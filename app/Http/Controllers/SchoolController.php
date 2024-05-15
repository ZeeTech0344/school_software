<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AttachHead;
use App\Models\Bank;
use App\Models\Book;
use App\Models\classes;
use App\Models\ConnectTeacherStudentBook;
use App\Models\CreateExam;
use App\Models\CreatePaper;
use App\Models\Department;
use App\Models\Diary;
use App\Models\EasypaisaOutSource;
use App\Models\EmployeeAttendance;
use App\Models\Fine;
use App\Models\HolidayDiary;
use App\Models\ObtainedMark;
use App\Models\Question;
use App\Models\session;
use App\Models\StudentAttendance;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;


class SchoolController extends Controller
{

    function printResultSheet(Request $req)
    {


        $session = $req->session;
        $exam_class_id =  $req->exam_class_id;
        $exam_section = $req->exam_section;
        $exam_list_get = $req->exam_list_get;
        $exam_name = $req->exam_name;

        // $user_id = 4;
        // $session = 1;
        // $exam_class_id =  2;
        // $exam_section = "کوئی نہیں";
        // $exam_list_get = 1;

        $books = ConnectTeacherStudentBook::with("getBooks")
            ->where("class_id", $exam_class_id)
            ->where("section", $exam_section)
            ->where("session_id", $session)
            ->orderBy("book_id", "asc")
            ->get()

            ->pluck('getBooks') // Pluck only the 'getBooks' relation
            ->toArray();

        $results =  Admission::with("getClass")
            ->with("marks")
            ->whereHas('marks.createPaper', function ($query)  use ($exam_list_get) {
                $query->where("create_exam_id", $exam_list_get);
            })
            ->where('class_id', $exam_class_id)
            ->where('section',  $exam_section)
            ->where('admission_year', $session)
            ->where('status', 1)
            ->get()->toArray();


        $check_position = Admission::select('id')
            ->withSum('marks', 'marks')
            ->whereHas('marks.createPaper', function ($query) use ($exam_list_get) {
                $query->where("create_exam_id", $exam_list_get);
            })
            ->where('class_id', $exam_class_id)
            ->where('section', $exam_section)
            ->where('admission_year', $session)
            ->where('status', 1)
            ->get()->toArray();


        // Sort the students array based on marks_sum_marks
        usort($check_position, function ($a, $b) {
            if ($a['marks_sum_marks'] == $b['marks_sum_marks']) {
                return 0;
            }
            return ($a['marks_sum_marks'] > $b['marks_sum_marks']) ? -1 : 1;
        });

        // Initialize position
        $position = 1;

        // Loop through the sorted array to assign positions
        foreach ($check_position as $index => $student) {
            // Check if the current student has the same marks as the previous student
            if ($index > 0 && $check_position[$index - 1]['marks_sum_marks'] == $student['marks_sum_marks']) {
                // If marks are the same, assign the same position as the previous student
                $check_position[$index]['position'] = $check_position[$index - 1]['position'];
            } else {
                // Otherwise, assign the current position
                $check_position[$index]['position'] = $position;
            }
            $position++;
        }


        
        // $html = [];
        // $html["title"] = "امتحانی رپورٹ";
        // $html["view"] =  view("academic.student-attendance-report", compact("attendance_data", "numberOfDays", "startAttendanceThisDate", "endAttendanceThisDate"))->render();
        // return response()->json($html, 200);

        return view("academic.exam-grandsheet", compact("check_position", "results", "books"));
    }


    function viewReceipt(Request $req)
    {

        if ($req->ajax()) {
            $acocunts = Bank::with("getEmployee")->where("id", $req->expense_id)->latest()->first();

            // Render the view to HTML content
            $viewContent = view("accounts.view-receipt", compact("acocunts"))->render();

            // Return only the HTML content
            return response()->json($viewContent);
        }
    }


    function employeeAttendanceQrCodeTimeToGoInsert(Request $req)
    {

        $employee_id = $req->insert_attendance[0]["employee_id"];
        $date = $req->insert_attendance[0]["date"];
        $time_out = $req->insert_attendance[0]["time_out"];

        $check_attendance = EmployeeAttendance::where("employee_id", $employee_id)->where("date", $date)->first();
        if ($check_attendance) {
            $attendance_id = $check_attendance->id;
            $attendance_update =  EmployeeAttendance::find($attendance_id);
            $attendance_update->time_out = $time_out;
            $attendance_update->save();
            return response()->json("success", 200);
        }
    }


    function employeeAttendanceQrCodeTimeToGoView()
    {

        return view("accounts.employee-attendance-qrcode-go-view");
    }

    function employeeIdCardView(Request $req)
    {

        $employee_data = User::where("account_for", "employee")->where("employee_status", "On")->get()->toArray();
        return view("accounts.employee-idcard-view", compact("employee_data"));
    }


    function employeeAttendanceQrCodeView(Request $req)
    {

        return view("accounts.employee-attendance-qrcode-view");
    }

    function studentAttendanceReport(Request $req, $from_date, $to_date, $class_id, $section)
    {


        $attendance_data =  Admission::with(['attendance' => function ($query) use ($from_date, $to_date) {
            $query->whereDate("date", ">=", $from_date)->whereDate("date", "<=", $to_date);
        }])->where("class_id", $class_id)->where("section", $section)->get();



        $startDate = Carbon::parse($from_date);
        $endDate = Carbon::parse($to_date);

        // Calculate the number of days between the start and end dates
        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        $startAttendanceThisDate = $startDate->day;
        $endAttendanceThisDate = $endDate->day;

        $html = [];
        $html["title"] = "سٹاف حاضری رپورٹ";
        $html["view"] =  view("academic.student-attendance-report", compact("attendance_data", "numberOfDays", "startAttendanceThisDate", "endAttendanceThisDate"))->render();
        return response()->json($html, 200);

        // return view("academic.student-attendance-report", compact("attendance_data", "numberOfDays", "startAttendanceThisDate", "endAttendanceThisDate"));


    }

    function studentAttendanceReportView(Request $req)
    {

        $classes = classes::with("getDepartments")->get();
        $section = sections();

        return view("academic.student-attendance-report-view", compact("classes", "section"));
    }


    function studentAttendanceQrCodeGoView(Request $req)
    {


        return view("academic.student-attendance-qrcode-go-view");
    }

    function timeToGoStudentAttendanceUpdate(Request $req)
    {




        $student_id = $req->insert_attendance[0]["student_id"];
        $date = $req->insert_attendance[0]["date"];
        $time_out = $req->insert_attendance[0]["time_out"];

        $check_attendance = StudentAttendance::where("student_id", $student_id)->where("date", $date)->first();
        if ($check_attendance) {
            $attendance_id = $check_attendance->id;
            $attendance_update =  StudentAttendance::find($attendance_id);
            $attendance_update->time_out = $time_out;
            $attendance_update->save();
            return response()->json("success", 200);
        }
    }

    function studentAttendanceQrCode()
    {

        return view("academic.student-attendence-qrcode-view");
    }

    function getStaffAttendanceReport(Request $req)
    {

        return view("accounts.get-staff-attendance-report");
    }

    function employeeAttendanceReport(Request $req)
    {


        $getMonth = $req->for_the_month;

        $customDate = $getMonth . "-" . "01";

        $startDate = Carbon::createFromFormat('Y-m-d', $customDate);

        // Format the start date in the desired format "d-m-Y"
        $formattedStartDate = $startDate->format('Y-m-d');

        // Get the last day of the month
        $endDate = $startDate->copy()->endOfMonth();

        // Format the end date in the desired format "d-m-Y"
        $formattedEndDate = $endDate->format('Y-m-d');

        //  $attendance_data = EmployeeAttendance::with("getEmployeeData")->whereDate("date", ">=" ,$formattedStartDate)
        // ->whereDate("date", "<=" ,$formattedEndDate)
        // ->get();

        $attendance_data =  User::whereHas('attendance', function ($query) use ($startDate, $endDate) {
            $query->whereDate("date", ">=", $startDate)->whereDate("date", "<=", $endDate);
        })->where("account_for", "Employee")->get();

        $startDate = Carbon::parse($formattedStartDate);
        $endDate = Carbon::parse($formattedEndDate);

        // Calculate the number of days between the start and end dates
        $numberOfDays = $startDate->diffInDays($endDate) + 1;



        $html = [];
        $html["title"] = "سٹاف حاضری رپورٹ";
        $html["view"] =  view("accounts.employee-attendance-report", compact("attendance_data", "numberOfDays"))->render();
        return response()->json($html, 200);



        // return view("accounts.employee-attendance-report", compact("attendance_data", "numberOfDays"));

    }

    function deleteStudentHolidayDiary(Request $req)
    {

        $holidayDiary = HolidayDiary::find($req->id);
        $holidayDiary->delete();
        return response()->json("deleted", 200);
    }

    function editStudentHolidayDiary(Request $req)
    {

        $id = $req->id;
        $holidayDiary = HolidayDiary::with("getStudent")->where("id", $id)->first();
        return response()->json($holidayDiary, 200);
    }

