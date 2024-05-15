<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
    // return view('welcome');

    // return view('home');

// });


 Route::group(array('middleware' => 'auth'), function () {


Route::get('/', [SchoolController::class, 'dashboard']);

Route::get('/total-student', [SchoolController::class, 'totalStudent']);

Route::get('/total-staff', [SchoolController::class, 'totalStaff']);

 Route::get('/admission-form', [SchoolController::class, 'admissionForm']);

 Route::get('/test', [SchoolController::class, 'test']);

 Route::get('/id-cards/student-list', [SchoolController::class, 'idCardsStudentList']);

 Route::get('/id-cards/student-list/data', [SchoolController::class, 'idCardsStudentListData']);

 Route::post('/id-cards', [SchoolController::class, 'idCards']);
 

 Route::post('/insert-admission', [SchoolController::class, 'insertAdmission']);

 Route::get('/admission-report-view', [SchoolController::class, 'admissionReportView']);

 Route::get('/get-admission-report', [SchoolController::class, 'getAdmissionReport']);

 Route::post('/print-admissions', [SchoolController::class, 'printAdmissions']);

 Route::get('/promote-admissions', [SchoolController::class, 'promoteAdmissions']);

 Route::get('/promote-admissions/student-list', [SchoolController::class, 'promoteAdmissionsStudentList']);

 Route::post('/promote-admissions/student-list/promoted', [SchoolController::class, 'promoteAdmissionsStudentListPromoted']);

 Route::post('/get-list-of-admissions', [SchoolController::class, 'getListOfAdmissions']);

 Route::get('/edit-admission', [SchoolController::class, 'editAdmission']);

 Route::get('/view-admission/{student_id}', [SchoolController::class, 'viewAdmission']);

 Route::get('/print-admission/{student_id}', [SchoolController::class, 'printAdmission']);
 
 Route::get('/tuition-fee-form', [SchoolController::class, 'tuitionFeeForm']);

 Route::get('/fine-form', [SchoolController::class, 'fineForm']);

 Route::get('/get-admission-year', [SchoolController::class, 'getAdmissionYear']);
 
 Route::get('/fine-form', [SchoolController::class, 'fineForm']);

 Route::get('/get-student-class-wise', [SchoolController::class, 'getStudentClassWise']);

 Route::get('/get-student-classwise-sectionwise', [SchoolController::class, 'getStudentClassWiseSectionwise']);

 Route::post('/insert-fine', [SchoolController::class, 'insertFine']);

 Route::get('/get-list-of-fine', [SchoolController::class, 'getListOfFine']);

 Route::get('/edit-fine', [SchoolController::class, 'editFine']);

 Route::get('/voucher-head', [SchoolController::class, 'voucherHead']);

 Route::post('/insert-head', [SchoolController::class, 'insertHead']);

 Route::get('/get-voucher-head-list', [SchoolController::class, 'getVoucherHeadList']);

 Route::get('/edit-voucher-head', [SchoolController::class, 'editVoucherHead']);

 Route::get('/voucher-head-attach-form', [SchoolController::class, 'voucherHeadAttachForm']);

 Route::post('/insert-head-attach-amount', [SchoolController::class, 'insertHeadAttachAmount']);

 Route::get('/attach-voucher-head-list', [SchoolController::class, 'attachVoucherHeadList']);

 Route::get('/edit-attach-voucher-head', [SchoolController::class, 'editAttachVoucherHead']);

 Route::get('/fee-voucher', [SchoolController::class, 'feeVoucher']);

 Route::get('/get-fee-voucher-class-list', [SchoolController::class, 'getFeeVoucherClassList']);

 Route::post('/insert-fee-vouchers', [SchoolController::class, 'insertFeeVouchers']);
 
 Route::get('/get-voucher-list', [SchoolController::class, 'getVoucherList']);

 Route::post('/get-voucher-print', [SchoolController::class, 'feeVoucherPrint']);

 Route::get('/get-heads-list', [SchoolController::class, 'getHeadsList']);

 Route::get('/get-student-list', [SchoolController::class, 'getStudentList']);

 Route::get('/get-student-list-create-voucher', [SchoolController::class, 'getStudentListCreateVoucher']);

 Route::get('/get-student-fine-and-arrears', [SchoolController::class, 'getStudentFineAndArrears']);
 
 Route::get('/recieve-fee-voucher', [SchoolController::class, 'recieveFeeVoucher']);

 Route::post('/update-recieve-voucher', [SchoolController::class, 'updateRecieveVoucher']);

 Route::get('/reverse-fee-voucher', [SchoolController::class, 'reverseFeeVoucher']);

 Route::get('/view-not-recieve-voucher-list', [SchoolController::class, 'viewNotRecieveVoucherList']);

 Route::get('/not-recieve-voucher-list/{defaulter_month?}/{session?}/{class_id?}/{section?}', [SchoolController::class, 'notRecieveVoucherList']);

 Route::get('/get-recieve-voucher-list', [SchoolController::class, 'getRecieveVoucherList']);

 Route::get('/get-student-voucher-data', [SchoolController::class, 'getStudentVoucherData']);

 Route::get('/get-unrecieved-voucher', [SchoolController::class, 'getUnrecievedVoucher']);

 Route::get('/edit-voucher', [SchoolController::class, 'editVoucher']);

 Route::get('/delete-voucher', [SchoolController::class, 'deleteVoucher']);

 Route::get('/print-voucher-single/{id}', [SchoolController::class, 'printVoucherSingle']);

 Route::get('/view-print-voucher-multiple', [SchoolController::class, 'viewPrintVoucherMultiple']);

 Route::post('/print-voucher-multiple', [SchoolController::class, 'printVoucherMultiple']);


 Route::post('/delete-multiple-vouchers', [SchoolController::class, 'deleteMultipleVouchers']);

 Route::post('/add-employee-form', [SchoolController::class, 'addEmployeeForm']);

 Route::get('/employee-list-view', [SchoolController::class, 'employeeListView']);

 Route::get('/employee-list', [SchoolController::class, 'employeeList']);

 Route::get('/employee-list-attendence', [SchoolController::class, 'employeeListAttendence']);
 
 Route::get('/employee-attendence-report/{for_the_month}', [SchoolController::class, 'employeeAttendanceReport']);


 Route::get('/employee-attendance-qrcode-view', [SchoolController::class, 'employeeAttendanceQrCodeView']);

 Route::get('/employee-attendance-qrcode-time-to-go-view', [SchoolController::class, 'employeeAttendanceQrCodeTimeToGoView']);


 Route::post('/employee-attendance-qrcode-time-to-go-insert', [SchoolController::class, 'employeeAttendanceQrCodeTimeToGoInsert']);


 Route::get('/employee-idcard-view', [SchoolController::class, 'employeeIdCardView']);


 Route::get('/get-staff-attendence-report', [SchoolController::class, 'getStaffAttendanceReport']);


 Route::get('/student-list-attendence', [SchoolController::class, 'studentListAttendence']);


 Route::get('/student-attendence-view', [SchoolController::class, 'studentAttendenceView']);

 Route::post('/student-attendence-insert', [SchoolController::class, 'studentAttendenceInsert']);


 Route::get('/student-attendence-report-view', [SchoolController::class, 'studentAttendanceReportView']);


 Route::get('/student-attendence-report/{from_date}/{to_date}/{class_id}/{section}', [SchoolController::class, 'studentAttendanceReport']);




 Route::get('/student-attendence-qrcode-view', [SchoolController::class, 'studentAttendanceQrCode']);

 Route::get('/student-attendence-qrcode-go-view', [SchoolController::class, 'studentAttendanceQrCodeGoView']);


 
 Route::post('/time-to-go-student-attendance-update', [SchoolController::class, 'timeToGoStudentAttendanceUpdate']);



 Route::get('/employee-list-attendence-view', [SchoolController::class, 'employeeListAttendenceView']);

 Route::post('/employee-list-attendence-insert', [SchoolController::class, 'employeeListAttendenceInsert']);

 Route::get('/view-employee-profile/{id}', [SchoolController::class, 'viewEmployeeProfile']);

 Route::post('/employee-id-card', [SchoolController::class, 'employeeIdCard']);


 Route::get('/add-book-form', [SchoolController::class, 'addBookForm']);

 Route::post('/insert-books', [SchoolController::class, 'insertBooks']);

 Route::get('/get-book-list', [SchoolController::class, 'getBookList']);

 Route::get('/get-book-for-edit', [SchoolController::class, 'getBook']);

 Route::get('/connect-teacher-class-books', [SchoolController::class, 'connectTeacherClassBooks']);

 Route::get('/get-teacher-list', [SchoolController::class, 'getTeacherList']);
 Route::get('/get-class-list', [SchoolController::class, 'getClassList']);
 Route::get('/get-list-of-books', [SchoolController::class, 'getListofBooks']);

 Route::post('/insert-classes', [SchoolController::class, 'insertClasses']);

 Route::get('/get-classes', [SchoolController::class, 'getClasses']);

 Route::post('/insert-connect-teacher-class-books', [SchoolController::class, 'insertConnectTeacherClassBooks']);

 Route::get('/connect-teacher-class-books-list', [SchoolController::class, 'connectTeacherClassBooksList']);

 Route::get('/get-data-connect-teacher-class-books', [SchoolController::class, 'getDataConnectTeacherClassBooksList']);

 Route::get('/create-exam', [SchoolController::class, 'createExam']);

 Route::post('/insert-exam', [SchoolController::class, 'insertExam']);
 
 Route::get('/get-exam-list', [SchoolController::class, 'getExamList']);

 Route::get('/edit-exam-name', [SchoolController::class, 'editExamName']);

 Route::get('/create-student-mark', [SchoolController::class, 'createStudentMark']);

 Route::get('/create-class', [SchoolController::class, 'createClass']);

 Route::get('/get-sections-list', [SchoolController::class, 'getSectionsList']);

//  Route::get('/get-class-list', [SchoolController::class, 'getClassList']);

//  Route::get('/get-teacher-subject-list', [SchoolController::class, 'getTeacherSubjectList']);

 Route::get('/create-paper', [SchoolController::class, 'createPaper']);

 Route::post('/insert-create-paper', [SchoolController::class, 'insertCreatePaper']);

 Route::get('/get-paper-list', [SchoolController::class, 'getPaperList']);

 Route::get('/get-exams', [SchoolController::class, 'getExams']);

 Route::get('/edit-paper-data', [SchoolController::class, 'editPaperData']);

 Route::get('/get-teacher-attach-data', [SchoolController::class, 'getTeacherAttachData']);

 Route::get('/add-student-marks/{data}/{session_id}', [SchoolController::class, 'addStudentMarks']);

 Route::post('/insert-obtained-marks', [SchoolController::class, 'insertObtainedMarks']);

 Route::post('/print-result', [SchoolController::class, 'printResult']);



 Route::get('/view-result-sheets', [SchoolController::class, 'viewResultSheets']);

 Route::get('/department-form', [SchoolController::class, 'departmentForm']);

 Route::post('/insert-department', [SchoolController::class, 'insertDepartment']);

 Route::get('/department-list', [SchoolController::class, 'departmentList']);

 Route::get('/edit-department', [SchoolController::class, 'editDepartment']);

 Route::get('/get-department-list', [SchoolController::class, 'getDepartmentList']);

 Route::get('/get-classes-department-wise', [SchoolController::class, 'getClassesDepartmentWise']);

 Route::get('/attach-department-to-classes-form', [SchoolController::class, 'attachDepartmentToClassesForm']);

 Route::get('/edit-classes', [SchoolController::class, 'editClasses']);



 //account software

 Route::get('/get-data-of-employee', [HomeController::class, 'getDataofEmployee']);

 

 Route::get('/edit-employee', [HomeController::class, 'editEmployee']);

 Route::get('/add-employees', [HomeController::class, 'addEmployeeOtherSecondForm']);

 Route::post('/insert-employee-others', [HomeController::class, 'insertEmployeeOthers']);

 Route::get('/easypaisa-form', [HomeController::class, 'easypaisaForm']);

 Route::get('/easypaisa-form', [HomeController::class, 'easypaisaForm']);

 Route::post('/insert-easypaisa-amount', [HomeController::class, 'insertEasypaisaAmount']);

 Route::get('/get-report-of-easypaisa-amount', [HomeController::class, 'getReportofEasypaisaAmount']);

 Route::post('/edit-easypaisa-paid-amount', [HomeController::class, 'editEasypaisaPaidAmount']);

 Route::get('/easypaisa-outsource-form', [HomeController::class, 'easypaisaOutsourceForm']);

 Route::post('/insert-easypaisa-outsource', [HomeController::class, 'insertEasypaisaOutsource']);

 Route::get('/easypaisa-outsource-list', [HomeController::class, 'easypaisaOutsourceList']);

 Route::get('/edit-easypaisa-outsource-amount', [HomeController::class, 'editEasypaisaOutsourceAmount']);

 Route::get('/delete-easypaisa-outsource-amount', [HomeController::class, 'deleteEasypaisaOutsourceForm']);

 Route::post('/get-employees', [HomeController::class, 'getEmployees']);

 Route::get('/account-head', [HomeController::class, 'accountHead']);

 Route::get('/edit-account-head', [HomeController::class, 'editAccountHead']);

 Route::post('/insert-account-head', [HomeController::class, 'insertAccountHead']);

 
 Route::get('/get-head-list', [HomeController::class, 'getHeadList']);


 Route::get('/locker-form', [HomeController::class, 'lockerForm']);

 Route::post('/insert-locker-amount', [HomeController::class, 'insertLockerAmount']);

 Route::get('/get-locker-amount-list', [HomeController::class, 'getLockerAmountList']);

 Route::post('/edit-locker-amount', [HomeController::class, 'editLockerAmount']);

 Route::get('/locker-outsource-form', [HomeController::class, 'lockerOutsourceForm']);

 Route::post('/insert-locker-outsource-amount', [HomeController::class, 'insertLockerOutsourceAmount']);

 Route::get('/get-locker-outsource-amount-list', [HomeController::class, 'getLockerOutsourceAmountList']);

 Route::get('/edit-locker-outsource-amount', [HomeController::class, 'editLockerOutsourceAmount']);

 Route::get('/delete-locker-outsource-amount', [HomeController::class, 'deleteLockerOutsourceAmount']);

 Route::get('/salary-form', [HomeController::class, 'salaryForm']);

 Route::get('/pay-now-salary/{id}/{date}/{salary}/{name}/{joining}/{employee_post}', [HomeController::class, 'payNowSalary']);

 Route::get('/get-data-of-employee-salary', [HomeController::class, 'getDataofEmployeeSalary']);

 Route::get('/pending-form', [HomeController::class, 'pendingForm']);

 Route::post('/insert-pending', [HomeController::class, 'insertPending']);

 Route::get('/get-pending-list', [HomeController::class, 'getPendingList']);

 Route::get('/get-employee', [HomeController::class, 'getEmployee']);

 Route::post('/check-advance-salary', [HomeController::class, 'checkAdvanceSalary']);
 
 Route::post('/check-pendings', [HomeController::class, 'checkPendings']);

 Route::post('/final-salary-insert', [HomeController::class, 'finalSalaryInsert']);

Route::post('post-login', [HomeController::class, 'postLogin'])->name('login.post');

Route::get('logout', [HomeController::class, 'logout']);

Route::get('change-password', [HomeController::class, 'changePassword']);

Route::post('insert-password', [HomeController::class, 'insertPassword']);

Route::get('/get-salary-detail/{month}', [HomeController::class, 'getSalaryDetail']);

Route::post('/delete-salary-record', [HomeController::class, 'deleteSalaryRecord']);

Route::get('/get-paid-salary/{month}', [HomeController::class, 'getPaidSalary']);

Route::get('/get-salary-upaid-detail/{month}', [HomeController::class, 'getSalaryUnpaidDetail']);

Route::post('/get-salary-pdf', [HomeController::class, 'getSalaryPdf']);

Route::get('/check-balance', [HomeController::class, 'checkBalance']);


//easypaisa
Route::get('/get-full-report-of-easypaisa-amount', [HomeController::class, 'getFullReportofEasypaisaAmount']);

Route::get('/view-report-easypaisa-amount/{from_date}/{to_date}', [HomeController::class, 'easypaisaFullReportSecondView']);

Route::get('/print-view-report-easypaisa-amount/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'printViewEasypaisaAmount']);


// locker
Route::get('/get-full-report-of-locker-amount', [HomeController::class, 'getFullReportofLockerAmount']);

Route::get('/view-report-locker-amount/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'lockerFullReportSecondView']);

Route::get('/print-view-report-locker-amount/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'printViewLockerAmount']);


Route::get('/view-bank-form', [HomeController::class, 'viewBankForm']);

Route::post('/insert-bank-amount', [HomeController::class, 'insertBankAmount']);

Route::get('/get-bank-expense-list', [HomeController::class, 'getBankExpenseList']);

Route::get('/edit-bank-amount', [HomeController::class, 'editBankAmount']);

Route::get('/get-full-report-of-bank-amount', [HomeController::class, 'getFullReportOfBankAmount']);

Route::get('/view-report-bank-amount/{from_date}/{to_date}/{bank_name}', [HomeController::class, 'viewReportBankAmount']);

Route::get('/view-report-bank-amount/{from_date}/{to_date}/{bank_name}', [HomeController::class, 'viewReportBankAmount']);


Route::get('/bank-outsource-form', [HomeController::class, 'bankOutsourceForm']);

Route::post('/insert-bank-outsource-amount', [HomeController::class, 'insertBankOutsourceAmount']);

Route::get('/bank-outsource-amount-list', [HomeController::class, 'bankOutsourceAmountList']);

Route::get('/edit-bank-outsource-amount', [HomeController::class, 'editBankOutsourceAmount']);

Route::get('/delete-bank-outsource-amount', [HomeController::class, 'deleteBankOutsourceAmount']);

Route::get('/view-grand-report-bank-amount', [HomeController::class, 'viewGrandReportBankAmount']);

Route::get('/view-amount-outsource', [HomeController::class, 'viewAmountOutsource']);

//qurbani

Route::get('/create-qurbani-form', [HomeController::class, 'createQurbaniForm']);

Route::post('/insert-qurbani-data', [HomeController::class, 'insertQurbaniData']);

Route::get('/get-qurbani-list', [HomeController::class, 'getQurbaniList']);

Route::post('/update-qurbani-data', [HomeController::class, 'updateQurbaniData']);

Route::get('/create-qurbani-parts-form', [HomeController::class, 'createQurbaniPartsForm']);

Route::get('/get-all-qurbani', [HomeController::class, 'getAllQurbani']);

Route::get('/edit-qurbani-data', [HomeController::class, 'editQurbaniData']);

Route::post('/get-qurbani-data', [HomeController::class, 'getQurbaniData']);

Route::post('/insert-qurbani-parts-data', [HomeController::class, 'insertQurbaniPartsData']);

Route::get('/get-all-data-qurbani', [HomeController::class, 'getAllDataQurbani']);

Route::get('/edit-qurbani-parts-data', [HomeController::class, 'editQurbaniPartsData']);

Route::get('/delete-qurbani-parts', [HomeController::class, 'deleteQurbaniParts']);

Route::get('/view-qurbani', [HomeController::class, 'viewQurbani']);

Route::get('/view-qurbani-data/{id}/{qurbani_name}', [HomeController::class, 'viewQurbaniData']);

Route::get('/view-qurbani-part/{id}', [HomeController::class, 'viewQurbaniPart']);

Route::get('/diary-form/{day?}', [HomeController::class, 'diaryForm']);

Route::post('/insert-diary', [HomeController::class, 'insertDiary']);

Route::post('/insert-holiday-diary', [HomeController::class, 'insertHolidayDiary']);

Route::get('/get-list-of-diary', [HomeController::class, 'getListOfDiary']);

Route::get('/edit-student-diary', [HomeController::class, 'editStudentDiary']);

Route::get('/delete-student-diary', [HomeController::class, 'deleteStudentDiary']);

Route::get('/view-diary', [SchoolController::class, 'viewDiary']);

Route::get('/get-diary/{diary_of_class}/{get_student_name}/{diary_date}', [SchoolController::class, 'getDiary']);

Route::get('/send-sms', [SchoolController::class, 'sendSms']);

Route::get('/get-list-of-holiday-diary', [SchoolController::class, 'getListOfHolidayDiary']);

Route::get('/edit-student-holiday-diary', [SchoolController::class, 'editStudentHolidayDiary']);

Route::get('/delete-student-holiday-diary', [SchoolController::class, 'deleteStudentHolidayDiary']);

Route::get('/view-receipt', [SchoolController::class, 'viewReceipt']);

Route::post('/print-result-sheet', [SchoolController::class, 'printResultSheet']);


});


Route::group(array('middleware' => 'guest'), function () {

    Route::get('/register', [App\Http\Controllers\HomeController::class, 'register'])->name('register');

Route::post('/register-user', [App\Http\Controllers\HomeController::class, 'registerUser'])->name('register-user');


    Route::get('login', [HomeController::class, 'loginForm'])->name('login');
    Route::post('post-login', [HomeController::class, 'postLogin'])->name('login.post');
});

// Auth::routes();