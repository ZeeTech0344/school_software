<!DOCTYPE html>
<html lang="en">

<head>
    {{-- 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> -->

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}




    {{-- from role and permissin header --}}
    {{-- <meta charset="utf-8"> --}}


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>NFC</title>
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    {{-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> --}}

    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.bootstrap5.min.css"> --}}

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css"> --}}

    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Core build with no theme, formatting, non-essential modules -->


    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="{{ url('js/html5-qrcode.min.js') }}"></script>


    {{-- end --}}




    <style>
        .toast-message {
            text-align: right !important;
            font-size: 20px !important;
        }


        .note-editor.note-frame .note-editing-area .note-editable {
            direction: rtl !important;
            text-align: right !important;
        }

        @media print {
            .hide-on-print {
                display: none;
            }
        }

        .select2-container .select2-selection--single {
            height: 36px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {

            /* margin-top: 3px !important; */
        }

        .check_riders {
            width: 20px;
            height: 20px;
        }

        /* #convert_to_number{
            font-size: 13px;
        } */

        .nav-item a {
            font-weight: bolder;
        }


        .visible {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .d-none {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            pointer-events: none;
            /* Ensure no interaction when hidden */
        }

        body {

            font-family: "Jameel Noori Nastaleeq Kasheeda" !important;

        }

        select,
        option {
            text-align: right !important;
        }

        .fade-in-content {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .fade-in-content.loaded {
            opacity: 1;
        }
    </style>

</head>

<body id="page-top">



    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <nav class="navbar navbar-expand-lg p-3" style="z-index: 100; background-color:#4e73df; color:white;">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto" style="color:white;">

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    طلباءڈائری
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('diary-form') }}"></i>یوم کیفیت</a>
                                    <a class="dropdown-item" href="{{ url('diary-form/holiday') }}"></i>ہفتہ وار
                                        چھٹی</a>
                                    <a class="dropdown-item" href="{{ url('view-diary') }}"> ڈائری دیکھیں</a>
                                </div>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    قربانی
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" id="add_employee_id"
                                        href="{{ url('/create-qurbani-form') }}">قربانی فارم</a>
                                    <a class="dropdown-item" href="{{ url('/create-qurbani-parts-form') }}">قربانی
                                        حصہ</a>
                                    <a class="dropdown-item" href="{{ url('/view-qurbani') }}">قربانی کی رپورٹ</a>
                                    <a class="dropdown-item" href="{{ url('/view-qurbani') }}">قربانی حصہ رپورٹ</a>
                                </div>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">تنخواہ
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item " href="{{ url('salary-form') }}"> تنخواہ بنائیں</a>
                                    <a class="dropdown-item " href="{{ url('pending-form') }}">ادھار فارم </a>
                                </div>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">اکاونٹ
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item " href="{{ url('view-bank-form') }}"> اخراجات</a>
                                    <a class="dropdown-item " href="{{ url('bank-outsource-form') }}"> رقم جمع کریں
                                    </a>
                                    <a class="dropdown-item " href="{{ url('get-full-report-of-bank-amount') }}">
                                        رپورٹ </a>
                                </div>
                            </li>


                            {{-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">لاکر اکاؤنٹ
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item " href="{{ url('locker-form') }}"> لاکر اخراجات</a>
                                    <a class="dropdown-item " href="{{ url('locker-outsource-form') }}"> رقم جمع کریں
                                    </a>
                                    <a class="dropdown-item " href="{{ url('get-full-report-of-locker-amount') }}">
                                        رپورٹ </a>
                                </div>
                            </li> --}}


                            {{-- 
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">ایزی پیسہ اکاؤنٹ
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item " href="{{ url('easypaisa-form') }}">ایزی پیسہ اخراجات</a>
                                    <a class="dropdown-item " href="{{ url('easypaisa-outsource-form') }}"> رقم جمع
                                        کریں </a>
                                    <a class="dropdown-item"
                                        href="{{ url('get-full-report-of-easypaisa-amount') }}">رپورٹ</a>
                                </div>
                            </li> --}}


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">امتحان
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('add-book-form') }}">کتابیں بنائیں</a>
                                    <a class="dropdown-item" href="{{ url('connect-teacher-class-books') }}"> منسلک
                                        کریں</a>
                                    <a class="dropdown-item" href="{{ url('create-exam') }}">امتحان کا نام بنائیں</a>
                                    <a class="dropdown-item" href="#" id="create_paper">رزلٹ بنائیں</a>
                                    <a class="dropdown-item " href="#" id="print_results">رزلٹ پرنٹ کریں</a>
                                </div>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    حاضری
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('student-attendence-view') }}">طلباء
                                        حاضری</a>
                                    <a class="dropdown-item" href="{{ url('student-attendence-qrcode-view') }}">طلباء
                                        کے آنے کی حاضری </a>
                                    <a class="dropdown-item"
                                        href="{{ url('student-attendence-qrcode-go-view') }}">طلباء کے جانے کی حاضری
                                    </a>
                                    <a class="dropdown-item" href="{{ url('student-attendence-report-view') }}">
                                        طلباء کی حاضری رپورٹ</a>
                                    <a class="dropdown-item"
                                        href="{{ url('employee-list-attendence-view') }}"></i>عملہ حاضری</a>
                                    <a class="dropdown-item"
                                        href="{{ url('employee-attendance-qrcode-view') }}"></i>عملہ کے آنے کی
                                        حاضری</a>
                                    <a class="dropdown-item"
                                        href="{{ url('employee-attendance-qrcode-time-to-go-view') }}"></i>عملہ کے
                                        جانے کی حاضری</a>
                                    <a class="dropdown-item" href="{{ url('get-staff-attendence-report') }}"></i>عملہ
                                        حاضری رپورٹ</a>
                                </div>
                            </li>



                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    عملہ/اکاونٹ ہیڈ
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('add-employees') }}"></i>عملہ فارم</a>
                                    <a class="dropdown-item" href="{{ url('employee-list-view') }}"ْ>عملہ لسٹ</a>
                                    <a class="dropdown-item" href="{{ url('employee-list-view') }}"ْ>عملہ آئی ڈی
                                        کارڈ</a>
                                    <a class="dropdown-item" href="{{ url('account-head') }}"> اکاٰونٹ ہیڈ</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    فیس ووچر
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ url('recieve-fee-voucher') }}"> فیس ووچر موصول
                                        کریں</a>
                                    <a class="dropdown-item" href="{{ url('fee-voucher') }}"> فیس ووچر بنائیں</a>
                                    <a class="dropdown-item" href="{{ url('voucher-head-attach-form') }}">ووچر ہیڈ
                                        ملائیں</a>
                                    <a class="dropdown-item" href="{{ url('voucher-head') }}">ووچر ہیڈ بنائیں</a>
                                    <a class="dropdown-item" href="{{ url('fine-form') }}">جر مانہ فارم</a>
                                    <a class="dropdown-item" href="#" id="view-not-recieved-voucher-list">
                                        ناموصول لسٹ</a>
                                    <a class="dropdown-item" href="{{ url('view-print-voucher-multiple') }}"> فیس
                                        ووچر پرنٹ کریں</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    اکیڈمی
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" id="add_employee_id"
                                        href="{{ url('/admission-form') }}">داخلہ فارم</a>
                                    <a class="dropdown-item" href="{{ url('/department-form') }}">شعبہ فارم</a>
                                    <a class="dropdown-item"
                                        href="{{ url('/attach-department-to-classes-form') }}">شعبہ اور درجہ
                                        بنائیں</a>
                                    {{-- <a class="dropdown-item"   href="{{ url('/create-class') }}"> شعبہ بنائیں</a> --}}
                                    <a class="dropdown-item" href="{{ url('/id-cards/student-list') }}">آئی ڈی
                                        کارڈ</a>
                                    <a class="dropdown-item" href="{{ url('promote-admissions') }}">پروموٹ کریں</a>
                                    <a class="dropdown-item" href="{{ url('admission-report-view') }}">رپورٹ</a>

                                </div>
                            </li>



                        </ul>






                        <select name="session" id="session" class="form-control" style="width:20%">

                        </select>


                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary mr-5" href="#" id="navbarDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                                {{ isset(Auth::User()->name) ? Auth::User()->name : '' }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" id="logout" href="{{ url('logout') }}"> <i
                                        class="fas fa-power-off"></i> Logout</a>
                            </div>
                        </div>

                        <a href="#" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            id="check_amount">چیک رقم</a>

                    </div>
                </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid p-3">

                    <!-- Page Heading -->
                    {{-- <div class="d-sm-flex align-items-center justify-content-end mb-4"> --}}
                    {{-- <h1 class="h3 mb-0 text-gray-800">Dashboard</h1> --}}
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div> --}}

                    <!-- Content Row -->



                    {{-- end this area is hide due to speed issues 

                 {{-- <div class="row head-line"  > --}}





                    <!-- Earnings (Monthly) Card Example -->
                    {{-- <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                           Easypaisa Last Closing</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 easypaisa_amount_last"></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Earnings (Monthly) Card Example -->
                    {{-- <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                           HBL Last Closing</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 hbl-last-closing-amount"></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Earnings (Monthly) Card Example -->
                    {{-- <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Locker
                                        </div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">215,000</div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- end this area is hide due to speed issues  --}}


                    <!-- Pending Requests Card Example -->
                    {{-- <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Requests</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- </div>  --}}