    function getListOfHolidayDiary(Request $req)
    {

        if ($req->ajax()) {

            $user_id = Auth::user()->id;
            $total_count = HolidayDiary::where("teacher_id", $user_id)->count();

            $data = HolidayDiary::with([
                'getAttachBooks.getBooks',
                'getStudent.getClass.getDepartments',
                'getAttendances'
            ])
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");


            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('student_name', function ($row) {
                    return $row->getStudent->name;
                })
                ->addColumn('class', function ($row) {
                    return $row->getStudent->getClass->class . "-" . $row->getStudent->section . "(" . $row->getStudent->getClass->getDepartments->department . ")";
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
                    <a href="javascript:void(0)" class="dropdown-item edit-student-diary"  data-id="' . $row->id . '">Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item view-diary"  data-id="' . $row->id . '">View</a>
                    <a href="javascript:void(0)" class="dropdown-item delete-student-diary"  data-id="' . $row->id . '">Delete</a>
                    </div>
                    </div>';
                    // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function getDiary(Request $req, $diary_of_class, $get_student_name, $diary_date)
    {


        if ($req->ajax()) {

            $diary_of_class_array = explode(",", $diary_of_class);

            $attach_book_id = $diary_of_class_array[0];
            $student_id_get = $get_student_name;

            $customTimestamp = strtotime($diary_date);

            // Get the day of the week
            $dayOfWeek = date("l", $customTimestamp);

            if ($dayOfWeek == "Sunday" || $dayOfWeek == "Saturday") {

                $holidayDiary = HolidayDiary::with([
                    'getAttachBooks.getBooks',
                    'getStudent.getClass.getDepartments',
                    'getAttendances' => function ($query) use ($diary_date) {
                        $query->whereDate("date", ">=", $diary_date)
                            ->whereDate("date", "<=",  $diary_date);
                    },
                ])
                    ->where("attach_book_id", $attach_book_id)
                    ->where("student_id",  $student_id_get)
                    ->whereDate("created_at", ">=", $diary_date)
                    ->whereDate("created_at", "<=", $diary_date)
                    ->first();


                $get_questions = Question::where("status", "1")->get();

                $html = [];
                $html["title"] = "ہفتہ وار چھٹی";
                $html["view"] =  view("academic.get-holiday-diary", compact("holidayDiary", "get_questions"))->render();
                return response()->json($html, 200);
            } else {

                $diary = Diary::with([
                    'getAttachBooks.getBooks',
                    'getStudent.getClass.getDepartments',
                    'getAttendances' => function ($query) use ($diary_date) {
                        $query->whereDate("date", ">=", $diary_date)
                            ->whereDate("date", "<=", $diary_date);
                    },
                ])
                    ->where("attach_book_id", $attach_book_id)
                    ->where("student_id",  $student_id_get)
                    ->whereDate("created_at", ">=", $diary_date)
                    ->whereDate("created_at", "<=", $diary_date)
                    ->get();

                $html = [];
                $html["title"] = "یومیہ کیفیت";
                $html["view"] =  view("academic.get-diary", compact("diary"))->render();
                return response()->json($html, 200);
            }
        }



        // return view("academic.get-diary", compact("diary"));

    }


    function viewDiary(Request $req)
    {

        //change session id
        $teacher_id = Auth::user()->id;
        $books_and_classes = ConnectTeacherStudentBook::with("getBooks")->with("getClasses")->where("teacher_id", $teacher_id)->where("session_id", 1)->get();
        return view("academic.view-diary", compact("books_and_classes"));
    }


    function viewEmployeeProfile(Request $req, $id)
    {

        if ($req->ajax()) {
            $employees = User::where("account_for", "employee")->where("employee_status", "On")
                ->where("id", $id)->get()->first();

            $html = [];
            $html["title"] = $employees->employee_name;
            $html["view"] =  view("accounts.employee-profile", compact("employees"))->render();
            return response()->json($html, 200);
        }

        // return view("accounts.employee-profile", compact("employees"));


    }



    function employeeListView()
    {

        return view("accounts.employee-list-view");
    }


    function employeeIdCard(Request $req)
    {

        if (count($req->checkboxValues) <= 0) {

            $employee = user::where("account_for", "Employee")->where("employee_status", "On")->get()->toArray();
        } else {
            $employee = user::whereIn('id', $req->checkboxValues)->get()->toArray();
        }

        return view("academic.employee-id-card", compact("employee"));
    }



    function employeeList(Request $req)
    {

        if ($req->ajax()) {

            // $employees = User::where("account_for", "employee")->where("employee_status","On")->get();

            $total_count = User::where("account_for", "employee")->where("employee_status", "On")->count();


            $data = User::where("account_for", "employee")->where("employee_status", "On")->orderBy("id", "desc");


            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('check_box', function ($row) {
                    return '<div class="checkbox-wrapper-13">
                <input id="c1-13" type="checkbox" value="' . $row->id . '">
                </div>';
                })

                ->addColumn('employee_name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })
                ->addColumn('cnic', function ($row) {
                    return $row->cnic;
                })
                ->addColumn('salary', function ($row) {
                    return $row->basic_sallary;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('employee_status', function ($row) {
                    return $row->employee_status;
                })

                ->addColumn('view_only', function ($row) {
                    return '<a href="javascript:void(0)" class="view_profile btn  btn-success"  data-id="' . $row->id . '">View</a>';
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_employee"  data-id="' . $row->id . '">Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item view_profile"  data-id="' . $row->id . '">View</a>
                    </div>
                    </div>';
                    // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'check_box', 'view_only'])
                ->make(true);
        }
    }

    function employeeListAttendenceView(Request $req)
    {

        return view("accounts.employee-list-attendance-view");
    }


    function employeeListAttendence(Request $req)
    {


        if ($req->ajax()) {

            $date = $req->date;
            $total_count = User::where("account_for", "employee")->where("employee_status", "On")->count();

            $data = User::where("account_for", "employee")
                ->where("employee_status", "On")
                ->with(['attendance' => function ($query) use ($date) {
                    $query->where('date', $date);
                }])
                ->get();

            $increment = 1; // Initialize the increment variable

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('employee_no', function ($row) {
                    return $row->employee_no;
                })
                ->addColumn('employee_name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('attendance_type', function ($row) use (&$increment) {
                    $class = 'attendence_type' . $increment;
                    $increment++; // Increment the variable for the next row

                    if (count($row->attendance) <= 0) {
                        return '<div>
                        حاضر<input type="radio" name="' . $class . '" checked value="present" data-id="' . $row->id . '" class="attendence_type form_control">
                        <input type="radio" name="' . $class . '" value="absent" data-id="' . $row->id . '" class="attendence_type form_control mr-5">غیر حاضر
                        <input type="radio" name="' . $class . '" value="holiday" data-id="' . $row->id . '" class="attendence_type form_control mr-5"> چھٹی
                        <input type="radio" name="' . $class . '" value="leave" data-id="' . $row->id . '" class="attendence_type form_control mr-5">درخواست</div>';
                    } else {

                        //In this code we provide attendance id because this id is used for update
                        return '<div>
                        حاضر<input type="radio" name="' . $class . '"  value="present" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control" ' . ($row->attendance[0]->attendance_type == "present" ? "checked" : "") . '>
                        <input type="radio" name="' . $class . '" value="absent" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5" ' . ($row->attendance[0]->attendance_type == "absent" ? "checked" : "") . '>غیر حاضر
                        <input type="radio" name="' . $class . '" value="holiday" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5" ' . ($row->attendance[0]->attendance_type == "holiday" ? "checked" : "") . '>چھٹی
                        <input type="radio" name="' . $class . '" value="leave" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5" ' . ($row->attendance[0]->attendance_type == "leave" ? "checked" : "") . '>درخواست</div>';
                    }
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['attendance_type', 'check_box'])
                ->make(true);
        }
    }
    // <input type="radio" name="' . $class . '" value="half_leave" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5" '.($row->attendance[0]->attendance_type == "half_leave" ? "checked" : "").'>آدھی چھٹی</div>';

    function employeeListAttendenceInsert(Request $req)
    {

        if ($req->ajax()) {



            if ($req->qr_attendance) {

                $employee_id = $req->insert_attendance[0]["employee_id"];
                $date = $req->insert_attendance[0]["date"];

                $check_already_exist = EmployeeAttendance::where("employee_id", $employee_id)->where("date", $date)->first();

                if (!$check_already_exist) {
                    EmployeeAttendance::insert($req->insert_attendance);
                    return response()->json("success", 200);
                } else {
                    return response()->json([$check_already_exist], 400);
                }
            }


            EmployeeAttendance::insert($req->insert_attendance);

            if (count($req->update_attendance) > 0) {

                $update_array = $req->update_attendance;

                $updateQuery = 'UPDATE employee_attendances SET attendance_type = CASE ';
                foreach ($update_array as $employee_data) {


                    $updateQuery .= 'WHEN id = ' . $employee_data["employee_id"] . ' THEN ' . ' "' . $employee_data["attendance_type"] . '" ';
                }
                $updateQuery .= 'ELSE attendance_type END';

                // Execute the update query
                DB::statement($updateQuery);
            }
        }
    }

    function getAdmissionReport(Request $req)
    {


        if ($req->ajax()) {

            $from_date = $req->data["from_date"];
            $to_date = $req->data["to_date"];
            $class_id = $req->data["class_id"];
            $section = $req->data["section"];
            $report_type = $req->data["report_type"];
            $session = $req->data["session"];



            if ($from_date && $to_date && $class_id && $section && $report_type && $session) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")->where("class_id", $class_id)
                    ->where("section", $section)
                    ->where("status", $report_type)
                    ->where("admission_year", $session)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();
            } elseif ($from_date && $to_date && $class_id && $section) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->where("class_id", $class_id)
                    ->where("section", $section)
                    ->where("admission_year", $session)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();
            } elseif ($from_date && $to_date && $class_id) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->where("class_id", $class_id)
                    ->where("admission_year", $session)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();
            } elseif ($from_date && $to_date && $report_type) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->where("status", $report_type)
                    ->where("admission_year", $session)
                    ->get();
            } elseif ($from_date && $to_date) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->where("admission_year", $session)
                    ->get();
            }

            $html = [];
            $html["title"] = "داخلہ رپورٹ" . "(" . $from_date . "سے" . $to_date . ")";
            $html["view"] =  view("academic.get-admission-report", compact("admission"))->render();
            return response()->json($html, 200);
        }
    }


    function printAdmissions(Request $req)
    {



        if ($req->ajax()) {

            $from_date = $req->data["from_date"];
            $to_date = $req->data["to_date"];
            $class_id = $req->data["class_id"];
            $section = $req->data["section"];
            $report_type = $req->data["report_type"];
            $session = $req->data["session"];



            if ($from_date && $to_date && $class_id && $section && $report_type && $session) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")->where("class_id", $class_id)
                    ->where("section", $section)
                    ->where("status", $report_type)
                    ->where("admission_year", $session)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();
            } elseif ($from_date && $to_date && $class_id && $section) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->where("class_id", $class_id)
                    ->where("section", $section)
                    ->where("admission_year", $session)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();
            } elseif ($from_date && $to_date && $class_id) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->where("class_id", $class_id)
                    ->where("admission_year", $session)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();
            } elseif ($from_date && $to_date && $report_type) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->where("status", $report_type)
                    ->where("admission_year", $session)
                    ->get();
            } elseif ($from_date && $to_date) {

                $admission = Admission::with("getClass")->with("getClass.getDepartments")
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->where("admission_year", $session)
                    ->get();
            }

            return view("academic.get-admission-report", compact("admission"));
        }
    }

