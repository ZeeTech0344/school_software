<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankOutsourceAmount;
use App\Models\EasypaisaOutSource;
use App\Models\EasypaisaPaidAmount;
use App\Models\EmployeeAttendance;
use App\Models\LockerOutSource;
use App\Models\LockerPaidAmount;
use App\Models\Pendings;
use App\Models\salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use DateTime;
// use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PDF;
use App\Models\QurbaniPart;
use App\Models\Qurbani;
use App\Models\ConnectTeacherStudentBook;
use App\Models\Diary;
use App\Models\HolidayDiary;
use App\Models\Question;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class HomeController extends Controller
{

    function viewAmountOutsource(Request $req){

        $bank_amount_outsource = BankOutsourceAmount::with("getEmployee")->where("id", $req->id)->get();
        return view("accounts.view-outsource-amount", compact("bank_amount_outsource"));

    }


    function editAccountHead(Request $req){

        $head = User::find($req->id);
        return response()->json($head, 200);
    }

   function insertHolidayDiary(Request $req){



    $validation = [
        'book_id' => 'required',
        'student_id' => Rule::unique('holiday_diaries')->where(function ($query) use ($req) {
            return $query->where('attach_book_id', $req->book_id[0])
                        ->where('student_id', $req->student_id);
        }),
    ];

    if($req->student_diary_hidden_id){
        $validation["student_id"] = Rule::unique('holiday_diaries')->where(function ($query) use ($req) {
            return $query->where('attach_book_id', $req->book_id[0])
                        ->where('student_id', $req->student_id)->where("id", "!=" ,$req->student_diary_hidden_id);
        });
    }else{
        $validation["student_id"] = Rule::unique('holiday_diaries')->where(function ($query) use ($req) {
            return $query->where('attach_book_id', $req->book_id[0])
                        ->where('student_id', $req->student_id);
        });
    }




    $hazri_namaz = implode(",", $req->hazrinmaz);
    $questions = implode(",", $req->questions);
    $teacher_id = Auth::user()->id;

    if($req->student_diary_hidden_id){
        $holiday_diary = HolidayDiary::find($req->student_diary_hidden_id);
    }else{
        $holiday_diary = new HolidayDiary();
    }

    $holiday_diary->attach_book_id = $req->book_id[0];
    $holiday_diary->teacher_id = $teacher_id;
    $holiday_diary->ghar_kay_mamoolat = $req->ghar_kay_mamoolat;
    $holiday_diary->sonay_ka_waqt = $req->sonay_ka_waqt;
    $holiday_diary->hazrinmaz = $hazri_namaz;
    $holiday_diary->questions = $questions;
    $holiday_diary->session_id = $req->session_id;
    $holiday_diary->student_id = $req->student_id;
    $holiday_diary->hidayat_mualam = $req->hidayat_mualam;
    $holiday_diary->hidayat_sarparast = $req->hidayat_sarparat;
    $holiday_diary->save();


   }

    function viewReportBankAmount(Request $req, $from_date, $to_date, $bank_name){


        $closing_old_bank_amount = DB::table('bank_outsource_amounts')
            ->whereDate("created_at", "<", $from_date)
            ->where("bank_name", $bank_name)
            ->sum("amount");


        $get_data_old_sum = Bank::whereDate("created_at", "<", $from_date)
            ->where("bank_name", $bank_name)
            ->sum("amount");
    
        
        $old_vouchers_amount = DB::table('vouchers')
            ->whereDate("vouchers.updated_at", "<", $from_date)
            // ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", $bank_name)
            ->sum("recieved_amount");


        $grand_final_old_amount = $closing_old_bank_amount + $old_vouchers_amount -  $get_data_old_sum;



        //this code is disabled because we use send all voucher amount to locker if easypaisa will use then the code will enable

        $vouchers = DB::table('vouchers')
            ->join('admissions', 'admissions.id', '=', 'vouchers.student_id')
            ->join('classes', 'admissions.class_id', '=', 'classes.id')
            ->selectRaw('CONCAT(admissions.name, "(", classes.class ,")" , COALESCE(admissions.section, "") ) AS head, vouchers.updated_at as created_at, vouchers.recieved_amount as amount, vouchers.amount_status')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", $bank_name)
            ->get();


        $vouchers_amount = json_decode(json_encode($vouchers), true);



        $sum_of_bank_get = DB::table('bank_outsource_amounts')
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->where("bank_name", $bank_name)
            ->get();


        $sum_of_bank_datewise = json_decode(json_encode($sum_of_bank_get), true);


        //locker detail
        $get_data = DB::table('banks')
            ->join('users', 'users.id', '=', 'banks.employee_id')
            ->selectRaw(' CONCAT(COALESCE(users.employee_name, ""), " " , COALESCE(users.employee_post, "") ," - ",COALESCE(banks.purpose)) AS head , banks.amount, banks.amount_status, banks.remarks, banks.created_at')
            ->whereDate("banks.created_at", ">=", $from_date)
            ->whereDate("banks.created_at", "<=", $to_date)
            ->where("bank_name", $bank_name)
            ->get();

        $data = json_decode(json_encode($get_data), true);

       
        if($req->check_print == true){
            return view("accounts.bank-full-report-second-view", compact('vouchers_amount', 'sum_of_bank_datewise', 'grand_final_old_amount', 'data'));
        }
        $html = [];
        $html["title"] = "(".$bank_name.") بینک جمع کردہ/خرچ کردہ رپورٹ";
        $html["view"] = view("accounts.bank-full-report-second-view", compact('vouchers_amount', 'sum_of_bank_datewise', 'grand_final_old_amount', 'data'))->render();
        return response()->json($html, 200);

        
        
    }



    function deleteBankOutsourceAmount(Request $req){

        if($req->ajax()){
            $bank_outsource = BankOutsourceAmount::find($req->id);
            $bank_outsource->delete();
            return response()->json("deleted", 200);

        }

    }


    function editBankOutsourceAmount(Request $req){

        $bank_outsource = BankOutsourceAmount::find($req->id);
        return response()->json($bank_outsource, 200);
    }


    function bankOutsourceAmountList(Request $req){

        if ($req->ajax()) {
            $total_count = BankOutsourceAmount::count();
            $data = BankOutsourceAmount::offset($req->start)->limit(10)->orderBy("id", "desc");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('bank_name', function ($row) {
                    return $row->bank_name;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-bank-outsource-amount text-dark"  data-id="' . $row->id . '">Edit</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  delete-bank-outsource-amount text-dark"  data-id="' . $row->id . '">Delete</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  print-reciept text-dark"  data-id="' . $row->id . '">Print</a>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
        

    }



    function insertBankOutsourceAmount(Request $req){

        $validation = [
            "bank_name" => "required",
            "fund_type" => "required",
            "payment_type" => "required",
            "phone_no" => "required",
            "address" => "required",
            "remarks" => "required",
        ];

     
        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        if ($req->outsource_hidden_id) {
            $outsource = BankOutsourceAmount::find($req->outsource_hidden_id);
        } else {

            $check_invoice_no = BankOutsourceAmount::latest()->first();
            $outsource = new  BankOutsourceAmount();
            if($req->$check_invoice_no){
                $invoice_no =  $check_invoice_no->invoice_no + 1;
            }else{
                $invoice_no = 1000;
            }
        }

        $outsource->invoice_no = $invoice_no;
        $outsource->given_by = $req->given_by;
        $outsource->phone_no = $req->phone_no;
        $outsource->address = $req->address;
        $outsource->bank_name = $req->bank_name;
        $outsource->fund_type = $req->fund_type;
        $outsource->payment_type = $req->payment_type;
        $outsource->amount = $req->amount;
        $outsource->recieved_by = Auth::user()->id;
        $outsource->remarks = $req->remarks;
        $outsource->save();
        return response()->json("saved");

    }

    function bankOutsourceForm(Request $req){

        $bank_name = bankNames();
        return view("accounts.bank-outsource", compact("bank_name"));
    }



    function getFullReportOfBankAmount(){

        $bank_name = bankNames();
        return  view("accounts.get-full-report-bank-amount", compact("bank_name"));
    }


    function editBankAmount(Request $req){


        $paid_amount = bank::find($req->id);
        $get_detail_employee = User::find($paid_amount->employee_id);
        return response()->json([$paid_amount, $get_detail_employee], 200);
     

    }

    function getBankExpenseList(Request $req){

        if ($req->search_value) {
            $search_value = $req->search_value;

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->where("bank_name", $req->search_bank_name)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->where("bank_name", $req->search_bank_name)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->search_value &&  $req->from_date && $req->to_date) {

            $search_value = $req->search_value;

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {


            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();



            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->from_date && $req->to_date && $req->employee_others) {


            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->from_date && $req->to_date && $req->type) {

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->from_date && $req->to_date) {

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")->count();
            $data = Bank::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
        }


        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('bank_name', function ($row) {
                return  $row->bank_name;
            })
            ->addColumn('paid_date', function ($row) {
                return  date_format(date_create($row->created_at), "d-m-Y");
            })
            ->addColumn('employee', function ($row) {
                if ($row->getEmployee->employee_post !== null) {
                    $advance_date = date_format(date_create($row->paid_for_month_date), "d-M-Y");
                    return $row->getEmployee->employee_name . " (" . $row->getEmployee->employee_post . ")-" . $advance_date;
                } else {
                    return $row->getEmployee->employee_name;
                }
            })
            ->addColumn('purpose', function ($row) {
                return $row->purpose;
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount);
            })
            ->addColumn('remarks', function ($row) {
                return $row->remarks;
            })

            ->addColumn('action', function ($row) {


                //dont remove this code
                // if($row->status == "Paid"){

                //     $btn = '<div class="btn-group btn-sm">
                //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                //     Action
                //     </button>
                //     <div class="dropdown-menu">
                //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                // }else{

                //     $btn = '<div class="btn-group btn-sm">
                //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                //     Action
                //     </button>
                //     <div class="dropdown-menu">
                //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                // }

                //dont remove this code

                if ($row->purpose == "Others" ||  $row->purpose == "Advance") {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle"' . (Auth::User()->user_type == "User" ? "disabled" : "") . 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">';
                    $btn .= '<a href="javascript:void(0)" class="dropdown-item  edit-bank-amount text-dark"  data-id="' . $row->id . '">Edit</a>';
                    $btn .='<a href="javascript:void(0)" class="dropdown-item  print-expense text-dark"  data-id="' . $row->id . '">Print</a>';
                    $btn .= '</div></div>';
                } else {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                    Action
                    </button>
                    <div class="dropdown-menu">';
                    $btn .='<a href="javascript:void(0)" class="dropdown-item  edit-bank-amount text-dark"  data-id="' . $row->id . '">Edit</a>';
                    $btn .='<a href="javascript:void(0)" class="dropdown-item  print-expense text-dark"  data-id="' . $row->id . '">Print</a>';
                    $btn .= '</div></div>';
                }
                // $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                // $btn .= '</div>
                // </div>';


                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    



    function insertBankAmount(Request $req){

        if ($req->ajax()) {

            $validation = [
                'employee_id' =>  'required',
                'purpose' =>  'required',
                'amount' =>  'required',
                'bank_name' =>  'required'
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if ($req->hidden_id) {
                $bank_paid =  Bank::find($req->hidden_id);
            } else {

                $bank_amount = Bank::latest()->first();
                $bank_paid = new Bank();

                if($bank_amount){
                    $invoice_no = $bank_amount->invoice_no + 1;
                }else{
                    $invoice_no = 100000;
                }
            }

            $bank_paid->invoice_no = $invoice_no;
            $bank_paid->employee_id = $req->employee_id;
            $bank_paid->purpose = $req->purpose;
            if (isset($req->advance_payment_month)) {
                $bank_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }

            $bank_paid->given_by = Auth::user()->id;

            $bank_paid->status = "Paid";
            $bank_paid->amount = $req->amount;
            $bank_paid->bank_name = $req->bank_name;
            $bank_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $bank_paid->save();
        }
    }

    function viewBankForm(Request $req){

        $bank_name = bankNames();
        return view("accounts.view-bank-form", compact("bank_name"));

    }



    function viewQurbaniData(Request $req, $id, $qurbani_name){


         $get_qurbani_data = QurbaniPart::where("qurbani_id", 1)->get();

        $html = [];
        $html["title"] =  "( ".$qurbani_name.' ) '."قربانی";
        $html["view"] = view("academic.view-qurbani-data", compact("get_qurbani_data"))->render();
        return response()->json($html, 200);
        
        //return  view("academic.view-qurbani-data", compact("get_qurbani_data"));

    }

    function viewQurbani(Request $req){

        $get_qurbani = Qurbani::all();
        return view("academic.view-qurbani", compact("get_qurbani"));

    }


    function deleteStudentDiary(Request $req){
        $diary = diary::find($req->id);
        $diary -> delete();
        return response()->json("deleted", 200);
    }

    function editStudentDiary(Request $req){

        $diary = diary::with("getStudent")->where("id", $req->id)->first();
        return response()->json($diary, 200);

    }

    function getListOfDiary(Request $req){
        if ($req->ajax()) {

            if ($req->search_value) {

                $search_value = $req->search_value;
                $total_count = diary::with("getStudent")
                    ->whereHas('getStudent', function ($query) use ($search_value) {
                        $query->where("name", "like", '%' . $search_value . '%');
                    })->where("session_id", $req->session_id)
                    ->count();

                $data = diary::with("getStudent")
                ->whereHas('getStudent', function ($query) use ($search_value) {
                    $query->where("name", "like", '%' . $search_value . '%');
                })->where("session_id", $req->session_id)
                ->limit(10)->orderBy("id", "desc");

            } else {

                $total_count = diary::count();
                $data = diary::with("getStudent")->with("getAttachBooks")
                ->where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
         
            
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    return $row->getStudent->name;
                })
                ->addColumn('class', function ($row) {
                    return $row->getStudent->getClass->class.(isset($row->getStudent->section) ? "(".$row->getStudent->section.")" : "");
                })
    
                ->addColumn('book', function ($row) {
                    return $row->getAttachBooks->getBooks->book;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-student-diary"  data-id="' . $row->id . '">Edit</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  delete-student-diary"  data-id="' . $row->id . '">Delete</a>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }


    }



    function insertDiary(Request $req){

 
    $validation = [
        'book_id' => 'required',
        'student_id' => Rule::unique('diaries')->where(function ($query) use ($req) {
            return $query->where('attach_book_id', $req->book_id[0])
                        ->where('student_id', $req->student_id);
        }),
    ];

    if($req->student_diary_hidden_id){
        $validation["student_id"] = Rule::unique('diaries')->where(function ($query) use ($req) {
            return $query->where('attach_book_id', $req->book_id[0])
                        ->where('student_id', $req->student_id)->where("id", "!=" ,$req->student_diary_hidden_id);
        });
    }else{
        $validation["student_id"] = Rule::unique('diaries')->where(function ($query) use ($req) {
            return $query->where('attach_book_id', $req->book_id[0])
                        ->where('student_id', $req->student_id);
        });
    }

    $req->validate($validation);

    
    $teacher_id = Auth::user()->id;
    if($req->student_diary_hidden_id){
        $diary = Diary::find($req->student_diary_hidden_id);
    }else{
        
        $diary = new Diary();
    }

    $hazri_namaz = implode(', ', $req->hazrinmaz);
    $diary->attach_book_id = $req->book_id[0];
    $diary->teacher_id = $teacher_id;
    $diary->student_id = $req->student_id;
    $diary->sabaq_sunaya = $req->sabaq_sunaya;
    $diary->sunaya =  $req->sunaya;
    $diary->manzil_para =  $req->manzil_para;
    $diary->samay_one =  $req->samay_one;
    $diary->sabaq_sunai =  $req->sabaq_sunai;
    $diary->samay_four =  $req->samay_four;
    $diary->kacha_sabaq_sunaya=  $req->kacha_sabaq_sunaya;
    $diary->samay_three=  $req->samay_three;
    $diary->samay_two=  $req->samay_two;
    $diary->dia=  $req->dia;
    $diary->para_ya_teen_sabaq=  $req->para_ya_teen_sabaq;
    $diary->bad_zuhr_hazir_hua=  $req->bad_zuhr_hazir_hua;
    $diary->bad_maghrib_hazir_hua=  $req->bad_maghrib_hazir_hua;
    $diary->subah_hazir_hua=  $req->subah_hazir_hua;
    $diary->tak_satrain=  $req->tak_satrain;
    $diary->taa=  $req->taa;
    $diary->ayat_no=  $req->ayat_no;
    $diary->para_no=  $req->para_no;
    $diary->surah=  $req->surah;
    $diary->hazrinmaz=  $hazri_namaz ;
    $diary->description = $req->description;
    $diary->session_id = $req->session_id;
    $diary->save();
    return response()->json("saved", 200);


    }

    function diaryForm(Request $req, $day=null){

        

        $teacher_id = Auth::user()->id;
        $books_and_classes = ConnectTeacherStudentBook::with("getBooks")->with("getClasses")->where("teacher_id", $teacher_id)->where("session_id", 1)->get();
    
        if($day == "holiday"){
             $questions = Question::where("status", 1)->get();
            return view("academic.holiday-diary-form", compact('books_and_classes','questions'));
        }

        return view("academic.diary-form", compact('books_and_classes'));
        
    }


    function deleteQurbaniParts(Request $req)
    {

        $qurbani_parts = QurbaniPart::find($req->id);
        $qurbani_parts->delete();
    }

    function editQurbaniPartsData(Request $req)
    {

        $qurbani_data = QurbaniPart::find($req->id);
        return response()->json($qurbani_data, 200);
    }


    function getAllDataQurbani(Request $req)
    {


        if ($req->ajax()) {

            if ($req->search_value) {

                $search_value = $req->search_value;

                $total_count = QurbaniPart::with("getQurbaniInfo")
                    ->whereHas('getQurbaniInfo', function ($query) use ($search_value) {
                        $query->where("qurbani_name", "like", '%' . $search_value . '%');
                    })
                    ->count();

                $data = QurbaniPart::with("getQurbaniInfo")
                    ->whereHas('getQurbaniInfo', function ($query) use ($search_value) {
                        $query->where("qurbani_name", "like", '%' . $search_value . '%');
                    })->offset($req->start)->limit(10)->orderBy("id", "desc");
            } else {

                $total_count = QurbaniPart::count();
                $data = QurbaniPart::with("getQurbaniInfo")->offset($req->start)->limit(10)->orderBy("id", "desc");
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('qurbani_id', function ($row) {
                    return $row->getQurbaniInfo->qurbani_name;
                })
                ->addColumn('total_parts', function ($row) {
                    return $row->total_parts;
                })
                ->addColumn('total_parts_amount', function ($row) {
                    return $row->total_parts_amount;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-qurbani-parts-data"  data-id="' . $row->id . '">Edit</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  delete-qurbani-parts-data"  data-id="' . $row->id . '">Delete</a>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function insertQurbaniPartsData(Request $req)
    {


        if ($req->qurbani_hidden_id) {
            $qurbani = QurbaniPart::find($req->qurbani_hidden_id);
        } else {

            $find_serial_no = QurbaniPart::latest()->first();
           
            $qurbani = new QurbaniPart();

            if($find_serial_no){
                $qurbani->serial_no = $find_serial_no->serial_no + 1;
            }else{
                $qurbani->serial_no = 1000;
            }
        }

        
        $qurbani->full_name = $req->full_name;
        $qurbani->address = $req->address;
        $qurbani->phone_no = $req->phone_no;
        
        $qurbani->qurbani_id = $req->qurbani;
        $qurbani->total_parts = $req->total_parts;
        $qurbani->total_parts_amount = $req->total_amount;
        $qurbani->remarks = $req->remarks;
        $qurbani->save();

        return response()->json("saved", 200);
    }

    function viewQurbaniPart(Request $req, $id){

        $qurbani = QurbaniPart::with("getQurbaniInfo")->where("id", $id)->get();

     
        $html = [];
        $html["title"] = "قربانی حصہ";
        $html["view"] = view("academic.view-qurbani-part", compact("qurbani"))->render();
        return response()->json($html, 200);

           // return view("academic.view-qurbani-part", compact("qurbani"));
    }


    function getQurbaniData(Request $req)
    {


        $qurbani_data = Qurbani::find($req->qurbani_id);

        $count_qurbani = QurbaniPart::where("qurbani_id", $req->qurbani_id)->sum("total_parts");

        $qurbani_all_data = QurbaniPart::where("qurbani_id", $req->qurbani_id)->sum("total_parts_amount");

        return response()->json([$qurbani_data, $count_qurbani, $qurbani_all_data], 200);
    }

    function editQurbaniData(Request $req)
    {

        $data = Qurbani::find($req->id);
        return response()->json($data, 200);
    }

    function getAllQurbani()
    {
        $qurbani  = Qurbani::where("status", "On")->get();
        return response()->json($qurbani, 200);
    }



    function createQurbaniForm(Request $req)
    {

        return view("qurbani.qurbani-form");
    }


    function insertQurbaniData(Request $req)
    {


        $validation = [
            'amount' =>  'required',
            'remarks' =>  'required'
        ];


        if ($req->qurbani_hidden_id) {
            $validation["qurbani_name"] = "required|unique:qurbanis,qurbani_name," . $req->qurbani_hidden_id;
        } else {
            $validation["qurbani_name"] = "required|unique:qurbanis,qurbani_name";
        }


        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->qurbani_hidden_id) {
            $qurbani =  Qurbani::find($req->qurbani_hidden_id);
        } else {
            $qurbani = new Qurbani();
        }

        $qurbani->qurbani_name = $req->qurbani_name;
        $qurbani->amount = $req->amount;
        $qurbani->remarks = $req->remarks;
        $qurbani->save();
        return response()->json("saved", 200);
    }


    function getQurbaniList(Request $req)
    {

        if ($req->ajax()) {

            if ($req->search_value) {
                $total_count = Qurbani::where("qurbani_name", "like", '%' . $req->search_value . '%')->count();
                $data = Qurbani::where("qurbani_name", "like", '%' . $req->search_value . '%')->limit(10)->orderBy("id", "desc");
            } else {
                $total_count = Qurbani::count();
                $data = Qurbani::offset($req->start)->limit(10)->orderBy("id", "desc");
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('qurbani_name', function ($row) {
                    return $row->qurbani_name;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-qurbani-data"  data-id="' . $row->id . '">Edit</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  delete-qurbani-data"  data-id="' . $row->id . '">Delete</a>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    function createQurbaniPartsForm(Request $req)
    {


        return view("qurbani.create-qurbani-parts-form");
    }


    function updateQurbaniData(Request $req)
    {

        $data = Qurbani::find($req->id);
        return response()->json($data, 200);
    }



    function deleteEasypaisaOutsourceForm(Request $req)
    {

        if ($req->ajax()) {

            $outsource = EasypaisaOutSource::find($req->id);
            $outsource->delete();
            return response()->json($outsource, 200);
        }
    }

    function editEasypaisaOutsourceAmount(Request $req)
    {

        $outsource = EasypaisaOutSource::find($req->id);
        return response()->json($outsource, 200);
    }

    function easypaisaOutsourceList(Request $req)
    {


        if ($req->ajax()) {

            if($req->search_value){

                $keyword = $req->search_value;

                $total_count = EasypaisaOutSource::where('invoice_no', 'like', "%$keyword%")
                ->orWhere('amount', 'like', "%$keyword%")
                ->orWhere('remarks', 'like', "%$keyword%")->count();

                $data = EasypaisaOutSource::where('invoice_no', 'like', "%$keyword%")
                   ->orWhere('amount', 'like', "%$keyword%")
                   ->orWhere('remarks', 'like', "%$keyword%")
                   ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }else{
                $total_count = EasypaisaOutSource::count();
                $data = EasypaisaOutSource::offset($req->start)->limit(10)->orderBy("id", "desc");
            }
        
            return DataTables::of($data)
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice_no;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-outsource-amount"  data-id="' . $row->id . '">Edit</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  delete-easypaisa-outsource-amount"  data-id="' . $row->id . '">Delete</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  print-easypaisa-receipt"  data-id="' . $row->id . '">Print</a>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function printViewEasypaisaAmount(Request $req, $from_date = null, $to_date = null, $type = null, $employee_others = null)
    {


        $closing_old_amount_easypaisa = DB::table('easypaisa_out_sources')
            ->whereDate("created_at", "<", $from_date)
            ->sum("amount");


        $get_data_old_sum = EasypaisaPaidAmount::whereDate("created_at", "<", $from_date)
            ->sum("amount");
        



       $old_vouchers_amount = DB::table('vouchers')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", "Easypaisa")
            ->sum("recieved_amount");


        $grand_final_old_amount = $closing_old_amount_easypaisa + $old_vouchers_amount -  $get_data_old_sum;


        //this code is disabled because we use send all voucher amount to locker if easypaisa will use then the code will enable

        $vouchers = DB::table('vouchers')
            ->join('admissions', 'admissions.id', '=', 'vouchers.student_id')
            ->join('classes', 'admissions.class_id', '=', 'classes.id')
            ->selectRaw('CONCAT(admissions.name, "(", classes.class ,")" , COALESCE(admissions.section, "") ) AS head, vouchers.updated_at as created_at, vouchers.recieved_amount as amount, vouchers.amount_status')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", "Easypaisa")
            ->get();


        $vouchers_amount = json_decode(json_encode($vouchers), true);



        $sum_of_easypaisa_get = DB::table('easypaisa_out_sources')
            ->whereDate("easypaisa_out_sources.created_at", ">=", $from_date)
            ->whereDate("easypaisa_out_sources.created_at", "<=", $to_date)
            ->get();


        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('easypaisa_paid_amounts')
            ->join('users', 'users.id', '=', 'easypaisa_paid_amounts.employee_id')

            ->selectRaw(' CONCAT(COALESCE(users.employee_name, ""), " " , COALESCE(users.employee_post, "") ," - ",COALESCE(easypaisa_paid_amounts.purpose)) AS head , easypaisa_paid_amounts.amount, easypaisa_paid_amounts.amount_status, easypaisa_paid_amounts.remarks, easypaisa_paid_amounts.created_at')

            ->whereDate("easypaisa_paid_amounts.created_at", ">=", $from_date)
            ->whereDate("easypaisa_paid_amounts.created_at", "<=", $to_date)
            ->get();

        $data = json_decode(json_encode($get_data), true);

        return view("accounts.easypaisa-full-report-second-view", compact('vouchers_amount', 'sum_of_easypaisa_datewise', 'grand_final_old_amount', 'data'));
    }



    function easypaisaFullReportSecondView(Request $req, $from_date = null, $to_date = null)
    {



        $closing_old_amount_easypaisa = DB::table('easypaisa_out_sources')
            ->whereDate("created_at", "<", $from_date)
            ->sum("amount");


        $get_data_old_sum = EasypaisaPaidAmount::whereDate("created_at", "<", $from_date)
            ->sum("amount");


        $old_vouchers_amount = DB::table('vouchers')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", "Easypaisa")
            ->sum("recieved_amount");


        $grand_final_old_amount = $closing_old_amount_easypaisa + $old_vouchers_amount -  $get_data_old_sum;



        //this code is disabled because we use send all voucher amount to locker if easypaisa will use then the code will enable

        $vouchers = DB::table('vouchers')
            ->join('admissions', 'admissions.id', '=', 'vouchers.student_id')
            ->join('classes', 'admissions.class_id', '=', 'classes.id')
            ->selectRaw('CONCAT(admissions.name, "(", classes.class ,")" , COALESCE(admissions.section, "") ) AS head, vouchers.updated_at as created_at, vouchers.recieved_amount as amount, vouchers.amount_status')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", "Easypaisa")
            ->get();


        $vouchers_amount = json_decode(json_encode($vouchers), true);



        $sum_of_easypaisa_get = DB::table('easypaisa_out_sources')
            ->whereDate("easypaisa_out_sources.created_at", ">=", $from_date)
            ->whereDate("easypaisa_out_sources.created_at", "<=", $to_date)
            ->get();


        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('easypaisa_paid_amounts')
            ->join('users', 'users.id', '=', 'easypaisa_paid_amounts.employee_id')
            ->selectRaw(' CONCAT(COALESCE(users.employee_name, ""), " " , COALESCE(users.employee_post, "") ," - ",COALESCE(easypaisa_paid_amounts.purpose)) AS head , easypaisa_paid_amounts.amount, easypaisa_paid_amounts.amount_status, easypaisa_paid_amounts.remarks, easypaisa_paid_amounts.created_at')

            ->whereDate("easypaisa_paid_amounts.created_at", ">=", $from_date)
            ->whereDate("easypaisa_paid_amounts.created_at", "<=", $to_date)
            ->get();

        $data = json_decode(json_encode($get_data), true);


        $html = [];
        $html["title"] = "ایزی پیسہ جمع کردہ/خرچ کردہ رپورٹ";
        $html["view"] = view("accounts.easypaisa-full-report-second-view", compact('vouchers_amount', 'sum_of_easypaisa_datewise', 'grand_final_old_amount', 'data'))->render();
        return response()->json($html, 200);
    }




    function getViewEasypaisaAmountNewCreatedSecond(Request $req, $from_date = null, $to_date = null, $type = null, $employee_others = null)
    {

        if ($from_date && $to_date && $type && $employee_others) {


            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->where("employee_id", $employee_others)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();
        } elseif ($req->from_date && $req->to_date && $req->type) {

            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();
        }

        $html = [];
        $html["title"] = "ایزی پیسہ ویو";
        $html["view"] = view("accounts.get-view-easypaisa-amount-second", compact('data'))->render();
        return response()->json($html, 200);
    }


    function getFullReportofEasypaisaAmount(Request $req)
    {

        return  view("accounts.get-full-report-easypaisa-amount");
    }



    function lockerFullReportSecondView(Request $req, $from_date = null, $to_date = null, $type = null, $employee_others = null)
    {



        $closing_old_amount_locker = DB::table('locker_out_sources')
            ->whereDate("created_at", "<", $from_date)
            ->sum("amount");


        $get_data_old_sum = EasypaisaPaidAmount::whereDate("created_at", "<", $from_date)
            ->sum("amount");

        
        $old_vouchers_amount = DB::table('vouchers')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", "Locker")
            ->sum("recieved_amount");


        $grand_final_old_amount = $closing_old_amount_locker + $old_vouchers_amount -  $get_data_old_sum;


        $sum_of_easypaisa_get = DB::table('locker_out_sources')
            ->whereDate("locker_out_sources.created_at", ">=", $from_date)
            ->whereDate("locker_out_sources.created_at", "<=", $to_date)
            ->get();


        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('locker_paid_amounts')
            ->join('users', 'users.id', '=', 'locker_paid_amounts.employee_id')

            ->selectRaw(' CONCAT(COALESCE(users.employee_name, ""), " " , COALESCE(users.employee_post, "") ," - ",COALESCE(locker_paid_amounts.purpose)) AS head , locker_paid_amounts.amount, locker_paid_amounts.amount_status, locker_paid_amounts.remarks, locker_paid_amounts.created_at')

            ->whereDate("locker_paid_amounts.created_at", ">=", $from_date)
            ->whereDate("locker_paid_amounts.created_at", "<=", $to_date)
            ->get();

        $data = json_decode(json_encode($get_data), true);


        $html = [];
        $html["title"] = " لاکر جمع کردہ/خرچ کردہ رپورٹ";
        $html["view"] = view("accounts.locker-full-report-second-view", compact('vouchers_amount', 'sum_of_easypaisa_datewise', 'grand_final_old_amount', 'data'))->render();
        return response()->json($html, 200);
    }



    function printViewLockerAmount(Request $req, $from_date = null, $to_date = null, $type = null, $employee_others = null)
    {



        $closing_old_amount_locker = DB::table('locker_out_sources')
            ->whereDate("created_at", "<", $from_date)
            ->sum("amount");


        $get_data_old_sum = EasypaisaPaidAmount::whereDate("created_at", "<", $from_date)
            ->sum("amount");


            $old_vouchers_amount = DB::table('vouchers')
            ->whereDate("vouchers.updated_at", ">=", $from_date)
            ->whereDate("vouchers.updated_at", "<=", $to_date)
            ->where("vouchers.status", 1)
            ->where("account_name", "Locker")
            ->sum("recieved_amount");


        $grand_final_old_amount = $closing_old_amount_locker + $old_vouchers_amount -  $get_data_old_sum;



        //this code is disabled because we use send all voucher amount to locker if easypaisa will use then the code will enable

        $vouchers = DB::table('vouchers')
        ->join('admissions', 'admissions.id', '=', 'vouchers.student_id')
        ->join('classes', 'admissions.class_id', '=', 'classes.id')
        ->selectRaw('CONCAT(admissions.name, "(", classes.class ,")" , COALESCE(admissions.section, "") ) AS head, vouchers.updated_at as created_at, vouchers.recieved_amount as amount, vouchers.amount_status')
        ->whereDate("vouchers.updated_at", ">=", $from_date)
        ->whereDate("vouchers.updated_at", "<=", $to_date)
        ->where("vouchers.status", 1)
        ->where("account_name", "Locker")
        ->get();


        $vouchers_amount = json_decode(json_encode($vouchers), true);



        $sum_of_easypaisa_get = DB::table('locker_out_sources')
            ->whereDate("locker_out_sources.created_at", ">=", $from_date)
            ->whereDate("locker_out_sources.created_at", "<=", $to_date)
            ->get();


        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('locker_paid_amounts')
            ->join('users', 'users.id', '=', 'locker_paid_amounts.employee_id')

            ->selectRaw(' CONCAT(COALESCE(users.employee_name, ""), " " , COALESCE(users.employee_post, "") ," - ",COALESCE(locker_paid_amounts.purpose)) AS head , locker_paid_amounts.amount, locker_paid_amounts.amount_status, locker_paid_amounts.remarks, locker_paid_amounts.created_at')

            ->whereDate("locker_paid_amounts.created_at", ">=", $from_date)
            ->whereDate("locker_paid_amounts.created_at", "<=", $to_date)
            ->get();

        $data = json_decode(json_encode($get_data), true);



        return view("accounts.locker-full-report-second-view", compact('vouchers_amount', 'sum_of_easypaisa_datewise', 'grand_final_old_amount', 'data'));
    }



    function getFullReportofLockerAmount(Request $req)
    {

        return  view("accounts.get-full-report-locker-amount");
    }

    function checkBalance(Request $req)
    {

        //easypaisa detail
        $easypaisa_amount = DB::table('easypaisa_out_sources')
            ->sum("amount");

        $easypaisa_paid_amount  = DB::table('easypaisa_paid_amounts')
            ->sum("amount");

        $easypaisa_vouchers  = DB::table('vouchers')
        ->where("account_name", "Easypaisa")
        ->where("status", 1)
        ->sum("recieved_amount");


        //locker_detail
        $locker_amount = DB::table('locker_out_sources')
            ->sum("amount");

        $locker_paid_amount  = DB::table('locker_paid_amounts')
            ->sum("amount");

         $locker_vouchers  = DB::table('vouchers')
            ->where("account_name", "Locker")
            ->where("status", 1)
            ->sum("recieved_amount");


        //bank detail
        $bankSums = DB::table('banks')
        ->select('bank_name', DB::raw('SUM(amount) as total'))
        ->groupBy('bank_name')
        ->orderBy("bank_name","desc")
        ->get();

        $bankSums_array = json_decode(json_encode($bankSums), true);


       $outsourceSums = DB::table('bank_outsource_amounts')
        ->select('bank_name', DB::raw('SUM(amount) as outsource_sum'))
        ->groupBy('bank_name')
        ->orderBy("bank_name","desc")
        ->get();

        $outsourceSums_array = json_decode(json_encode($outsourceSums), true);

    

        $vouchers = DB::table('vouchers')
        ->select('account_name as bank_name', DB::raw('SUM(recieved_amount) as voucher_sum'))
        ->whereNotIn('account_name', ['Easypaisa', 'Locker'])
        ->where("status", 1)
        ->groupBy('account_name')
        ->orderBy("account_name","desc")
        ->get();

        $vouchers_array = json_decode(json_encode($vouchers), true);


        if ($req->ajax()) {
            $html = [];
            $html["title"] = "ٹوٹل موجودہ اکاونٹ رقم";
            $html["view"] = view("accounts.check-amount", compact("locker_vouchers","easypaisa_vouchers","bankSums_array","outsourceSums_array","vouchers_array","easypaisa_amount", "easypaisa_paid_amount", "locker_amount", "locker_paid_amount"))->render();
            return response()->json($html, 200);
        }
    }



    function getSalaryPdf(Request $req)
    {

        if ($req->ajax()) {
            $month = $req->month . "-" . "01";
            $last_date = date("Y-m-t", strtotime($month));

            $salary_detail = User::with(['easypaisa' => function ($query) use ($month) {
                $query->where('paid_for_month_date', $month)
                    ->where("purpose", "Advance");
            }])
                ->with(['locker' => function ($query) use ($month) {
                    $query->where('paid_for_month_date', $month)
                        ->where("purpose", "Advance");
                }])
                ->with(['salary' => function ($query) use ($month) {
                    $query->where('salary_month', $month);
                }])
                ->whereDate('joining', '<=', $last_date)
                ->where("account_for", "Employee")
                ->where("employee_status", "On")
                ->get();

            return view("accounts.get-salary-pdf", compact("salary_detail", "month"));
        }
    }



    function getSalaryUnpaidDetail(Request $req, $get_month)
    {
        if ($req->ajax()) {

            $month = $get_month . "-01";
            $last_date = date("Y-m-t", strtotime($month));

            $salary_detail = User::with(['easypaisa' => function ($query) use ($month) {
                $query->where('paid_for_month_date', $month)
                    ->where("purpose", "Advance");
            }])
                ->with(['locker' => function ($query) use ($month) {
                    $query->where('paid_for_month_date', $month)
                        ->where("purpose", "Advance");
                }])
                ->with(['pendings' => function ($query) use ($month, $last_date) {
                    $query->whereDate('created_at', ">=", $month)
                        ->whereDate('created_at', "<=", $last_date)
                        ->where("status", "Pending");
                }])
                ->whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })
                ->whereDate('joining', '<=', $last_date)
                ->where("account_for", "Employee")
                ->where("employee_status", "On")
                ->get();

            $html = [];
            $html["title"] = "Salary Report (" . date_format(date_create($month), "M-Y") . ")";
            $html["view"] = view("accounts.salary-unpaid-view", compact("salary_detail", "month"))->render();
            return response()->json($html, 200);
        }
    }

    function getPaidSalary(Request $req, $get_month)
    {

        if ($req->ajax()) {
            $month  = $get_month . "-" . "01";
            $data = salary::with("employee")
                ->where("salary_month", $month)
                ->orderBy("id", "asc")->get();
            $html = [];
            $html["title"] = "ُادا کردہ سیلری (" . date_format(date_create($month), "M-Y") . ")";
            $html["view"] = view("accounts.salary-paid-view", compact("data", "month"))->render();
            return response()->json($html, 200);
        }
    }

    function deleteSalaryRecord(Request $req)
    {

        if ($req->data[1] == "Easypaisa") {

            $easypaisa_paid_amount = EasypaisaPaidAmount::find($req->data[0]);
            $easypaisa_paid_amount->delete();

            DB::table('pendings')
                ->where("account_id", $req->data[0])
                ->where("account_name", $req->data[1])
                ->update(['status' => 'Pending', 'account_id' => null, 'account_name' => null]);

            DB::table('salaries')
                ->where("account_id", $req->data[0])
                ->where("account_name", $req->data[1])
                ->delete();
        } elseif ($req->data[1] == "Locker") {
            $easypaisa_paid_amount = LockerPaidAmount::find($req->data[0]);
            $easypaisa_paid_amount->delete();

            DB::table('pendings')
                ->where("account_id", $req->data[0])
                ->where("account_name", $req->data[1])
                ->update(['status' => 'Pending', 'account_id' => null, 'account_name' => null]);

            DB::table('salaries')
                ->where("account_id", $req->data[0])
                ->where("account_name", $req->data[1])
                ->delete();
        }
    }


    function getSalaryDetail(Request $req, $month_get)
    {


        if ($req->ajax()) {

            $month = $month_get . "-01";
            $last_date = date("Y-m-t", strtotime($month));

            $salary_detail = User::with(['easypaisa' => function ($query) use ($month) {
                $query->where('paid_for_month_date', $month)
                    ->where("purpose", "Advance");
            }])
                ->with(['locker' => function ($query) use ($month) {
                    $query->where('paid_for_month_date', $month)
                        ->where("purpose", "Advance");
                }])
                ->with(['salary' => function ($query) use ($month) {
                    $query->where('salary_month', $month);
                }])
                ->whereDate('joining', '<=', $last_date)
                ->where("account_for", "Employee")
                ->where("employee_status", "On")
                ->get();


            $html = [];
            $html["title"] = "سیلری رپورٹ (" . date_format(date_create($month_get), "M-Y") . ")";
            $html["view"] = view("accounts.get-salary-detail", compact("salary_detail", "month_get"))->render();
            return response()->json($html, 200);
        }
    }

    function finalSalaryInsert(Request $req)
    {


        if ($req->ajax()) {

            $validation = [
                'pay_through' =>  'required',

            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }



            $firstDayOfMonth = $req->paid_for_month;;
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));


            if ($req->pay_through == "Easypaisa") {
                $paid_amount = new EasypaisaPaidAmount();
                // $paid_amount->paid_date = date("Y-m-d");
                $paid_amount->employee_id = $req->employee_id;
                $paid_amount->purpose = "Salary";
                $paid_amount->paid_for_month_date = $req->paid_for_month;
                $paid_amount->status = "Paid";
                $paid_amount->amount = $req->salary;
                $paid_amount->save();

                $salary = new salary();
                $salary->employee_id = $req->employee_id;

                $salary->basic_salary = $req->basic_salary;
                $salary->advance = $req->get_advance;
                $salary->pendings = $req->pendings;
                // $salary->fuel_amount = $req->fuel_amount;
                $salary->day_of_work_deduction = $req->day_of_work_deduction;
                $salary->addition = $req->addition;
                $salary->remarks = $req->remarks;
                $salary->day_of_work = $req->day_of_work;

                $salary->amount = $req->salary;
                $salary->salary_month = $req->paid_for_month;
                $salary->status = "Paid";
                $salary->account_id  = $paid_amount->id;
                $salary->account_name  = $req->pay_through;
                $salary->save();

                DB::table('pendings')
                    ->where("employee_id", $req->employee_id)
                    ->where("status", "Pending")
                    ->whereDate("created_at", ">=", $firstDayOfMonth)
                    ->whereDate("created_at", "<=", $lastDayOfMonth)
                    ->update(['status' => 'Paid', 'account_id' => $paid_amount->id, 'account_name' => "Easypaisa"]);
            } elseif ($req->pay_through == "Locker") {
                $paid_amount = new LockerPaidAmount();
                // $paid_amount->paid_date = date("Y-m-d");
                $paid_amount->employee_id = $req->employee_id;
                $paid_amount->purpose = "Salary";
                $paid_amount->paid_for_month_date = $req->paid_for_month;
                $paid_amount->status = "Paid";
                $paid_amount->amount = $req->salary;
                $paid_amount->save();

                $salary = new salary();
                $salary->employee_id = $req->employee_id;
                $salary->basic_salary = $req->basic_salary;
                $salary->advance = $req->get_advance;
                $salary->pendings = $req->pendings;
                //$salary->fuel_amount = $req->fuel_amount;
                $salary->day_of_work_deduction = $req->day_of_work_deduction;
                $salary->addition = $req->addition;
                $salary->remarks = $req->remarks;
                $salary->day_of_work = $req->day_of_work;
                //this salary is after deduction
                $salary->amount = $req->salary;
                $salary->salary_month = $req->paid_for_month;
                $salary->status = "Paid";
                $salary->account_id  = $paid_amount->id;
                $salary->account_name  = $req->pay_through;
                $salary->save();

                DB::table('pendings')
                    ->where("employee_id", $req->employee_id)
                    ->where("status", "Pending")
                    ->whereDate("created_at", ">=", $firstDayOfMonth)
                    ->whereDate("created_at", "<=", $lastDayOfMonth)
                    ->update(['status' => 'Paid', 'account_id' => $paid_amount->id, 'account_name' => "Easypaisa"]);
            }
        }
    }

    function checkPendings(Request $req)
    {

        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
        $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');


        $check_pendings = DB::table('pendings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("status", "Pending")
            ->whereDate("created_at", ">=", $firstDayOfPreviousMonth)
            ->whereDate("created_at", "<=", $lastDayOfPreviousMonth)
            ->get();

        return response()->json([(isset($check_pendings[0]) ? $check_pendings[0]->sum : 0)], 200);
    }

    function checkAdvanceSalary(Request $req)
    {


        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
        $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');

        $check_advance_easypaisa = DB::table('easypaisa_paid_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("purpose", "Advance")
            ->whereDate("paid_for_month_date", ">=", $firstDayOfPreviousMonth)
            ->whereDate("paid_for_month_date", "<=", $lastDayOfPreviousMonth)
            ->get();

        $check_advance_locker = DB::table('locker_paid_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("purpose", "Advance")
            ->whereDate("paid_for_month_date", ">=", $firstDayOfPreviousMonth)
            ->whereDate("paid_for_month_date", "<=", $lastDayOfPreviousMonth)
            ->get();


        //locker advance check 

        return response()->json([(isset($check_advance_locker[0]) ? $check_advance_locker[0]->sum : 0) + (isset($check_advance_easypaisa[0]) ? $check_advance_easypaisa[0]->sum : 0) + (isset($check_advance_hbl[0]) ? $check_advance_hbl[0]->sum : 0)], 200);
    }
    function getPendingList(Request $req)
    {



        if ($req->ajax()) {

            if ($req->search_value && $req->from_date_pending && $req->to_date_pending) {

                $search_value = $req->search_value;

                $count_data =  Pendings::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pendings::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->search_value) {

                $search_value = $req->search_value;

                $count_data =  Pendings::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })->count();

                $data = Pendings::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status && $req->pending_employee_id) {


                $count_data = Pendings::where("employee_id", $req->pending_employee_id)
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pendings::with("getStaff:id,employee_name,employee_post")
                    ->where("employee_id", $req->pending_employee_id)
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_employee_id) {


                $count_data = Pendings::where("employee_id", $req->pending_employee_id)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pendings::with("getStaff:id,employee_name,employee_post")
                    ->where("employee_id", $req->pending_employee_id)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status) {


                $count_data = Pendings::where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();


                $data = Pendings::with("getStaff:id,employee_name,employee_post")
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending) {

                $count_data = Pendings::whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pendings::with("getStaff:id,employee_name,employee_post")
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } else {



                $count_data = Pendings::count();
                $data = Pendings::with("getStaff:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->getStaff->employee_post . "-" . $row->getStaff->employee_name;
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })

                ->addColumn('action', function ($row) {

                    if ($row->status == "Pending") {
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" ' . (Auth::User()->user_type == "User" ? "disabled" : "") . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .=  '<a href="javascript:void(0)" class="dropdown-item  edit-pending-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-pending-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';
                    } else {
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-pending-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-pending-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';
                    }

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($count_data)
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function getEmployee(Request $req)
    {

        $staff = User::where("account_for", "Employee")->where("employee_status", "On")->get();
        return response()->json($staff, 200);
    }

    function insertPending(Request $req)
    {


        if ($req->ajax()) {

            $validation = [
                'date' =>  'required',
                'employee_id' =>  'required',
                'amount' =>  'required',
                'remarks' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if ($req->hidden_id) {
                $pending = Pendings::find($req->hidden_id);
            } else {
                $pending = new Pendings();
            }

            $pending->date = $req->date;
            $pending->employee_id = $req->employee_id;
            $pending->amount = $req->amount;
            $pending->status = "Pending";
            $pending->remarks = $req->remarks;
            $pending->save();
            return response()->json(['saved'], 200);
        }
    }

    function pendingForm(Request $req)
    {

        return view("accounts.pending-form");
    }

    function payNowSalary(Request $req, $id, $date, $salary, $name, $joining, $employee_post)
    {

        $firstDayOfMonth = $date;
        $lastDayOfMonth = date('Y-m-t', strtotime($date));

        $get_attendance = EmployeeAttendance::where("employee_id", $id)
            ->whereDate("date", ">=", $firstDayOfMonth)
            ->whereDate("date", "<=", $lastDayOfMonth)
            ->where("attendance_type", "present")
            ->count();

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pay Salary (" . date_format(date_create($date), "M-Y") . ")";
            $html["view"] = view("accounts.pay-now-salary", compact("get_attendance", "id", "date", "salary", "name", "joining", "employee_post"))->render();
            return response()->json($html, 200);
        }
    }

    function salaryForm(Request $req)
    {

        return view("accounts.salary");
    }

    function getDataofEmployeeSalary(Request $req)
    {
        // ->whereDoesntHave('salary', function ($query) use ($month) {
        //     $query->where('salary_month', $month);
        // })
        if ($req->ajax()) {
            if ($req->month) {
                $count_employee = count(DB::table('users')->where("account_for", "Employee")
                    ->where("employee_status", "On")
                    ->get());
                $month  = $req->month . "-" . "01";
                $last_date = date("Y-m-t", strtotime($month));
                $data = User::whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })->where("employee_status", "On")
                    ->whereDate('joining', '<=', $last_date)
                    ->where("account_for", "Employee")->orderBy("id", "asc");
            } else {
                $count_employee = count(DB::table('users')
                    ->where("account_for", "Employee")
                    ->where("employee_status", "On")->get());
                $month  = date("Y-m") . "-" . "01";
                $last_date = date("Y-m-t", strtotime($month));
                $data = User::whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })
                    ->where("employee_status", "On")
                    ->whereDate('joining', '<=', $last_date)
                    ->where("account_for", "Employee")->orderBy("id", "asc");
            }
            // })->where("plot_area", $req->block)->where("status", "On")->offset($req->start)->limit(10)->orderBy("id", "DESC");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('employee_no', function ($row) {
                    return $row->employee_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('post', function ($row) {
                    return $row->employee_post;
                })

                ->addColumn('salary', function ($row) {
                    return number_format($row->basic_sallary);
                })

                ->addColumn('action', function ($row) use ($month) {
                    $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                <a href="javascript:void(0)" class="dropdown-item  pay_now_salary"  data-id="' . $row->id . "," . $month . "," . $row->basic_sallary . "," . $row->employee_name . "," . $row->joining . "," . $row->employee_post . '">View Salary</a>';
                    $btn .= '</div>
                </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function deleteLockerOutsourceAmount(Request $req)
    {

        $outsource = LockerOutSource::find($req->id);
        $outsource->delete();
        return response()->json("deleted", 200);
    }

    function editLockerOutsourceAmount(Request $req)
    {

        $outsource = LockerOutSource::find($req->id);
        return response()->json($outsource, 200);
    }



    function getLockerOutsourceAmountList(Request $req)
    {

        if ($req->ajax()) {
            $total_count = LockerOutSource::count();
            $data = LockerOutSource::offset($req->start)->limit(10)->orderBy("id", "desc");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-locker-outsource-amount"  data-id="' . $row->id . '">Edit</a>';
                    $btn .=  '<a href="javascript:void(0)" class="dropdown-item  delete-locker-outsource-amount"  data-id="' . $row->id . '">Delete</a>';
                    $btn .= '</div></div>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function insertLockerOutsourceAmount(Request $req)
    {


        $validation = [
            "remarks" => "required"
        ];

        if ($req->outsource_hidden_id) {
            $validation["amount"] = [
                'required',
                Rule::unique('easypaisa_out_sources')->ignore($req->outsource_hidden_id)
            ];
        } else {
            $validation["amount"] = [
                'required',
            ];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        if ($req->outsource_hidden_id) {
            $outsource = LockerOutSource::find($req->outsource_hidden_id);
        } else {
            $outsource = new  LockerOutSource();
        }

        $outsource->amount = $req->amount;
        $outsource->remarks = $req->remarks;
        $outsource->save();
        return response()->json("saved");
    }

    function lockerOutsourceForm(Request $req)
    {

        return view("accounts.locker-outsource-form");
    }



    function editLockerAmount(Request $req)
    {

        $easypaisa_paid_amount = LockerPaidAmount::find($req->id);

        $get_detail_employee = User::find($easypaisa_paid_amount->employee_id);

        return response()->json([$easypaisa_paid_amount, $get_detail_employee], 200);
    }


    function getLockerAmountList(Request $req)
    {


        if ($req->ajax()) {

            if ($req->search_value) {
                $search_value = $req->search_value;

                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->count();

                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->search_value &&  $req->from_date && $req->to_date) {

                $search_value = $req->search_value;

                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {


                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();



                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date && $req->employee_others) {


                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date && $req->type) {

                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date) {

                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } else {

                $total_count = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")->count();
                $data = LockerPaidAmount::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('paid_date', function ($row) {
                    return  date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee', function ($row) {
                    if ($row->getEmployee->employee_post !== null) {
                        $advance_date = date_format(date_create($row->paid_for_month_date), "d-M-Y");
                        return $row->getEmployee->employee_name . " (" . $row->getEmployee->employee_post . ")-" . $advance_date;
                    } else {
                        return $row->getEmployee->employee_name;
                    }
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('action', function ($row) {


                    //dont remove this code
                    // if($row->status == "Paid"){

                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                    // }else{

                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                    // }

                    //dont remove this code

                    if ($row->purpose == "Others" ||  $row->purpose == "Advance") {

                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"' . (Auth::User()->user_type == "User" ? "disabled" : "") . 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .= '<a href="javascript:void(0)" class="dropdown-item  edit-locker-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '</div></div>';
                    } else {

                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-locker-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '</div></div>';
                    }
                    // $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                    // $btn .= '</div>
                    // </div>';


                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    function insertLockerAmount(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                'employee_id' =>  'required',
                'purpose' =>  'required',
                'amount' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if ($req->hidden_id) {
                $locker_paid =  LockerPaidAmount::find($req->hidden_id);
            } else {
                $locker_paid = new LockerPaidAmount();
            }

            $locker_paid->employee_id = $req->employee_id;
            $locker_paid->purpose = $req->purpose;

            if (isset($req->advance_payment_month)) {
                $locker_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }

            $locker_paid->status = "Paid";
            $locker_paid->amount = $req->amount;
            $locker_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $locker_paid->save();
        }
    }

    function lockerForm(Request $req)
    {

        return view("accounts.locker-form");
    }


    function insertEasypaisaOutsource(Request $req)
    {


        $validation = [
            "remarks" => "required",
            "amount" => "required"
        ];

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        if ($req->outsource_hidden_id) {
            $outsource = EasypaisaOutSource::find($req->outsource_hidden_id);
        } else {
            $exist_invoice = EasypaisaOutSource::latest()->first();
            $outsource = new  EasypaisaOutSource();

            $invoice = "";
            if($exist_invoice){
                $invoice = $exist_invoice->invoice_no + 1;
            }else{
                $invoice = 1000;
            }
            $outsource->invoice_no = $invoice;
        }

        $outsource->amount = $req->amount;
        $outsource->given_by = $req->given_by;
        $outsource->recieved_by = Auth::user()->id;
        $outsource->remarks = $req->remarks;
        $outsource->save();
        return response()->json("saved");

    }

    function easypaisaOutsourceForm(Request $req)
    {

        return view('accounts.easypaisa-outsource');
    }


    function getReportofEasypaisaAmount(Request $req)
    {   
        

        if ($req->ajax()) {



            if ($req->search_value) {
                $search_value = $req->search_value;

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->search_value &&  $req->from_date && $req->to_date) {

                $search_value = $req->search_value;

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereHas('getEmployee', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {


                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();



                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date && $req->employee_others) {


                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date && $req->type) {

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->from_date && $req->to_date) {

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } else {

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")->count();
                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('paid_date', function ($row) {
                    return  date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee', function ($row) {
                    if ($row->getEmployee->employee_post !== null) {
                        $advance_date = date_format(date_create($row->paid_for_month_date), "d-M-Y");
                        return $row->getEmployee->employee_name . " (" . $row->getEmployee->employee_post . ")-" . $advance_date;
                    } else {
                        return $row->getEmployee->employee_name;
                    }
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('action', function ($row) {


                    //dont remove this code
                    // if($row->status == "Paid"){

                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                    // }else{

                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                    // }

                    //dont remove this code

                    if ($row->purpose == "Others" ||  $row->purpose == "Advance") {

                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"' . (Auth::User()->user_type == "User" ? "disabled" : "") . 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .= '<a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a href="javascript:void(0)" class="dropdown-item  view-receipt"  data-id="' . $row->id . '">View Receipt</a>';
                        $btn .= '</div></div>';
                    } else {

                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item view-receipt"  data-id="' . $row->id . '">View Receipt</a>';
                        $btn .= '</div></div>';
                    }
                    // $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                    // $btn .= '</div>
                    // </div>';


                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function editEasypaisaPaidAmount(Request $req)
    {

        $easypaisa_paid_amount = EasypaisaPaidAmount::find($req->id);

        $get_detail_employee = User::find($easypaisa_paid_amount->employee_id);

        return response()->json([$easypaisa_paid_amount, $get_detail_employee], 200);
    }




    function insertAccountHead(Request $req)
    {


        if ($req->head_hidden_id) {
            // When updating, check if the new name conflicts with any existing names except the current record
            $validation = [
                'employee_name' => [
                    'required',
                    Rule::unique('users', 'employee_name')->ignore($req->head_hidden_id),
                ]
            ];
        } else {
            // When creating a new record, simply check for existence of the name
            $validation = [
                'employee_name' => 'required|unique:users,employee_name'
            ];
        }
        
        
        

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->head_hidden_id) {
            $head = User::find($req->head_hidden_id);
        } else {
            $head = new User();
        }

        //actually we paste code from other software therefore it we use employee name as head
        $head->employee_name =  $req->employee_name;
        $head->account_for =  "Others";
        $head->employee_status = $req->employee_status;
        $head->save();
        return response()->json("saved", 200);
    }

    function accountHead(Request $req)
    {

        return view("academic.add-account-head");
    }


    function getHeadList(Request $req){


        if ($req->ajax()) {

            // if ($req->search_value) {

            //     $count_employee = User::where("employee_name", "like", '%' . $req->search_value . '%')->where('account_for', "Teacher")->count();

            //     $data = User::where("employee_name", "like", '%' . $req->search_value . '%')->where('account_for', "Employee")->offset($req->start)->limit(10)->orderBy("id", "desc");
            // } else {

                $count_head = user::where('account_for', "Others")->count();
                $data = user::where('account_for', "Others")
                ->where("employee_status", "On")
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
            // }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('head', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('status', function ($row) {
                    return $row->employee_status;
                })
               
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_head"  data-id="' . $row->id . '">Edit</a>';
                    $btn .= '</div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_head)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }

    }

    function getEmployees(Request $req)
    {

        if ($req->employee_type) {
            $data = User::where("account_for", $req->employee_type)
                ->where("employee_status", "On")->get();
        }

        return response()->json($data);
    }

    function insertEasypaisaAmount(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                'employee_id' =>  'required',
                'purpose' =>  'required',
                'amount' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if ($req->hidden_id) {
                $easypaisa_paid =  EasypaisaPaidAmount::find($req->hidden_id);
            } else {
                $easypaisa_paid = new EasypaisaPaidAmount();
            }

            $easypaisa_paid->employee_id = $req->employee_id;
            $easypaisa_paid->purpose = $req->purpose;
            if (isset($req->advance_payment_month)) {
                $easypaisa_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }

            $easypaisa_paid->status = "Paid";
            $easypaisa_paid->amount = $req->amount;
            $easypaisa_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $easypaisa_paid->save();
        }
    }

    function easypaisaForm()
    {

        return view("accounts.easypaisa-form");
    }

    function addEmployeeOtherSecondForm()
    {

        return view("accounts.add-employee-other-second-form");
    }


    function insertEmployeeOthers(Request $req)
    {

        $validation = [
            'employee_name' =>  'required',
            'employee_post' => 'required',
            'basic_sallary' => 'required',
            'dob' => 'required',
            'cnic' => 'required',
            'basic_sallary' => 'required',
            'employee_status' =>  'required',
            'cnic' =>  'unique:users,cnic'
        ];


        if ($req->employee_hidden_id) {
            $validation["cnic"] = 'unique:users,cnic,' . $req->employee_hidden_id;
            $validation["name"] ='nullable|unique:users,name,' . $req->employee_hidden_id;
        } 

        $validator = Validator::make($req->all(), $validation);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        // $Employee_last_id = User::latest()->get()->first();

        if ($req->employee_hidden_id) {
            $employee = User::find($req->employee_hidden_id);
        } else {
            $get_employee_no = User::where("account_for", "Employee")->latest()->first();
            $employee = new User();
            if ($get_employee_no == "") {
                $employee->employee_no = 10000;
            } else {
                $employee->employee_no = $get_employee_no->employee_no + 1;
            }
        }


        $employee->employee_name = $req->employee_name;

        $employee->employee_post = $req->employee_post;
        $employee->cnic = $req->cnic;
        $employee->dob = $req->dob;
        $employee->basic_sallary = $req->basic_sallary;
        $employee->cnic =  $req->cnic;
        $employee->phone_no =  $req->phone_no;
        $employee->father_cnic =  $req->father_cnic;
        $employee->father_phone_no =  $req->father_phone_no;
        $employee->employee_status = $req->employee_status;
        $employee->joining = $req->joining;
        $employee->leaving = $req->leaving;
        $employee->account_for = "Employee";
        $employee->name = $req->name;
        if (isset($req->image)) {
            $imageName = time() . '.' . $req->image->extension();
            $req->image->move(public_path('images'), $imageName);
            $employee->image = $imageName;
        }
        if (isset($req->password)) {

            $employee->password = $req->password;
        }

        $employee->save();
        return response()->json("saved", 200);
    }


    function getDataofEmployee(Request $req)
    {

        if ($req->ajax()) {

            if ($req->search_value) {

                $count_employee = User::where("employee_name", "like", '%' . $req->search_value . '%')->where('account_for', "Teacher")->count();

                $data = User::where("employee_name", "like", '%' . $req->search_value . '%')->where('account_for', "Employee")->offset($req->start)->limit(10)->orderBy("id", "desc");
            } else {

                $count_employee = User::where('account_for', "Teacher")->count();

                $data = User::where('account_for', "Employee")->offset($req->start)->limit(10)->orderBy("id", "desc");
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->employee_no ? "(" . $row->employee_no . ") " . $row->employee_name . "-" . $row->employee_post : $row->employee_name;
                })
                ->addColumn('basic_salary', function ($row) {
                    return $row->basic_sallary ?  number_format($row->basic_sallary) : "-";
                })

                ->addColumn('cnic', function ($row) {
                    return $row->cnic ? $row->cnic : "-";
                })

                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no ?  $row->phone_no : "-";
                })
                ->addColumn('doj', function ($row) {
                    return $row->joining ? date_format(date_create($row->joining), "d-m-Y") : "-";
                })
                ->addColumn('dol', function ($row) {
                    return $row->leaving ?  date_format(date_create($row->leaving), "d-m-Y") : "-";
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item  edit_employee"  data-id="' . $row->id . '">Edit</a>';
                    $btn .= '</div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function editEmployee(Request $req)
    {

        $employee =  User::where("id", $req->id)->get();
        return response()->json($employee, 200);
    }


    //     //auth functions



    function register()
    {

        return view('auth.register');
    }


    function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name|max:20',
            'password' => 'required|max:20|confirmed',
            'password_confirmation' => 'required',
            'account_for' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $register = new User();
        $register->name = trim($request->name);
        $register->password = Hash::make(trim($request->password));
        $register->user_type = trim($request->user_type);
        $register->account_for = trim($request->account_for);
        $register->save();
        return json_encode("saved");
    }


    function loginForm()
    {

        if (!Auth::check()) {
            return view('auth.login');
            // return Redirect::route('login');
        }
        // return view('auth.login-form');
        return redirect('/');
    }


    function postLogin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);



        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {

            return redirect('/');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }




    function logout()
    {
        session()->flush();
        Auth::logout();

        return Redirect('login');
    }
}
