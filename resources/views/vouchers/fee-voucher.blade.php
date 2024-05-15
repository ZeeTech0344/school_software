@extends('layout.structure')

@section('content')
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}



        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">لسٹ</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_employee_other_report"><i
                   class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a> --}}

                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                       id="employee_other_reports"><i class="fas fa-download fa-sm text-white-50"></i>Generate Full
                       Report</a> --}}
                        <div class="checkbox-wrapper-13" style="align-items: center;">
                            <input type="checkbox" style="margin-right:3px;" id="delete_voucher" onclick="deleteVouchers()">
                            Voucher Deleted (Multiple Created)
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="form-inline d-flex justify-content-center">

                            <input type="button" id="delete_vouchers" class="btn btn-danger mb-2 mr-2 d-none"
                                value="مٹائیں ">
                            <input type="button" id="print_vouchers" class="btn btn-success mb-2 mr-2" value="پرنٹ کریں">
                            <input type="button" id="search" class="btn btn-primary mb-2" value="سرچ کریں">

                            {{-- <div class="form-group mx-sm-2 mb-2">

                                <select name="section" id="section" class="form-control">
                                    <option value="">سیکشن منتخب کریں</option>
                                    @foreach ($sections as $section)
                                        <option>{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div> --}}


                            <div class="form-group mx-sm-1 mb-2 ml-0">

                                <select name="class_id_search" id="class_id_search" class="form-control toselect-tag">
                                    <option value="">کلاس منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class . ' (' . $class->getDepartments->department . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mx-sm-2 mb-2">
                                <input type="month" name="for_the_month_search" id="for_the_month_search"
                                    class="form-control">
                            </div>

                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value"
                                placeholder="سرچ کریں">
                        </div>
                        <table class="table table-bordered get-list-of-admissions" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> درجہ </th>
                                    <th> پیدائش </th>
                                    <th> والد کانام </th>
                                    <th> نام </th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary"> ووچر بنائیں </h6>

                    <div class="form-check form-switch">
                        {{-- <input class="form-check-input" type="checkbox" id="single_voucher"
                            onclick="checkSingVoucher(this)"> --}}
                        <div class="checkbox-wrapper-13" style="align-items: center;">
                            <input type="checkbox" style="margin-right:3px;" id="single_voucher"
                                onclick="checkSingVoucher()">
                            Single Voucher
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <form id="voucher-insert">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">مہینہ </label>
                                <input type="month" name="for_the_month" id="for_the_month" class="form-control">
                            </div>
                        </div>


                        {{-- <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">شعبہ</label>
                                <select name="department_id" id="department_id" onchange="getClasses()" class="form-control">
                                    <option value="">شعبہ منتخب کریں</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">درجہ</label>
                                <select name="class_id" id="class_id" class="form-control  toselect-tag">
                                    <option value="">درجہ منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class . ' (' . $class->getDepartments->department . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">سیکشن</label>
                                <select name="section" id="section" class="form-control" onchange="getHeads()">
                                    <option value="">سیکشن منتخب کریں</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section }}">{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row d-none" id="student_names">
                            <div class="col">
                                <label for="exampleFormControlInput1">نام </label>
                                <select name="student_id" id="fname" class="form-control toselect-tag"
                                    onchange="getFineArrears(this)" style="width:100%;">
                                    <option value="">نام منتخب کریں</option>
                                </select>
                            </div>
                        </div>


                        <div class="row d-none" id="fine_row">
                            <div class="col">
                                <label for="exampleFormControlInput1">جرمانہ </label>
                                <input type="number" name="fine" id="fine" class="form-control" readonly value="0"
                                    onkeyup="calculateVoucherAmount(this)">
                            </div>
                        </div>

                        <div class="row d-none" id="arrears_row">
                            <div class="col">
                                <label for="exampleFormControlInput1">بقایاجات </label>
                                <input type="number" name="arrears" id="arrears" class="form-control" value="0"
                                    onkeyup="calculateVoucherAmount(this)" readonly>
                            </div>
                        </div>

                        <div>
                            <label for="exampleFormControlInput1">ہیڈ منتخب کریں</label>


                            @foreach ($heads as $head)
                                <div class="form-check" style="display: flex; justify-content:end; margin-top:10px;">
                                    <div id="checkboxes"><input type="number" name="head" readonly
                                            onkeyup="calculateVoucherAmount(this)" class="head{{ $head->id }}"
                                            style="margin-right:5px;"></div>

                                    <div style="min-width:125px; text-align:right"><label class="form-check-label"
                                            style="width:auto;margin-right:30px;" for="defaultCheck1">
                                            {{ $head->head }}
                                        </label>
                                        <input
                                            class="form-check-input all_checkboxes uncheck_checked edit_checkbox{{ $head->id }}"
                                            disabled type="checkbox" onclick="calculateUsingCheck(this)"
                                            value=" {{ $head->id }}">
                                    </div>
                                </div>
                            @endforeach

                            <input type="number" class="form-control w-25" name="head_amount_total"
                                id="head_amount_total" style="margin-top:20px;" readonly>

                        </div>



                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> آخری تاریخ </label>
                                <input type="date" class="form-control" id="last_date" name="last_date"
                                    onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="row d-none" id="status_row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> Status </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Not Recieved</option>
                                    <option value="1">Recieved</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="voucher_hidden_id" id="voucher_hidden_id">

                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection




@section('script')
    <script>
        $(document).ready(function() {
            var currentDate = new Date(); // Get current date
            var currentMonth = currentDate.toISOString().slice(0, 7); // Get current month in YYYY-MM format
            $("#for_the_month").attr("min", currentMonth); // Set min attribute to current month
        });



        // function getClasses(edit_class_id, edit_department_id){


        //     console.log(edit_class_id, edit_department_id);

        //     var department_id = $("#department_id")[0].value;

        //     $.ajax({
        //         url: "{{ url('get-classes-department-wise') }}",
        //         type: "GET",
        //         data: {
        //             id: department_id,
        //         },
        //         success: function(data) {


        //             $('#class_id')[0].innerHTML="";
        //            var create_option = document.createElement("option");
        //             create_option.value = "";
        //             create_option.innerText = "کلاس منتخب کریں";
        //             $('#class_id').append(create_option);
        //             $.each(data, function(index, get_classes) {
        //                 $('#class_id').append('<option value=' + get_classes["id"] + '>' +
        //                     get_classes["class"] + '</option>');



        //             });


        //         }
        //     })

        // }

        function calculateVoucherAmount(element) {
            var total = 0;

            // Iterate over each input with class 'headXXX' and sum their values
            $('input[class^="head"]').each(function() {
                var value = parseFloat($(this).val()) || 0;
                total += value;
            });

            // Add the fine value
            var fineValue = parseFloat($('#fine').val()) || 0;
            total += fineValue;

            var arrears = parseFloat($('#arrears').val()) || 0;
            total += arrears;

            // Update the total in the 'head_amount_total' input
            $('#head_amount_total').val(total);
        }


        function calculateUsingCheck(element) {

            var checkboxValue = parseFloat($(element).val()) || 0;
            var head_amount = parseFloat($(".head" + checkboxValue).val()) || 0;

            var total_amount = $('#head_amount_total')[0].value;

            if ($(element).is(':checked')) {
                total = parseFloat(total_amount) + head_amount;
            } else {
                total = parseFloat(total_amount) - head_amount;
            }

            $('#head_amount_total').val(total);
        }

        function checkSingVoucher() {

            const checkbox = $("#single_voucher")[0].checked; // Access the checkbox associated with the event

            if (checkbox) {
                // var confirm_voucher_type = confirm("!کیا آپ سنگل ووچر پرنٹ کرنا چاہتے ہیں ");
                // if (confirm_voucher_type) {
                $('#student_names').removeClass('d-none');
                $('#fine_row').removeClass('d-none');
                // $('#status_row').removeClass('d-none');
                $('#arrears_row').removeClass('d-none');
            } else
            //      {
            //         $('#single_voucher').prop('checked', false);
            //         $('#arrears').val('');
            //         $('#fine').val('');
            //         $(".toselect-tag").val(null).trigger("change");
            //     }
            // } else 
            {
                $('#student_names').addClass('d-none');
                $('#fine_row').addClass('d-none');
                // $('#status_row').addClass('d-none');
                $('#arrears_row').addClass('d-none');
                $('#arrears').val('');
                $('#fine').val('');
                $(".toselect-tag").val(null).trigger("change");
            }



        }









        function deleteVouchers() {

            const checkbox = $("#delete_voucher")[0].checked; // Access the checkbox associated with the event

            if (checkbox) {
                var confirm_voucher_type = confirm("!کیا آپ تمام ووچرز کو مٹائیں کرنا چاہتے ہیں");
                if (confirm_voucher_type) {

                    $("#delete_vouchers").removeClass("d-none");

                } else {
                    $("#delete_vouchers").addClass("d-none");
                }
            } else {
                $("#delete_vouchers").addClass("d-none");
            }



        }




        function getFineArrears(e) {



            var student_id = $("#fname")[0].value;
            var class_id = $("#class_id")[0].value;
            var session = $("#session")[0].value;
            var for_the_month = $("#for_the_month")[0].value;

            $.ajax({
                url: "{{ url('get-student-fine-and-arrears') }}",
                type: "GET",
                data: {
                    student_id: student_id,
                    class_id: class_id,
                    session_id: session,
                    for_the_month: for_the_month
                },
                success: function(data) {



                    $("#fine").val(data[0]);
                    $("#arrears").val(data[1]);
                    var head_amount_total = $("#head_amount_total")[0].value;
                    $("#head_amount_total").val(parseFloat(head_amount_total) + parseFloat(data[0]) +
                        parseFloat(data[1]));

                }
            })

        }


        function getHeads(edit_student, set_head_id) {

            var for_the_month = $("#for_the_month")[0].value;
            var class_id = $("#class_id")[0].value;
            var section = $("#section")[0].value;

            var checkboxes = $(".all_checkboxes");
            var session = $("#session")[0].value;

            $(".all_checkboxes").prop('checked', false);
            $(".all_checkboxes").prop('disabled', true);

            $('input[type="number"]').val('');

            $.ajax({
                url: "{{ url('get-heads-list') }}",
                type: "GET",
                data: {
                    for_the_month:for_the_month,
                    class_id: class_id,
                    section: section,
                    session: session
                },
                success: function(data) {


                    
                    if (edit_student !== true) {

                    if(data == "overall_created"){
                        newCustomSuccessAlert("آپ نے ووچر بنا لیے ہیں");
                        return false;
                    }

                    if(data == "invalid"){
                        newCustomSuccessAlert("اگلے مہینے کے ووچر ختم کریں");
                        return false;
                    }
                }

                    var total_amount_of_voucher = 0;
                    $.each(data, function(index, get_all_head_ids) {

                        $.each(checkboxes, function(index, get_value) {

                           

                            if (get_all_head_ids["get_head"]["id"] == get_value.value) {

                                if (edit_student == true) {

                                    
                                    var all_classes = $(get_value).attr("class");

                                    var array1 = all_classes.split(" ");

                                    // Remove dot from the elements of array2
                                    array2 = set_head_id.map(element => element.replace('.',
                                        ''));

                                    // Check if any value matches between the two arrays
                                    var result = array1.some(classValue => array2.includes(
                                        classValue));

                                    if (result) {
                                        // $(".head" + get_all_head_ids["head_id"]).prop("readonly", true);
                                        get_value.checked = true;
                                        get_value.disabled = false;

                                    }else{
                                        
                                        get_value.disabled = false;
                                    }

                                } else {
                                    // $(".head" + get_all_head_ids["head_id"]).prop("readonly", true);
                                    get_value.checked = true;
                                    get_value.disabled = false;


                                }
                                
                                $(".head" + get_all_head_ids["get_head"]["id"]).val(get_all_head_ids["amount"]);

                                if ($(".edit_checkbox" + get_all_head_ids["get_head"]["id"]).prop("checked")) {
                                total_amount_of_voucher = total_amount_of_voucher + get_all_head_ids["amount"];
                                }
                            }



                        });

                        $(".head" + get_all_head_ids["head_id"]).prop("readonly", false);

                    });

                    $("#head_amount_total").val(total_amount_of_voucher);

                }
            })





            //get heads
            var class_id = $("#class_id")[0].value;
            var section = $("#section")[0].value;

            $('#fname')[0].innerHTML = "";
            var for_the_month = $("#for_the_month")[0].value;

            if (edit_student == "") {
                getStudents(class_id, section, for_the_month);
            }


            $.ajax({
                url: "{{ url('get-student-list') }}",
                type: "GET",
                data: {
                    class_id: class_id,
                    session: session,
                    for_the_month: for_the_month,
                },
                success: function(data) {

                    $('#fname').append('<option value="">نام منتخب کریں</option>');
                    $.each(data, function(index, get_students) {
                        $('#fname').append('<option value=' + get_students["id"] + '>' + get_students["name"] + '</option>');
                    });

                }
            })



        }



        function displayImage(input) {
            const imgElement = document.getElementById('image_name');
            const file = input.files[0]; // Get the selected file

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Set the src attribute of the <img> element to the data URL
                    imgElement.src = e.target.result;
                };

                // Read the selected file as a data URL
                reader.readAsDataURL(file);
                $("#image_name")[0].classList.remove("d-none");
            } else {
                // If no file is selected, clear the <img> element
                imgElement.src = '';
            }
        }






        // admission_list.draw();





        function refreshTableAfterAdmissionYearLoad() {


            var voucher_head_attach_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: true,
                "info": true,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-unrecieved-voucher') }}",
                    data: function(d) {
                        d.search_value = $("#search_value").val();
                        d.session = $("#session").val();
                        d.class_id = $("#class_id_search").val();
                        d.for_the_month = $("#for_the_month_search").val();
                    }
                },

                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },


                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    }
                ],

                success: function(data) {

                }
            });




            //search functions
            $("#session").on('change', function(e) {

                voucher_head_attach_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    voucher_head_attach_list.draw();
                }
            });


            $("#search").on("click", function() {

                voucher_head_attach_list.draw();

            })



            //delete multiple vouchers

            $("#delete_vouchers").click(function() {

                var for_the_month_search = $("#for_the_month_search")[0].value;
                var class_id_search = $("#class_id_search")[0].value;
                var session = $("#session")[0].value;
                var confirm_delete = confirm("کیا آپ تمام ووچرز کو مٹائیں کرنا چاہتے ہیں");

                if (!confirm_delete) {
                    return false;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('delete-multiple-vouchers') }}",
                    type: "POST",
                    data: {
                        for_the_month_search: for_the_month_search,
                        class_id_search: class_id_search,
                        session_id: session
                    },
                    success: function(data) {
                        voucher_head_attach_list.draw();

                    }
                })


            })






            //form
            $('#voucher-insert').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    class_id: "required",
                    for_the_month: "required",
                    last_date: "required"
                },

                submitHandler: function(form) {




                    //heads checked or not
                    var checkedValues = [];
                    $(".all_checkboxes:checked").each(function() {
                        checkedValues.push($(this).val());
                    });

                    //set heads amount

                    //final_array inserting head id and amount

                    var final_array = [];
                    $.each(checkedValues, function(index, value) {
                        final_array.push([value, $(".head" + value.trim())[0].value]);
                    });


                    var check_voucher = $('#single_voucher').prop('checked');
                    if (check_voucher) {
                        checked_checkbox = "voucher_checked";
                    } else {
                        checked_checkbox = "voucher_not_checked";
                    }
                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);
                    formData.append('heads_detail', final_array);
                    formData.append('single_voucher', checked_checkbox);


                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-fee-vouchers') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {

                           

                            printContentViaAjax(data);
                            var student_id = $("#fname")[0].value;
                            var for_the_month = $("#for_the_month")[0].value;
                            var class_id = $("#class_id")[0].value;

                            $("#for_the_month_search").val(for_the_month);
                            $("#class_id_search").val(class_id);
                            $("#search").click();
                            $('#voucher-insert')[0].reset();
                            $('#voucher_hidden_id').val("");
                            successAlert();

                        },
                        error: function(data) {


                        }

                    })
                }
            });
        }



        $("#print_vouchers").click(function() {

            var class_id = $("#class_id_search")[0].value;
            var session_id = $("#session")[0].value;
            var for_the_month = $("#for_the_month_search")[0].value;
            var data = {
                class_id: class_id,
                session_id: session_id,
                for_the_month: for_the_month,
                status: 0
            }

            printContentViaAjax(data);





        })


        function printContentViaAjax(data) {


            if (data !== "saved") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('get-voucher-print') }}",
                    type: 'POST',
                    data: {
                        data: data
                    },
                    success: function(response) {

                        var printWindow = window.open('', '_blank');
                        printWindow.document.write(response);
                        setTimeout(function() {
                            printWindow.print();
                            printWindow.close();
                        }, 500);

                    }
                });
            }

        }








        $(document).on("click", ".print_voucher", function() {

            var id = $(this).data("id");

            $.ajax({
                url: "{{ url('print-voucher-single') }}" + "/" + id,
                type: 'GET',
                success: function(response) {

                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(response);
                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 500);

                }
            });

        })

        $(document).on("click", ".edit_voucher", function() {




            var id = $(this).data("id");
            $('#voucher-insert')[0].reset();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-voucher') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data["admissions"][0]["class_id"]);
                    if (!$("#single_voucher").is(':checked')) {
                        $("#single_voucher")[0].click();
                    }
                    // $("#single_voucher").prop("checked", true); 

                    // getHeads(data["student_id"], data["for_the_month"],not_edit_student)

                    getStudents(data["class_id"], data["section"], data["student_id"]);

                    $("#voucher_hidden_id").val(data["id"]);
                    $("#for_the_month").val(data["for_the_month"]);

                    $('#class_id').val(data["admissions"][0]["class_id"]).trigger('change');
                    $('#section').val(data["section"]);
                    var edit_student = true;



                    

                  
                    

                    $("#head_amount_total_after_date").val(data["after_due_date"]);
                    $("#last_date").val(data["last_date"]);
                    $("#voucher_hidden_id").val(data["id"]);
                    $("#created_at_hidden").val(data["created_at"]);

                    const inputString = data["voucher_heads"];

                    let cleanedString = inputString.replace(/"/g, '');

                    let voucher_heads = cleanedString.replace(/"/g, '');
                    $(".uncheck_checked")[0].checked = false;



                    var total_amount_of_head = 0;
                    // Split the string by commas and group into subarrays of two
                    var set_head_id = [];
                    let heads_array = voucher_heads.split(',').reduce((acc, curr, index) => {
                        if (index % 2 === 0) {
                            acc.push([parseInt(curr), parseInt(cleanedString.split(',')[index +
                                1])]);

                            $(".head" + curr.trim()).val(parseInt(cleanedString.split(',')[
                                index +
                                1]));

                            total_amount_of_head = total_amount_of_head + parseInt(cleanedString
                                .split(',')[index + 1]);

                            $(".edit_checkbox" + curr.trim())[0].disabled = false;
                            $(".edit_checkbox" + curr.trim())[0].checked = true;


                            set_head_id.push(".edit_checkbox" + curr.trim());



                        }
                        return acc;
                    }, []);

                    getHeads(edit_student, set_head_id);
                    $("#head_amount_total").val(total_amount_of_head);

                    $("#fine")[0].value = data["fine"];
                    $("#arrears")[0].value=data["arrears"];

                }
            })

        })






        //delete single voucher
        $(document).on("click", ".delete_voucher", function() {

            var id = $(this).data("id");

            var element = this;

            var confirm_delete = confirm("Are you sure! delete voucher");

            if (!confirm_delete) {
                return false;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-voucher') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $(element).parent().parent().parent().parent().fadeOut();


                }
            })

        })









        function removeBorder(e) {
            e.style.border = "";
            if (e.id == "image") {
                $("#image_name").attr("src", e.value);
            }
        }


        $(".toselect-tag").select2();


        //for edit purpose not for voucher generate

        function getStudents(class_id, section, student_id = null) {


            $('#fname').text(""); // Clear the content
            var session = $("#session").val();


            $.ajax({
                url: "{{ url('get-student-class-wise') }}",
                type: "GET",
                data: {
                    class_id: class_id,
                    section: section,
                    session: session
                },
                success: function(data) {

                    console.log(data);
                    $('#fname')[0].innerHTML = "";
                    $.each(data, function(index, get_students) {

                        if (get_students["id"] == student_id) {
                            $('#fname').append('<option selected value=' + get_students["id"] + '>' +
                                get_students["name"] + '</option>');
                        } else {
                            $('#fname').append('<option value=' + get_students["id"] + '>' +
                                get_students["name"] + '</option>');
                        }
                    });
                }
            });
        }
    </script>
@endsection