    function admissionReportView(Request $req)
    {


        $classes = classes::with("getDepartments")->get();
        $sections = sections();



        return view("academic.admission-report-view", compact("classes", "sections"));
    }


    function getClassesDepartmentWise(Request $req)
    {

        $class = classes::where("department_id", $req->id)->get();
        return response()->json($class, 200);
    }


    function editClasses(Request $req)
    {

        $class = classes::find($req->id);
        return response()->json($class, 200);
    }


    function getClasses(Request $req)
    {

        if ($req->search_value) {

            $search = $req->search_value;
            $total_count = classes::where("class", "like", '%' . $search . '%')->count();
            $data = classes::with("getDepartments")->where("class", "like", '%' . $search . '%')
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = classes::count();
            $data = classes::with("getDepartments")->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('class', function ($row) {
                return $row->class;
            })
            ->addColumn('department_id', function ($row) {
                return $row->getDepartments->department;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_classes"  data-id="' . $row->id . '">Edit</a>
                  
                    </div>
                    </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function insertClasses(Request $req)
    {

        $department_id = $req->department_id;
        $class_hidden_id = $req->class_hidden_id;

        if ($req->class_hidden_id) {

            $validation["class"] = ['required', Rule::unique('classes')
                ->where(function ($query) use ($department_id, $class_hidden_id) {
                    return $query->where("department_id", $department_id);
                })->ignore($class_hidden_id)];
        } else {
            $validation["class"] = ['required', Rule::unique('classes')
                ->where(function ($query) use ($department_id) {
                    return $query->where("department_id", $department_id);
                })];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->class_hidden_id) {
            $class = classes::find($req->class_hidden_id);
        } else {
            $class = new classes();
        }

        $class->class = $req->class;
        $class->department_id =  $req->department_id;
        $class->status =  $req->status;
        $class->save();
        return response()->json("saved", 200);
    }


    function getDepartmentList(Request $req)
    {

        $departments = Department::all();
        return response()->json($departments);
    }

    function attachDepartmentToClassesForm(Request $req)
    {

        $departments = Department::all();
        return view("academic.attach-department-to-classes-form", compact("departments"));
    }
    function editDepartment(Request $req)
    {

        $department = Department::find($req->id);
        return response()->json($department);
    }

    function departmentList(Request $req)
    {


        if ($req->search_value) {

            $search = $req->search_value;
            $total_count = Department::where("department", "like", '%' . $search . '%')->count();
            $data = Department::where("department", "like", '%' . $search . '%')
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = Department::count();
            $data = Department::offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }



        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('department', function ($row) {
                return $row->department;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_department"  data-id="' . $row->id . '">Edit</a>
                  
                    </div>
                    </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function insertDepartment(Request $req)
    {


        if ($req->department_hidden_id) {
            $validation["department"] = [
                'required',
                Rule::unique('departments')->ignore($req->department_hidden_id)
            ];
        } else {
            $validation["department"] = [
                'required',
                Rule::unique('departments')
            ];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->department_hidden_id) {
            $departments = Department::find($req->department_hidden_id);
        } else {
            $departments = new Department();
        }

        $departments->department = $req->department;
        $departments->save();
        return response()->json("saved", 200);
    }

    function departmentForm(Request $req)
    {

        return view("academic.add-department-form");
    }


    function totalStudent(Request $req)
    {
        $students = Admission::where("admission_year", $req->session)->count();
        return response()->json($students, 200);
    }

    function totalStaff(Request $req)
    {
        $employee = user::where("account_for", "Employee")->where("employee_status", "On")->count();
        return response()->json($employee, 200);
    }

    function dashboard(Request $req)
    {

        return view("dashboard.dashboard");
    }

    function viewResultSheets(Request $req)
    {


        if ($req->ajax()) {

            $sections = sections();
            $classes = classes::all();
            $html = [];
            $html["title"] = "رزلٹ شیٹ پرنٹ";
            $html["view"] =  view("exam.view-result-sheet", compact("classes", "sections"))->render();
            return response()->json($html, 200);
        }
    }


    function printResult(Request $req)
    {


        $session = $req->session;
        $exam_class_id =  $req->exam_class_id;
        $exam_section = $req->exam_section;
        $exam_list_get = $req->exam_list_get;
        $exam_name = $req->exam_name;


        $check_position  = Admission::withSum('marks', 'marks')
            ->whereHas('marks.createPaper', function ($query) use ($exam_list_get) {
                $query->where("create_exam_id", $exam_list_get);
            })
            ->where('class_id', $exam_class_id)
            ->where('section',  $exam_section)
            ->where('admission_year', $session)
            ->where('status', 1)
            ->get()->toArray();

        $results =  Admission::with("getClass")
            ->with("marks.createPaper.getTeacherConnectedData.getBooks")
            ->whereHas('marks.createPaper', function ($query)  use ($exam_list_get) {
                $query->where("create_exam_id", $exam_list_get);
            })
            ->where('class_id', $exam_class_id)
            ->where('section',  $exam_section)
            ->where('admission_year', $session)
            ->where('status', 1)
            ->get()->toArray();



        //this code is for testing

        // $check_position  = Admission::withSum('marks', 'marks')
        // ->whereHas('marks.createPaper', function($query) {
        //     $query->where("create_exam_id", 1 );
        //  })
        //  ->where('class_id', 1 )
        //  ->where('section', 'اے')
        //  ->where('admission_year', 1)
        //  ->where('status', 1)
        //  ->get()->toArray();

        // $results =  Admission::with("getClass")
        //  ->with("marks.createPaper.getTeacherConnectedData.getBooks")
        //  ->whereHas('marks.createPaper', function($query) {
        //     $query->where("create_exam_id", 1 );
        //  })
        //  ->where('class_id', 1 )
        //  ->where('section', 'اے')
        //  ->where('admission_year', 1)
        //  ->where('status', 1)
        //  ->get()->toArray();


        return view("exam.print-result-sheets", compact("results", "check_position", "exam_name"));
    }

    function editObtainedMarks(Request $req, $create_paper_id, $session_id)
    {



        $all_students = ObtainedMark::with("admission")->where("create_paper_id", $create_paper_id)
            ->where("session_id", $session_id)
            ->get()->toArray();


        if ($req->ajax()) {

            $html = [];
            $html["title"] = "حاصل کردہ نمبر فارم";
            $html["view"] =  view("exam.edit-student-marks", compact("all_students", "create_paper_id", "session_id"))->render();
            return response()->json($html, 200);
        }
    }


    function insertObtainedMarks(Request $req)
    {

        $obtained_marks = $req->myArray;

        //insert Data
        $filteredArray = [];

        foreach ($obtained_marks as $item) {
            if ($item['update_student_id'] === null) {
                unset($item['update_student_id']);
                $filteredArray[] = $item;
            }
        }



        if (count($filteredArray) > 0) {
            ObtainedMark::insert($filteredArray);
        }



        //this code is for update marks
        $update_ids = array_column($obtained_marks, "update_student_id");

        if ($update_ids[0]  !== null) {


            $update_marks = array_column($obtained_marks, "marks");
            // Create an associative array of studentId => mark
            $studentIdMarkPairs = array_combine($update_ids, $update_marks);

            $updateQuery = 'UPDATE obtained_marks SET marks = CASE ';
            foreach ($studentIdMarkPairs as $studentId => $studentMark) {
                $updateQuery .= 'WHEN id = ' . $studentId . ' THEN ' . $studentMark . ' ';
            }
            $updateQuery .= 'ELSE marks END';

            // Execute the update query
            DB::statement($updateQuery);
        }

        return response()->json("saved", 200);
    }


    function addStudentMarks(Request $req, $data, $session_id)
    {

        if ($req->ajax()) {
            $array = explode(',', $data);
            $paper_created_id = $array[0];
            $total_marks = $array[1];
            $class_id = $array[2];
            $section = $array[3];


            $all_students = Admission::where("admission_year", $session_id)
                ->where("class_id", $class_id)
                ->where("section", $section)
                ->where("status", 1)
                ->get()->toArray();


            $already_add_marks = ObtainedMark::with("admission")->where("create_paper_id", $paper_created_id)
                ->where("session_id", $session_id)
                ->get()->toArray();


            foreach ($all_students  as $key => $student) {

                foreach ($already_add_marks as $data) {
                    if ($student["id"] == $data["student_id"]) {
                        $all_students[$key]["update_id"] = $data["id"];
                        $all_students[$key]["marks"] = $data["marks"];
                    } else {
                        // $all_students[$key]["marks"] = "";
                    }
                }
            }


            // echo "<pre>";
            // print_r($all_students);
            // echo "</pre>";

            // return false;



            $html = [];
            $html["title"] = "حاصل کردہ نمبر فارم";
            $html["view"] =  view("exam.add-student-marks", compact("all_students", "array"))->render();
            return response()->json($html, 200);
        }
    }


    function editPaperData(Request $req)
    {

        $paper = CreatePaper::find($req->id);
        return response()->json($paper, 200);
    }

    function getExams(Request $req)
    {

        $exams = CreateExam::where("session_id", $req->session)->get();
        return response()->json($exams, 200);
    }



    function getPaperList(Request $req)
    {


        $total_count = CreatePaper::where("session_id", $req->session)->count();

        $teacher_id = Auth::user()->id;

        if (Auth::user()->employee_post == "استاد") {
            $data = CreatePaper::with("getTeacherConnectedData.getBooks")
                ->with("getTeacherConnectedData.getClasses")
                ->with("getTeacherConnectedData.getTeachers")
                ->where("session_id", $req->session)->offset($req->start)
                ->whereHas('getTeacherConnectedData', function ($query) use ($teacher_id) {
                    $query->where("teacher_id", $teacher_id);
                })
                ->limit(10)
                ->orderBy("id", "desc");
        } else {
            $data = CreatePaper::with("getTeacherConnectedData.getBooks")
                ->with("getTeacherConnectedData.getClasses")
                ->with("getTeacherConnectedData.getTeachers")
                ->where("session_id", $req->session)->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)


            ->addColumn('connected_teacher_id', function ($row) {
                return $row->getTeacherConnectedData->getTeachers->employee_name;
            })

            ->addColumn('exam', function ($row) {
                return $row->exam->exam_name;
            })

            ->addColumn('paper', function ($row) {
                return $row->getTeacherConnectedData->getBooks->book .
                    " (" . $row->getTeacherConnectedData->getClasses->class . "-" . $row->getTeacherConnectedData->section . ")";
            })

            ->addColumn('marks', function ($row) {
                return $row->marks;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_paper_data"  data-id="' . $row->id . '">Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item add_student_marks"  data-id="' . $row->id . "," . $row->marks . "," . $row->getTeacherConnectedData->class_id . "," . $row->getTeacherConnectedData->section . '">Add Marks</a>
                    </div>
                    </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function insertCreatePaper(Request $req)
    {


        $hidden_id = $req->paper_hidden_id;
        $session_id = $req->session_id;
        $exam_id = $req->exam_id;
        $connect_teacher_id  = $req->connect_teacher_id;

        if ($req->paper_hidden_id) {
            $validation["connect_teacher_id"] = ['required', Rule::unique('create_papers')
                ->where(function ($query) use ($session_id,  $exam_id) {
                    return $query
                        ->where("create_exam_id", $exam_id)
                        ->where("session_id", $session_id);
                })->ignore($hidden_id)];
        } else {
            $validation["connect_teacher_id"] = ['required', Rule::unique('create_papers')
                ->where(function ($query) use ($session_id, $exam_id) {
                    return $query->where("create_exam_id", $exam_id)
                        ->where("session_id", $session_id);
                })];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        if ($req->paper_hidden_id) {
            $create_paper = CreatePaper::find($req->paper_hidden_id);
        } else {
            $create_paper = new CreatePaper();
        }

        $create_paper->connect_teacher_id = $req->connect_teacher_id;
        $create_paper->create_exam_id = $req->exam_id;
        $create_paper->marks = $req->marks;
        $create_paper->session_id =  $req->session_id;
        $create_paper->save();
        return response()->json("saved", 200);
    }


    function createPaper(Request $req)
    {

        if ($req->ajax()) {

            $html = [];
            $html["title"] = "پرچہ  فارم";
            $html["view"] =  view("exam.create-paper")->render();
            return response()->json($html, 200);
        }
    }

    function getTeacherAttachData(Request $req)
    {

        //get teacher attach books
        $session_id = $req->session;
        $user_id = Auth::user()->id;
        $teacher_subject = ConnectTeacherStudentBook::with("getClasses")->with("getBooks")->where("teacher_id", $user_id)
            ->where("session_id", $session_id)->get();
        return response()->json($teacher_subject, 200);
    }

    function getSectionsList()
    {

        $section = sections();
        return response()->json($section, 200);
    }

    function createClass()
    {

        $classes = classes::all();
        $sections = sections();
        return view("academic.create-class", compact("classes", "sections"));
    }


    function createStudentMark(Request $req)
    {
        $classes = classes::all();
        $sections = sections();
        return view("exam.create-marksheet", compact("classes", "sections"));
    }

    function insertExam(Request $req)
    {

        if ($req->hidden_id) {
            $exam = createExam::find($req->hidden_id);
        } else {
            $exam = new CreateExam();
        }
        $exam->exam_name = $req->exam_name;
        $exam->session_id = $req->session_id;
        $exam->save();
        return response()->json("saved");
    }


    function getExamList(Request $req)
    {


        $total_count = CreateExam::count();

        $data = CreateExam::where("session_id", $req->session)
            ->offset($req->start)
            ->limit(10)
            ->orderBy("id", "desc");

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('exam_name', function ($row) {
                return $row->exam_name;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_exam"  data-id="' . $row->id . '">Edit</a>
                  
                    </div>
                    </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }


    function editExamName(Request $req)
    {

        $exam = createExam::find($req->id);
        return response()->json($exam, 200);
    }


    function createExam(Request $req)
    {

        return view("exam.create-exam");
    }




    function getDataConnectTeacherClassBooksList(Request $req)
    {

        $getTeacherData = ConnectTeacherStudentBook::find($req->id);
        return response()->json($getTeacherData, 200);
    }

    function connectTeacherClassBooksList(Request $req)
    {



        $total_count = ConnectTeacherStudentBook::count();

        $data = ConnectTeacherStudentBook::with("getTeachers:id,employee_name")
            ->with("getBooks:id,book")
            ->with("getClasses:id,class")
            ->offset($req->start)
            ->limit(10)
            ->orderBy("id", "desc");



        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('teacher', function ($row) {
                return $row->getTeachers->employee_name;
            })
            ->addColumn('class', function ($row) {
                return $row->getClasses->class . " ( " . $row->section . " )";
            })
            ->addColumn('book', function ($row) {
                return $row->getBooks->book;
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_teaher_books"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }


    function insertConnectTeacherClassBooks(Request $req)
    {

        $books = $req->book_id;
        $teacher_id = $req->teacher_id;
        $class_id =  $req->class_id;
        $section =  $req->section_id;
        $hidden_id = $req->hidden_id;
        $session_id = $req->session_id;

        $validation = [];

        if ($req->hidden_id) {
            $validation["book_id"] = ['required', Rule::unique('connect_teacher_student_books')->where(function ($query) use ($session_id, $books, $teacher_id, $class_id, $section, $hidden_id) {
                return $query->where('book_id', $books)->where("teacher_id", $teacher_id)->where("class_id", $class_id)
                    ->where("section", $section)
                    ->where("session_id", $session_id);;
            })->ignore($hidden_id)];
        } else {
            $validation["book_id"] = ['required', Rule::unique('connect_teacher_student_books')->where(function ($query) use ($session_id, $books, $teacher_id, $class_id, $section, $hidden_id) {
                return $query->where('book_id', $books)->where("teacher_id", $teacher_id)
                    ->where("class_id", $class_id)
                    ->where("section", $section)
                    ->where("session_id", $session_id);
            })];
        }



        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        if ($req->hidden_id) {
            $teacherData = ConnectTeacherStudentBook::find($req->hidden_id);
            $teacherData->teacher_id = $req->teacher_id;
            $teacherData->class_id = $req->class_id;
            $teacherData->section = $req->section_id;
            $teacherData->book_id = $req->book_id[0];
            $teacherData->save();
            return response()->json("updated", 200);
        }






        $timestamp = Carbon::now();

        $count_book = count($req->book_id);

        $create_array = [];

        for ($a = 0; $a < $count_book; $a++) {
            $create_array[] = [
                "book_id" => $req->book_id[$a],
                "class_id" => $req->class_id,
                "section" => $req->section_id,
                "teacher_id" => $req->teacher_id,
                "session_id" => $req->session_id,
                "created_at" =>  $timestamp,
                "updated_at" =>  $timestamp,
            ];
        }

        ConnectTeacherStudentBook::insert($create_array);
        return response()->json("saved", 200);
    }


    function getTeacherList(Request $req)
    {

        $teachers = User::where("employee_post", "استاد")->where("employee_status", "On")->get();
        return response()->json($teachers, 200);
    }


    function getClassList(Request $req)
    {

        $classes = classes::all();
        return response()->json($classes, 200);
    }


    function getListofBooks(Request $req)
    {
        $books = Book::all();
        return response()->json($books, 200);
    }


    function connectTeacherClassBooks()
    {
        return view("academic.connect-teacher-class-book");
    }

    function getBook(Request $req)
    {

        $book = Book::find($req->id);

        return response()->json($book, 200);
    }

    function getBookList(Request $req)
    {


        if ($req->search_value) {

            $search = $req->search_value;

            $total_count = Book::where("head", "like", '%' . $search . '%')->count();

            $data = Book::where("head", "like", '%' . $search . '%')
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = Book::count();

            $data = Book::offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('book', function ($row) {
                return $row->book;
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_book"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function insertBooks(Request $req)
    {

        if ($req->voucher_hidden_id) {
            $validation["book"] = [
                'required',
                Rule::unique('books')->ignore($req->voucher_hidden_id)
            ];
        } else {
            $validation["book"] = [
                'required',
                Rule::unique('books')
            ];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->voucher_hidden_id) {
            $head =  Book::find($req->voucher_hidden_id);
        } else {
            $head = new Book();
        }

        $head->book = $req->book;
        $head->save();
        return response()->json("saved", 200);
    }

    function addBookForm()
    {

        return view("exam.add-book-form");
    }

    function promoteAdmissions()
    {

        $sections = sections();
        $classes = classes::all();
        return view("academic.promote-admissions", compact("sections", "classes"));
    }


    function promoteAdmissionsStudentList(Request $req)
    {


        if ($req->class_id && $req->section && $req->session_id) {

            $next_admission_year_to_promoted = session::where('id', '>', $req->session_id)
                ->orderBy('id', 'asc')
                ->first();

            $total_count = Admission::where("class_id", $req->class_id)
                ->where("section", $req->section)
                // ->where("admission_year", $req->session_id)
                ->where("admission_year", "!=",  $next_admission_year_to_promoted->id)
                ->count();

            $data = Admission::where("class_id", $req->class_id)
                ->where("section", $req->section)
                // ->where("admission_year", $req->session_id)
                ->where("admission_year", "!=",  $next_admission_year_to_promoted->id)
                ->orderBy("id", "desc");
        } elseif ($req->class_id && $req->session_id) {


            $next_admission_year_to_promoted = session::where('id', '>', $req->session_id)
                ->orderBy('id', 'asc')
                ->first();

            if ($next_admission_year_to_promoted !== null) {

                $current_session_student =  Admission::where("previous_class_id", $req->class_id)
                    ->where("admission_year", $next_admission_year_to_promoted->id)
                    ->orderBy("id", "desc")
                    ->pluck('promote_student_id')
                    ->toArray();

                //previous_session_student
                $data =  Admission::where("class_id", $req->class_id)
                    ->where("admission_year", $req->session_id)
                    ->whereNotIn("id", $current_session_student)
                    ->orderBy("id", "desc");

                $total_count = $data->count();
            } else {
                $total_count = Admission::where("id", 0)->count();
                $data = Admission::where("id", 0);
            }
        } else {
            $total_count = Admission::where("id", 0)->count();
            $data = Admission::where("id", 0);
        }


        return DataTables::of($data)
            ->addColumn('check_box', function ($row) {
                return '<div class="checkbox-wrapper-13">
                <input class="promoted_student" type="checkbox" checked value="' . $row->id . '" name="promotedStudent[]">
             
              </div>';
            })
            ->addColumn('register_no', function ($row) {
                return $row->register_no;
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('father_name', function ($row) {
                return $row->father_name;
            })
            ->addColumn('department', function ($row) {
                return $row->getClass->getDepartments->department;
            })
            ->addColumn('class', function ($row) {
                return $row->getClass->class;
            })
            ->addColumn('section', function ($row) {
                return $row->section;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_admission"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action', 'check_box'])
            ->make(true);
    }


    function promoteAdmissionsStudentListPromoted(Request $req)
    {



        $next_admission_year_to_promoted = session::where('id', '>', $req->session_id)
            ->orderBy('id', 'asc')
            ->first();


        $promoted_student_ids = $req->promoted_student;

        //get data of promoted student ids
        $all_promoted_student_data = Admission::whereIn('id', $promoted_student_ids)->get()->toArray();

        $id =  Admission::latest('id')->first()->id;
        $id = $id + 1;
        $formattedTimestamp = Carbon::now();
        foreach ($all_promoted_student_data as &$admission) {


            // Check if 'session' key exists in the admission record and update its value
            if (isset($admission['admission_year'])) {
                // Update the 'session' value to your desired new value
                $admission['promote_student_id'] = $admission["id"];
                $admission['id'] = $id++;
                $admission['admission_year'] = $next_admission_year_to_promoted->id;
                $admission['class_id'] = $req->class_id;
                $admission['section'] = $req->section;
                $admission['previous_class_id'] = $req->previous_class_id;
                $admission['created_at'] = $formattedTimestamp;
                $admission['updated_at'] = $formattedTimestamp;
            }
        }


        //promote student id get from search


        Admission::insert($all_promoted_student_data);
        return response()->json("saved", 200);
    }

    function deleteMultipleVouchers(Request $req)
    {


        Voucher::where('for_the_month', $req->for_the_month_search)
            ->where('class_id', $req->class_id_search)
            ->where('session_id', $req->session_id)
            ->where('status', 0)
            ->delete();
    }

    function reverseFeeVoucher(Request $req)
    {

        $voucher = voucher::find($req->id);
        $voucher->status = 0;
        $voucher->recieved_amount = 0;
        $voucher->save();


        Voucher::where('arrears_cleared_voucher_id', $req->id)
            ->update(['status' => 0, "arrears_cleared_voucher_id" => null]);

        return response()->json("updated", 200);
    }

    function test()
    {

        // return  QrCode::size(100)->generate('irfan');

        return view("vouchers.test");
    }

    function idCardsStudentList(Request $req)
    {


        $classes = classes::with("getDepartments")->get();
        $sections = sections();

        return view("academic.id-card-students-list", compact("classes", "sections"));
    }



    function idCardsStudentListData(Request $req)
    {

        if ($req->class_id && $req->section && $req->session_id) {

            $total_count = Admission::where("class_id", $req->class_id)
                ->where("section", $req->section)
                ->where("admission_year", $req->session_id)
                ->count();

            $data = Admission::where("class_id", $req->class_id)
                ->where("section", $req->section)
                ->where("admission_year", $req->session_id)
                ->orderBy("id", "desc");
        } elseif ($req->class_id && $req->session_id) {

            $total_count = Admission::where("class_id", $req->class_id)
                ->where("section", $req->section)
                ->where("admission_year", $req->session_id)
                ->count();

            $data = Admission::where("class_id", $req->class_id)
                ->where("section", $req->section)
                ->where("admission_year", $req->session_id)
                ->orderBy("id", "desc");
        } else {
            $total_count = Admission::where("id", 0)->count();
            $data = Admission::where("id", 0);
        }


        return DataTables::of($data)
            ->addColumn('check_box', function ($row) {
                return '<div class="checkbox-wrapper-13">
                <input id="c1-13" type="checkbox" value="' . $row->id . '">
             
              </div>';
            })
            ->addColumn('register_no', function ($row) {
                return $row->register_no;
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('father_name', function ($row) {
                return $row->father_name;
            })
            ->addColumn('class', function ($row) {
                return $row->getClass->class;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_admission"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action', 'check_box'])
            ->make(true);
    }




    function idCards(Request $req)
    {

        if (count($req->checkboxValues) <= 0) {
            if ($req->section !== "") {
                $admissions = Admission::with("getClass")->where("class_id", $req->class_id)->where("section", $req->section)->where("admission_year", $req->session_id)->get()->toArray();
            } else {
                $admissions = Admission::with("getClass")->where("class_id", $req->class_id)->where("admission_year", $req->session_id)->get()->toArray();
            }
        } else {
            $admissions = Admission::with("getClass")->whereIn('id', $req->checkboxValues)->get()->toArray();
        }

        return view("academic.id-cards", compact("admissions"));
    }

    function feeVoucherPrint(Request $req)
    {


        $attachHead = VoucherHead::get()->toArray();

        $head_detail_array = json_decode(json_encode($attachHead), true);

        if (count($req->data) <= 2) {
            $fee_vouchers = Voucher::with("admissions")->with("admissions.getClass")->where("id", $req->data[1])->get()->toArray();
        } else {
            $fee_vouchers = Voucher::with("admissions")->with("admissions.getClass")->where($req->data)->get()->toArray();
        }

        return view("vouchers.fee-voucher-print", compact("head_detail_array", "fee_vouchers"));
    }




    function getHeadsList(Request $req)
    {
        $for_the_month = $req->for_the_month;
        $class_id =  $req->class_id;
        $section =  $req->section;
        $session_id =  $req->session;


        $attach_heads = AttachHead::with(['getHead' => function ($query) {
            $query->orderBy('id', 'desc');  // Change 'asc' to 'desc' for descending order
        }])
            ->where('class_id', $class_id)
            ->where('session_id', $session_id)
            ->get();


        $count_voucher = Voucher::where("for_the_month", $for_the_month)->where("class_id", $class_id)->where("section", $section)->where("session_id", $session_id)->count();
        $count_admission = Admission::where("class_id", $class_id)->where("section", $section)->where("admission_year", $session_id)->count();

        $check_latest_voucher = Voucher::latest()->first();


        if ($count_voucher > 0 && $count_voucher == $count_admission) {
            return response()->json("overall_created");
        }

        if ($check_latest_voucher) {
            if ($for_the_month <= $check_latest_voucher->for_the_month) {
                //becuase if voucher created in next month, then previous month should not be edited or deleted or created.
                return response()->json("invalid");
            }
        }

        return response()->json($attach_heads);
    }

    function admissionForm()
    {

        $classes = classes::all();
        $sessions = session::all();
        $sections = sections();
        return view("academic.admission-form", compact("classes", "sessions", "sections"));
    }

    function insertAdmission(Request $req)
    {

        if (isset($req->admission_hidden_id)) {
            $admission =  Admission::find($req->admission_hidden_id);
        } else {
            $admission = new Admission();
            $get_student =  Admission::latest()->first();
            if ($get_student == "") {
                $admission->roll_no = 10000;
            } else {
                $admission->roll_no = $get_student->roll_no + 1;
            }
        }

        $admission->admission_year = $req->session_id;
        $admission->register_no = $req->register_no;
        $admission->admission_date = $req->admission_date;
        $admission->class_id = $req->class;
        $admission->section = $req->section;
        $admission->shift = $req->shift;
        $admission->category = $req->category;
        $admission->name = $req->fname;
        $admission->father_name = $req->father_name;
        $admission->dob = $req->dob;
        $admission->mobile_no = $req->mobile_no;
        $admission->address = $req->address;
        $admission->guardian = $req->guardian;
        $admission->guardian_relation = $req->guardian_relation;
        $admission->father_cnic = $req->father_cnic;
        $admission->father_occupation = $req->father_occupation;
        $admission->phone_no = $req->phone_no;
        $admission->previous_madrissa = $req->previous_madrissa;
        $admission->previous_madrissa_education = $req->previous_madrissa_education;
        $admission->previous_school = $req->previous_school;
        $admission->previous_school_education = $req->previous_school_education;

        if (isset($req->image)) {
            $imageName = time() . '.' . $req->image->extension();
            $req->image->move(public_path('images'), $imageName);
            $admission->image = $imageName;
        }
        $admission->status = $req->status;
        $admission->save();
        return response()->json("saved", 200);
    }

    function getListOfAdmissions(Request $req)
    {

        if ($req->search_value) {

            $total_count = Admission::where("name", "like", '%' . $req->search_value . '%')
                ->where("admission_year", $req->session)
                ->count();

            $data = Admission::with("getClass.getDepartments")->where("name", "like", '%' . $req->search_value . '%')
                ->where("admission_year", $req->session)
                ->offset($req->start)
                ->limit(2)
                ->orderBy("id", "desc");
        } else {

            $total_count = Admission::where("admission_year", $req->session)->count();

            $data = Admission::with("getClass.getDepartments")
                ->where("admission_year", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('roll_no', function ($row) {
                return $row->register_no;
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('father_name', function ($row) {
                return $row->father_name;
            })
            ->addColumn('class', function ($row) {
                return " (" . $row->getClass->getDepartments->department . ")" . $row->getClass->class;
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_admission"  data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item view_admission"  data-id="' . $row->id . '">View</a>
                        <a href="javascript:void(0)" class="dropdown-item print_admission"  data-id="' . $row->id . '">Print</a>
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }


    function studentListAttendence(Request $req)
    {


        if ($req->ajax()) {

            $date = $req->date;
            $class = $req->class;
            $session = $req->session;

            $total_count = Admission::where("class_id", $class)
                ->where("admission_year", $session)
                ->where("status", "On")->count();

            $data = Admission::where("class_id", $class)
                ->where("admission_year", $session)
                ->where("status", 1)
                ->with(['attendance' => function ($query) use ($date) {
                    $query->where('date', $date);
                }])
                ->get();

            $increment = 1; // Initialize the increment variable

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('roll_no', function ($row) {
                    return $row->roll_no;
                })

                ->addColumn('student_name', function ($row) {
                    return $row->name;
                })


                ->addColumn('attendance_type', function ($row) use (&$increment) {
                    $class = 'attendence_type' . $increment;
                    $increment++; // Increment the variable for the next row

                    if (count($row->attendance) <= 0) {
                        return '<div>
                        حاضر<input type="radio" name="' . $class . '" checked value="present" data-id="' . $row->id . '" class="attendence_type form_control">
                        <input type="radio" name="' . $class . '" value="absent" data-id="' . $row->id . '" class="attendence_type form_control mr-5">غیر حاضر
                        <input type="radio" name="' . $class . '" value="holiday" data-id="' . $row->id . '" class="attendence_type form_control mr-5">چھٹی
                        <input type="radio" name="' . $class . '" value="leave" data-id="' . $row->id . '" class="attendence_type form_control mr-5">درخواست</div>';
                    } else {

                        //In this code we provide attendance id because this id is used for update
                        return '<div>
                        حاضر<input type="radio" name="' . $class . '"  value="present" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control" ' . ($row->attendance[0]->attendance_type == "present" ? "checked" : "") . '>
                        <input type="radio" name="' . $class . '" value="absent" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5" ' . ($row->attendance[0]->attendance_type == "absent" ? "checked" : "") . '>غیر حاضر
                        <input type="radio" name="' . $class . '" value="leave" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5"' . ($row->attendance[0]->attendance_type == "holiday" ? "checked" : "") . '>چھٹی
                        <input type="radio" name="' . $class . '" value="leave" data-update="' . $row->attendance[0]->id . '" class="attendence_type form_control mr-5"' . ($row->attendance[0]->attendance_type == "leave" ? "checked" : "") . '>درخواست</div>';
                    }
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['attendance_type', 'check_box'])
                ->make(true);
        }
    }


    function studentAttendenceInsert(Request $req)
    {

        if ($req->ajax()) {


            if ($req->qr_attendance) {

                $student_id = $req->insert_attendance[0]["student_id"];
                $date = $req->insert_attendance[0]["date"];

                $check_already_exist = StudentAttendance::where("student_id", $student_id)->where("date", $date)->first();

                if (!$check_already_exist) {
                    StudentAttendance::insert($req->insert_attendance);
                    return response()->json("success", 200);
                } else {
                    return response()->json([$check_already_exist], 400);
                }
            }


            if (count($req->insert_attendance) > 0) {
                StudentAttendance::insert($req->insert_attendance);
            }

            if (count($req->update_attendance) > 0) {

                $update_array = $req->update_attendance;

                $updateQuery = 'UPDATE student_attendances SET attendance_type = CASE ';
                foreach ($update_array as $student_data) {


                    $updateQuery .= 'WHEN id = ' . $student_data["student_id"] . ' THEN ' . ' "' . $student_data["attendance_type"] . '" ';
                }
                $updateQuery .= 'ELSE attendance_type END';

                // Execute the update query
                DB::statement($updateQuery);
            }
        }
    }


    function studentAttendenceView()
    {

        return view("academic.student-attendance");
    }


    function viewAdmission(Request $req, $student_id)
    {

        if ($req->ajax()) {
            $admission = Admission::with("getClass")->with("getClass.getDepartments")
                ->where("id", $student_id)
                ->get();
            $html = [];
            $html["title"] = "داخلہ کی تفصیل <a class='btn ml-2 btn-sm btn-danger .hide-on-print' id='print-admission' data-id=" . $admission[0]->id . ">پرنٹ</a>";
            $html["view"] =  view("academic.view-admission", compact("admission"))->render();
            return response()->json($html, 200);
        }
    }


    function printAdmission(Request $req, $student_id)
    {

        $admission = Admission::with("getClass")->with("getClass.getDepartments")
            ->where("id", $student_id)
            ->get();
        return view("academic.view-admission", compact("admission"));
    }



    function editAdmission(Request $req)
    {

        $admission = Admission::with("getClass.getDepartments")->where("id", $req->id)->first();

        return response()->json($admission, 200);
    }

    function getAdmissionYear()
    {

        $admission_year = session::all();
        return response()->json($admission_year, 200);
    }


    function fineForm(Request $req)
    {
        $classes = classes::all();
        $departments = Department::all();
        $sections = sections();
        return view("vouchers.fine-form", compact("sections"));
    }

    function getStudentClassWise(Request $req)
    {

        $students = Admission::where("class_id", $req->class_id)->where("section", $req->section)->where("admission_year", $req->session)->where("status", 1)->get();
        return response()->json($students, 200);
    }

    function getStudentClassWiseSectionwise(Request $req)
    {

        $students = Admission::where("class_id", $req->class_id)->where("section", $req->section)->where("admission_year", $req->session_id)->where("status", 1)->get();
        return response()->json($students, 200);
    }


    function insertFine(Request $req)
    {

        if ($req->fine_hidden_id) {
            $fine = Fine::find($req->fine_hidden_id);
        } else {
            $fine = new Fine();
        }
        $fine->class_id = $req->class_id;
        $fine->student_id = $req->student_id;
        $fine->fine_amount = $req->fine_amount;
        $fine->for_the_month = $req->month;
        $fine->remarks = $req->remarks;
        $fine->session_id = $req->session_id;
        $fine->save();
        return response()->json("saved", 200);
    }

    function getListOfFine(Request $req)
    {

        if ($req->search_value) {

            $search = $req->search_value;
            $total_count = Fine::where("session_id", $req->session)
                ->whereHas('admission', function ($query) use ($search) {
                    $query->where("name", "like", '%' . $search . '%');
                })
                ->count();

            $data = Fine::with("class:id,class")->with("admission:id,name")
                ->where("session_id", $req->session)
                ->whereHas('admission', function ($query) use ($search) {
                    $query->where("name", "like", '%' . $search . '%');
                })
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = Fine::where("session_id", $req->session)->count();

            $data = Fine::with("class:id,class")->with("admission:id,name")
                ->where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->admission->name;
            })
            ->addColumn('class', function ($row) {
                return $row->class->class;
            })
            ->addColumn('amount', function ($row) {
                return $row->fine_amount;
            })
            ->addColumn('month', function ($row) {
                return $row->for_the_month;
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_fine"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function editFine(Request $req)
    {

        $fine_student = Fine::with("class")->where("id", $req->id)->first();
        return response()->json($fine_student, 200);
    }


    function voucherHeadAttachForm(Request $req)
    {

        $classes = classes::with("getDepartments")->get();
        $heads = voucherHead::all();
        return view("vouchers.voucher-head-attach-form", compact("classes", "heads"));
    }


    function insertHeadAttachAmount(Request $req)
    {

        $session_id = $req->session_id;
        $class_id = $req->class_id;

        if ($req->voucher_head_attach_id) {

            $validation["head_id"] = [
                'required',
                Rule::unique('attach_heads')->where(function ($query) use ($session_id, $class_id) {
                    return $query->where("session_id", $session_id)->where("class_id", $class_id);
                })->ignore($req->voucher_head_attach_id)
            ];
        } else {

            $validation["head_id"] = [
                'required',
                Rule::unique('attach_heads')->where(function ($query) use ($session_id, $class_id) {
                    return $query->where("session_id", $session_id)->where("class_id", $class_id);
                })
            ];
        }


        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->voucher_head_attach_id) {
            $head =  AttachHead::find($req->voucher_head_attach_id);
        } else {
            $head = new AttachHead();
        }

        $head->class_id = $req->class_id;
        $head->head_id = $req->head_id;
        $head->amount = $req->amount;
        $head->session_id = $req->session_id;
        $head->save();
        return response()->json("saved", 200);
    }



    function attachVoucherHeadList(Request $req)
    {


        if ($req->search_value && $req->class_id_search) {

            $search = $req->search_value;

            $total_count = AttachHead::where("session_id", $req->session)
                ->whereHas('getHead', function ($query) use ($search) {
                    $query->where("head", "like", '%' . $search . '%');
                })->where("class_id", $req->class_id_search)->count();

            $data = AttachHead::with("getClass:id,class")->with("getHead:id,head")
                ->whereHas('getHead', function ($query) use ($search) {
                    $query->where("head", "like", '%' . $search . '%');
                })
                ->where("class_id", $req->class_id_search)
                ->where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->class_id_search) {

            $total_count = AttachHead::where("session_id", $req->session)
                ->where("class_id", $req->class_id_search)
                ->count();


            $data = AttachHead::with("getClass:id,class")->with("getHead:id,head")
                ->where("class_id", $req->class_id_search)
                ->where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->search_value) {

            $search = $req->search_value;

            $total_count = AttachHead::where("session_id", $req->session)
                ->whereHas('getHead', function ($query) use ($search) {
                    $query->where("head", "like", '%' . $search . '%');
                })->count();

            $data = AttachHead::with("getClass:id,class")->with("getHead:id,head")
                ->whereHas('getHead', function ($query) use ($search) {
                    $query->where("head", "like", '%' . $search . '%');
                })
                ->where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = AttachHead::where("session_id", $req->session)->count();
            $data = AttachHead::with("getClass:id,class")->with("getHead:id,head")
                ->where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('class', function ($row) {
                return $row->getClass->class;
            })
            ->addColumn('head', function ($row) {
                return $row->getHead->head;
            })
            ->addColumn('amount', function ($row) {
                return $row->amount;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_voucher_head_attach"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }



    function editAttachVoucherHead(Request $req)
    {
        $attach_voucher_head = AttachHead::with("getHead:id,head")->where("id", $req->id)->first();
        return response()->json($attach_voucher_head, 200);
    }





    function voucherHead(Request $req)
    {

        $classes = classes::all();
        return view("vouchers.voucher-head", compact("classes"));
    }


    function insertHead(Request $req)
    {

        if ($req->voucher_hidden_id) {
            $validation["head"] = [
                'required',
                Rule::unique('voucher_heads')->ignore($req->voucher_hidden_id)
            ];
        } else {
            $validation["head"] = [
                'required',
                Rule::unique('voucher_heads')
            ];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->voucher_hidden_id) {
            $head =  VoucherHead::find($req->voucher_hidden_id);
        } else {
            $head = new VoucherHead();
        }
        $head->head = $req->head;
        $head->session_id = $req->session_id;
        $head->save();
        return response()->json("saved", 200);
    }


    function getVoucherHeadList(Request $req)
    {


        if ($req->search_value) {

            $search = $req->search_value;

            $total_count = voucherHead::where("head", "like", '%' . $search . '%')->where("session_id", $req->session)->count();

            $data = voucherHead::where("head", "like", '%' . $search . '%')
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = voucherHead::where("session_id", $req->session)->count();

            $data = voucherHead::where("session_id", $req->session)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('head', function ($row) {
                return $row->head;
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_voucher_head"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function editVoucherHead(Request $req)
    {

        $admission = voucherHead::find($req->id);
        return response()->json($admission, 200);
    }

    function feeVoucher(Request $req)
    {

        $sections = sections();
        $heads = VoucherHead::all();
        $classes = classes::with("getDepartments")->get();
        return view("vouchers.fee-voucher", compact("heads", "sections", "classes"));
    }

    function getFeeVoucherClassList(Request $req)
    {


        $total_count = Admission::where("admission_year", $req->session)->where("id", 0)->count();
        $data = Admission::where("admission_year", $req->session)
            ->where("id", 0)
            ->offset($req->start)
            ->limit(10)
            ->orderBy("id", "desc");



        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('father_name', function ($row) {
                return $row->name;
            })
            ->addColumn('age', function ($row) {
                return $row->name;
            })
            ->addColumn('class', function ($row) {
                return $row->name;
            })
            ->addColumn('section', function ($row) {
                return $row->name;
            })
            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item edit_voucher_head"  data-id="' . $row->id . '">Edit</a>
                      
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }

    function deleteVoucher(Request $req)
    {

        $voucher = Voucher::find($req->id);
        $voucher->delete();
        return response()->json("deleted", 200);
    }



    function insertFeeVouchers(Request $req)
    {



        $timestamp = Carbon::now();


        $lastRecord = Voucher::offset(0)->limit(1)->orderBy("id", "desc")->get();

        if (count($lastRecord) > 0) {
            $voucher_no = $lastRecord[0]->voucher_no;
        } else {
            $voucher_no = 1000;
        }


        //this code is for single voucher
        if ($req->single_voucher == "voucher_checked") {
            // $fine  = fine::where('id', $req->student_id)->where("session_id", $req->session_id)->sum('fine_amount');

            if ($req->voucher_hidden_id) {

                $voucher_hidden_id = $req->voucher_hidden_id;
                $session_id = $req->session_id;
                $for_the_month = $req->for_the_month;
                $student_id = $req->student_id;

                $validation["student_id"] = ['required', Rule::unique('vouchers')
                    ->where(function ($query) use ($session_id, $for_the_month, $student_id) {
                        return $query->where("student_id", $student_id)
                            ->where("for_the_month", $for_the_month)
                            ->where("session_id", $session_id);
                    })->ignore($voucher_hidden_id)];

                $validator = Validator::make($req->all(), $validation);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->all()], 400);
                }

                $voucher =  Voucher::find($req->voucher_hidden_id);
            } else {
                $voucher = new Voucher();
            }
            $voucher->voucher_no = $voucher_no;
            $voucher->class_id = $req->class_id;
            $voucher->section = $req->section;
            $voucher->student_id = $req->student_id;
            $voucher->voucher_heads = json_encode($req->heads_detail);
            $voucher->for_the_month = $req->for_the_month;
            $voucher->last_date = $req->last_date;
            $voucher->fine =  $req->fine;
            $voucher->arrears =  $req->arrears;
            $voucher->before_due_date =  $req->head_amount_total +  $req->fine;
            $voucher->after_due_date =  $req->head_amount_total +  $req->fine + 150;
            if (!$req->voucher_hidden_id) {
                $voucher->voucher_type = "single";
            }
            if ($req->status == 1) {
                $voucher->recieved_amount =  $req->head_amount_total +  $req->fine;
            }

            $voucher->session_id =  $req->session_id;
            $voucher->status = $req->status;
            $voucher->save();

            if ($req->voucher_hidden_id) {
                return response()->json("saved");
            } else {
                return response()->json(["single", $voucher->id], 200);
            }
        }


        //this code is for multiple voucher

        $voucher_array = [
            "status" => $req->status, "last_date" => $req->last_date, "for_the_month" => $req->for_the_month, "section" => $req->section, "class_id" => $req->class_id, "voucher_heads" => json_encode($req->heads_detail), "session_id" => $req->session_id, 'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];


        //test query

        $current_month = $req->for_the_month;
        //$previous_month =  date('Y-m', strtotime('first day of last month', strtotime($current_month)));


        //$arrearsArray = Voucher::where("class_id", $req->class_id)->where('session_id', $req->session_id)->where("status",0)->get(["student_id","after_due_date"])->toArray();





        $class_id = $req->class_id;
        $session_id = $req->session_id;
        $for_the_month = $req->for_the_month;

        $previous_month = date("Y-m", strtotime("-1 month", strtotime($req->for_the_month)));

        $first_day_of_month = date("Y-m-01", strtotime($previous_month));
        $last_day_of_month = date("Y-m-t", strtotime($previous_month));



        $vouchers = Admission::whereDoesntHave('vouchers', function ($query) use ($class_id, $session_id, $for_the_month) {
            $query->where('class_id', $class_id)->where('session_id', $session_id)->where('for_the_month', $for_the_month);;
        })->with(['fine' => function ($query) use ($first_day_of_month, $last_day_of_month) {
            $query->whereDate("created_at", ">=", $first_day_of_month)->whereDate("created_at", "<=", $last_day_of_month);
        }])
            ->where('class_id', $class_id)
            ->where('admission_year', $session_id)
            ->where('status', 1)
            ->get("id")
            ->toArray();



        $mergedArray = [];

        $nestedArray = array_map(function ($element) {
            return [$element];
        }, $vouchers);




        //yahan fine ka directly sum lay liya hai array column ki madad sy 
        $updatedArrays = array_map(function ($item) use (&$voucher_no, &$req) {
            $voucher_no = $voucher_no + 1;
            return [
                "student_id" => $item[0]["id"],
                "fine" => array_sum(array_column($item[0]["fine"], 'fine_amount')),
                //change value of after due date (150)
                "after_due_date" => $req->head_amount_total + array_sum(array_column($item[0]["fine"], 'fine_amount')) + 150,
                "before_due_date" => $req->head_amount_total + array_sum(array_column($item[0]["fine"], 'fine_amount')),
                "voucher_no" => $voucher_no,
                "voucher_type" => "multiple"
            ];
        }, $nestedArray);



        //merge old remaining amount (Arrears)
        $arrearsArray = Voucher::where("class_id", $req->class_id)->where('session_id', $req->session_id)->where("status", 0)
            ->select([DB::raw("student_id, SUM(after_due_date) as arrear")])
            ->groupBy('student_id')
            ->get()->toArray();


        $mergedArrayArrears = [];

        foreach ($updatedArrays as $additionalInfo) {
            $student_id = $additionalInfo['student_id'];


            // Find the corresponding arrears information based on student_id
            $arrearsInfo = collect($arrearsArray)->firstWhere('student_id', $student_id);

            // Merge arrears with additional info
            if ($arrearsInfo) {
                $mergedArrayArrears[] = array_merge($additionalInfo, ['arrears' => $arrearsInfo['arrear'], 'before_due_date' => $additionalInfo['before_due_date'] + $arrearsInfo['arrear'], 'after_due_date' => $additionalInfo['after_due_date'] + $arrearsInfo['arrear']]);
            } else {
                $mergedArrayArrears[] = array_merge($additionalInfo, ['arrears' => 0]);
            }
        }



        foreach ($mergedArrayArrears as $student) {
            $mergedArray[] = array_merge($student, $voucher_array);
        }


        Voucher::insert($mergedArray);

        // because i dont want print when created i only want print when i want
        return response()->json("created_multiple");
    }


    function getUnrecievedVoucher(Request $req)
    {


        if ($req->ajax()) {

            $latest_voucher_month = voucher::latest()->first();

            if ($req->search_value) {

                $search = $req->search_value;
                $class_id = $req->class_id;
                $session_id = $req->session;
                $for_the_month = $req->for_the_month;
                $total_count = Voucher::whereHas('singleAdmission', function ($query) use ($search, $class_id, $session_id) {
                    $query->where("name", "like", '%' . $search . '%')
                        ->where("class_id", $class_id)
                        ->where("session_id", $session_id);
                })->where("class_id", $req->class_id)
                    ->where("session_id", $req->session)
                    ->where("status", 0)
                    ->where("for_the_month", $for_the_month)
                    ->count();

                $data = Voucher::whereHas('singleAdmission', function ($query) use ($search, $class_id, $session_id) {
                    $query->where("name", "like", '%' . $search . '%')
                        ->where("class_id", $class_id)
                        ->where("session_id", $session_id);
                })->where("class_id", $req->class_id)
                    ->where("session_id", $req->session)
                    ->where("for_the_month", $req->for_the_month)
                    ->where("status", 0)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->section && $req->class_id && $req->session && $req->for_the_month) {

                $total_count = Voucher::where("class_id", $req->class_id)
                    ->where("section", $req->section)
                    ->where("session_id", $req->session)
                    ->where("for_the_month", $req->for_the_month)
                    ->where("status", 0)
                    ->count();

                $data = Voucher::with("singleAdmission:id,name,father_name,dob")->with("classes:id,class")->where("class_id", $req->class_id)
                    ->where("section", $req->section)
                    ->where("session_id", $req->session)
                    ->where("for_the_month", $req->for_the_month)
                    ->where("status", 0)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } elseif ($req->class_id && $req->session && $req->for_the_month) {

                $total_count = Voucher::where("class_id", $req->class_id)
                    ->where("session_id", $req->session)
                    ->where("for_the_month", $req->for_the_month)
                    ->where("status", 0)
                    ->count();

                $data = Voucher::with("singleAdmission:id,name,father_name,dob")->with("classes:id,class")->where("class_id", $req->class_id)
                    ->where("session_id", $req->session)
                    ->where("for_the_month", $req->for_the_month)
                    ->where("status", 0)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } else {

                $total_count = Voucher::where("id", 0)->count();
                $data = Voucher::where("id", 0);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->singleAdmission->name;
                })
                ->addColumn('father_name', function ($row) {
                    return $row->singleAdmission->father_name;
                })
                ->addColumn('age', function ($row) {
                    return $row->singleAdmission->dob;
                })
                ->addColumn('class', function ($row) {
                    return $row->singleAdmission->section !== "" ?  $row->classes->class : "(" . $row->singleAdmission->section . ") " . $row->classes->class;
                })

                ->addColumn('action', function ($row) use ($latest_voucher_month) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                    if ($row->for_the_month >= $latest_voucher_month->for_the_month) {
                        $btn .= '<a href="javascript:void(0)" class="dropdown-item edit_voucher text-dark"  data-id="' . $row->id . '">Edit</a>
                            <a href="javascript:void(0)" class="dropdown-item delete_voucher text-dark"  data-id="' . $row->id . '">Delete</a>';
                    }
                    $btn .= '<a href="javascript:void(0)" class="dropdown-item print_voucher text-dark"  data-id="' . $row->id . '">Print</a>
                        </div>
                        </div>';
                    // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }





    function getRecieveVoucherList(Request $req)
    {



        if ($req->section && $req->class_id && $req->session && $req->for_the_month) {

            $total_count = Voucher::where("class_id", $req->class_id)
                ->where("section", $req->section)
                ->where("session_id", $req->session)
                ->where("for_the_month", $req->for_the_month)
                ->where("status", 1)
                ->count();

            $data = Voucher::with("singleAdmission:id,name,father_name,dob")->with("classes:id,class")->where("class_id", $req->class_id)
                ->where("section", $req->section)
                ->where("session_id", $req->session)
                ->where("for_the_month", $req->for_the_month)
                ->where("status", 1)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->class_id && $req->session && $req->for_the_month) {

            $total_count = Voucher::where("class_id", $req->class_id)
                ->where("session_id", $req->session)
                ->where("for_the_month", $req->for_the_month)
                ->where("status", 1)
                ->count();

            $data = Voucher::with("singleAdmission:id,name,father_name,dob")->with("classes:id,class")->where("class_id", $req->class_id)
                ->where("session_id", $req->session)
                ->where("for_the_month", $req->for_the_month)
                ->where("status", 1)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {


            $total_count = Voucher::where("session_id", $req->session)
                ->where("status", 1)->count();

            $data = Voucher::where("session_id", $req->session)
                ->where("status", 1)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->singleAdmission->name;
            })
            ->addColumn('father_name', function ($row) {
                return $row->singleAdmission->father_name;
            })
            ->addColumn('age', function ($row) {
                return $row->singleAdmission->dob;
            })
            ->addColumn('class', function ($row) {
                return $row->singleAdmission->section !== "" ?  $row->classes->class : "(" . $row->singleAdmission->section . ") " . $row->classes->class;
            })

            ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item unrecieved_voucher"  data-id="' . $row->id . '">Unrecieved</a>
                        </div>
                        </div>';
                // <a  href="javascript:void(0)" class="dropdown-item delete_vendor_name" data-id="' . $row->id . '">Delete</a>
                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }


    function printVoucherSingle(Request $req, $id)
    {

        $attachHead = VoucherHead::get()->toArray();

        $head_detail_array = json_decode(json_encode($attachHead), true);

        $fee_vouchers = Voucher::with("admissions")->with("admissions.getClass")->where("id", $id)->get()->toArray();

        return view("vouchers.fee-voucher-print", compact("head_detail_array", "fee_vouchers"));
    }


    function printVoucherMultiple(Request $req)
    {

        $attachHead = VoucherHead::get()->toArray();

        $head_detail_array = json_decode(json_encode($attachHead), true);

        $fee_vouchers = Voucher::with("admissions")->with("admissions.getClass")
            ->where("class_id", $req->class_id)
            ->where("for_the_month", $req->for_the_month)
            ->where("session_id", $req->session_id)
            ->get()->toArray();

        return view("vouchers.fee-voucher-print", compact("head_detail_array", "fee_vouchers"));
    }


    function getStudentList(Request $req)
    {


        // $voucher = Voucher::with("admissions")->get();

        $class_id = $req->class_id;
        $session_id = $req->session;
        $for_the_month = $req->for_the_month;

        $vouchers = Admission::with('vouchers')->whereDoesntHave('vouchers', function ($query) use ($class_id, $session_id, $for_the_month) {
            $query->where('class_id', $class_id)->where('session_id', $session_id)->where("for_the_month", $for_the_month);
        })->where('class_id', $class_id)->where('admission_year', $session_id)->get();

        return response()->json($vouchers);
    }



    function getStudentListCreateVoucher(Request $req)
    {


        // $voucher = Voucher::with("admissions")->get();

        $class_id = $req->class_id;
        $session_id = $req->session;
        $for_the_month = $req->for_the_month;

        $vouchers = Admission::with('vouchers')->whereHas('vouchers', function ($query) use ($class_id, $session_id, $for_the_month) {
            $query->where('class_id', $class_id)->where('session_id', $session_id)->where("status", 0)->where("for_the_month", $for_the_month);
        })->where('class_id', $class_id)->where('admission_year', $session_id)->get();

        return response()->json($vouchers);
    }


    function getStudentFineAndArrears(Request $req)
    {


        $previous_month = date("Y-m", strtotime("-1 month", strtotime($req->for_the_month)));

        $first_day_of_month = date("Y-m-01", strtotime($previous_month));
        $last_day_of_month = date("Y-m-t", strtotime($previous_month));

        $fine = Fine::where("class_id", $req->class_id)->where("student_id", $req->student_id)->where("session_id", $req->session_id)
            ->where("for_the_month", $req->for_the_month)
            ->sum("fine_amount");

        $arrearsArray = Voucher::where("student_id", $req->student_id)->where("class_id", $req->class_id)->where('session_id', $req->session_id)->where("status", 0)
            ->sum("after_due_date");

        return response()->json([$fine, $arrearsArray]);
    }

    function viewNotRecieveVoucherList(Request $req)
    {

        if ($req->ajax()) {

            $sections = sections();
            $classes = classes::all();
            $html = [];
            $html["title"] = "ناموصول لسٹ";
            $html["view"] =  view("vouchers.view-not-recieved-voucher-list", compact("classes", "sections"))->render();
            return response()->json($html, 200);
        }
    }


    function notRecieveVoucherList(Request $req, $defaulter_month, $session, $class_id = null, $section = null)
    {

        $month = $defaulter_month;
        $session_id = $session;
        if ($req->ajax()) {
            if ($month && $session_id && $class_id) {

                return $vouchers = Voucher::select('*')
                    ->with("classes")
                    ->with("singleAdmission")
                    ->wherehas('getOneVoucher', function ($query) use ($month, $session_id) {
                        $query->where('for_the_month', $month);
                    })->where("class_id", $class_id)
                    ->where("section", $section)
                    ->get()->toArray();
            } elseif ($month) {

                $vouchers = Admission::select('*')
                    ->with("getClass")
                    ->with("vouchers")
                    ->whereDoesntHave('vouchers', function ($query) use ($month, $session_id) {
                        $query->where('for_the_month', $month)
                            ->where('session_id',  $session_id)
                            ->where('status',  0);
                    })->get()->toArray();
            }



            $html = [];
            $html["title"] = $month . "ناموصول لسٹ";
            $html["view"] =  view("vouchers.not-recieved-voucher-list",  compact("vouchers", "month"))->render();
            return response()->json($html, 200);
        }


        // $pdf = PDF::loadView("vouchers.not-recieved-voucher-list",  compact("vouchers"));
        // $file = $pdf->download('nfc_send_stock_report.pdf');
        // return response()->json([base64_encode($file)], 200);


    }


    function recieveFeeVoucher(Request $req)
    {

        $classes = classes::with("getDepartments")->get();
        $heads = VoucherHead::all();
        $bank_name = bankNames();
        return view("vouchers.recieve-fee-voucher", compact("classes", "heads", "bank_name"));
    }


    function viewPrintVoucherMultiple(Request $req)
    {

        $classes = classes::with("getDepartments")->get();
        return view("accounts.view-print-voucher-multiple", compact("classes"));
    }

    function updateRecieveVoucher(Request $req)
    {

        $current_date = date("Y-m-d");
        $timestamp = Carbon::now();

        Voucher::where('student_id', $req->student_id)
            ->where('session_id', $req->session_id)
            ->where('status', 0)
            ->where('for_the_month', $req->for_the_month)
            ->whereDate("created_at", "<=", $req->last_date)
            ->update(['status' => 1, "arrears_cleared_voucher_id" => $req->voucher_hidden_id, "updated_at" => $timestamp, "account_name" => $req->account_name]);

        if ($current_date <= $req->last_date) {

            $voucher = Voucher::with("admissions")->where("id", $req->voucher_hidden_id)
                ->where('for_the_month', $req->for_the_month)
                ->update(['status' => 1, "recieved_amount" => $req->head_amount_total, "updated_at" => $timestamp, "account_name" => $req->account_name]);
        } else {

            $voucher = Voucher::with("admissions")->where("id", $req->voucher_hidden_id)
                ->where('for_the_month', $req->for_the_month)
                ->update(['status' => 1, "recieved_amount" => $req->head_amount_total, "updated_at" => $timestamp, "account_name" => $req->account_name]);
        }

        // $voucher_data = Voucher::with("admissions")
        // ->where("id", $req->voucher_hidden_id)
        // ->first(); // Fetch the voucher
        return response()->json("updated", 200);
    }


    function getStudentVoucherData(Request $req)
    {

        $voucher = Voucher::where("student_id", $req->student_id)->where("class_id", $req->class_id)
            ->where("session_id", $req->session_id)
            ->where("for_the_month", $req->for_the_month)
            ->get();

        return response()->json($voucher);
    }

    function editVoucher(Request $req)
    {

        $voucher = Voucher::with("admissions")->where("id", $req->id)->get()->first();
        return response()->json($voucher, 200);
    }
}
