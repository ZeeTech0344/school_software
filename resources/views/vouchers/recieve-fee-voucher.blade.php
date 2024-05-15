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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="form-inline d-flex justify-content-center">


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

                                <select name="class_id_search" id="class_id_search" class="form-control">
                                    <option value="">کلاس منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class }}</option>
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
                    <h6 class="m-0 font-weight-bold text-primary"> ووچر موصول کریں </h6>
                </div>
                <div class="card-body">
                    <form id="voucher-insert">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">مہینہ </label>
                                <input type="month" name="for_the_month" id="for_the_month" onchange="getHeads()"
                                    class="form-control">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">درجہ</label>
                                <select name="class_id" id="class_id" onchange="getHeads()" class="form-control">
                                    <option value="">درجہ منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class." (".$class->getDepartments->department.")"}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" id="student_names">
                            <div class="col">
                                <label for="exampleFormControlInput1">نام </label>
                                <select name="student_id" id="fname" class="form-control toselect-tag"
                                    onchange="getStudentVoucher(this)" style="width:100%;">
                                    <option value="">نام منتخب کریں</option>
                                </select>
                            </div>
                        </div>


                        <div class="row" id="fine_row">
                            <div class="col">
                                <label for="exampleFormControlInput1">جرمانہ </label>
                                <input type="number" name="fine" id="fine" readonly class="form-control"
                                    onkeyup="calculateVoucherAmount(this)">
                            </div>
                        </div>

                        <div class="row" id="arrears_row">
                            <div class="col">
                                <label for="exampleFormControlInput1">بقایاجات </label>
                                <input type="number" name="arrears" id="arrears" class="form-control"
                                    onkeyup="calculateVoucherAmount(this)" readonly>
                            </div>
                        </div>

                        <div>
                            <label for="exampleFormControlInput1">ہیڈ منتخب کریں</label>


                            @foreach ($heads as $head)
                                <div class="form-check" style="display: flex; justify-content:end; margin-top:10px;">
                                    <div id="checkboxes"><input type="number" readonly name="head"
                                            onkeyup="calculateVoucherAmount(this)" class="head{{ $head->id }}"
                                            style="margin-right:5px;"></div>

                                    <div style="min-width:125px; text-align:right"><label class="form-check-label"
                                            style="width:auto;margin-right:30px;" for="defaultCheck1">
                                            {{ $head->head }}
                                        </label>
                                        <input class="form-check-input all_checkboxes" readonly style="position: unset"
                                            type="checkbox" onclick="calculateUsingCheck(this)"
                                            value=" {{ $head->id }}">
                                    </div>
                                </div>
                            @endforeach

                            <div style="margin-bottom: 20px;  display:flex; justify-content:start;">
                                <input type="number" class="form-control w-25" name="head_amount_total"
                                    id="head_amount_total" style="margin-right:5px;" readonly> ٹوٹل
                            </div>

                            <div style="display:flex; justify-content:start;">
                                <input type="number" class="form-control w-25" name="head_amount_total_after_date"
                                    id="head_amount_total_after_date" style="margin-right:5px;" readonly> ٹوٹل(مقررہ تاریخ
                                کے بعد)
                            </div>

                        </div>


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">آخری تاریخ </label>
                                <input type="date" id="last_date" name="last_date" readonly class="form-control">

                            </div>
                        </div>

                        
                        <div class="row" id="status_row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> Account </label>
                                <select name="account_name" id="account_name" autofocus  class="form-control"  onkeyup="validateError(this)">
                                    <option value="">منتخب کریں</option>
                                    {{-- <option>Easypaisa</option>
                                    <option>Locker</option> --}}
                                    @foreach($bank_name as $bank)
                                    <option value="{{ $bank }}">{{$bank}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>


                        <div class="row d-none" id="status_row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> Status </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Recieved</option>
                                    <option value="0">Not Recieved</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="voucher_hidden_id" id="voucher_hidden_id">
                        <input type="hidden" name="created_at_hidden" id="created_at_hidden">
                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection




@section('script')
    <script>
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

        function checkSingVoucher(e) {
            const checkbox = e.checked; // Access the checkbox associated with the event

            if (checkbox) {
                var confirm_voucher_type = confirm("Are you sure to print! Single Voucher");
                if (confirm_voucher_type) {
                    $('#student_names').removeClass('d-none');
                    $('#fine_row').removeClass('d-none');
                    $('#status_row').removeClass('d-none');
                    $('#arrears_row').removeClass('d-none');
                } else {
                    $('#single_voucher').prop('checked', false);
                }
            } else {
                $('#student_names').addClass('d-none');
                $('#fine_row').addClass('d-none');
                $('#status_row').addClass('d-none');
                $('#arrears_row').addClass('d-none');
            }



        }




        function getStudentVoucher(e) {

            var student_id = $("#fname")[0].value;
            var class_id = $("#class_id")[0].value;
            var session = $("#session")[0].value;
            var for_the_month = $("#for_the_month")[0].value;

            $('input[type="number"]').val('');

            $.ajax({
                url: "{{ url('get-student-voucher-data') }}",
                type: "GET",
                data: {
                    student_id: student_id,
                    class_id: class_id,
                    session_id: session,
                    for_the_month: for_the_month
                },
                success: function(data) {

                    console.log(data);
                    if (data.length !== 0) {

                        $("#fine").val(data[0]["fine"]);
                        $("#arrears").val(data[0]["arrears"]);
                        $("#head_amount_total").val(data[0]["before_due_date"]);
                        $("#head_amount_total_after_date").val(data[0]["after_due_date"]);
                        $("#last_date").val(data[0]["last_date"]);
                        $("#voucher_hidden_id").val(data[0]["id"]);
                        $("#created_at_hidden").val(data[0]["created_at"]);



                        const inputString = data[0]["voucher_heads"];

                        let cleanedString = inputString.replace(/"/g, '');

                        let voucher_heads = cleanedString.replace(/"/g, '');

                        // Split the string by commas and group into subarrays of two
                        let heads_array = voucher_heads.split(',').reduce((acc, curr, index) => {
                            if (index % 2 === 0) {
                                acc.push([parseInt(curr), parseInt(cleanedString.split(',')[index +
                                    1])]);
                                $(".head" + curr.trim()).val(parseInt(cleanedString.split(',')[index +
                                    1]));
                                console.log($(".head" + curr.trim()));
                            }
                            return acc;
                        }, []);

                    }

                }
            })

        }


        function getHeads() {




            $('input[type="number"]').val('');



            //get heads


            var class_id = $("#class_id")[0].value;

            var session = $("#session")[0].value;

            $('#fname')[0].innerHTML = "";
            var for_the_month = $("#for_the_month")[0].value;

            $.ajax({
                url: "{{ url('get-student-list-create-voucher') }}",
                type: "GET",
                data: {
                    class_id: class_id,
                    session: session,
                    for_the_month: for_the_month
                },
                success: function(data) {

                    console.log(data);

                    var create_option = document.createElement("option");
                    create_option.value = "";
                    create_option.innerText = "نام منتخب کریں";
                    $('#fname').append(create_option);
                    $.each(data, function(index, get_students) {

                        $('#fname').append('<option value=' + get_students["id"] + '>' + get_students[
                            "name"] + '</option>');

                    });

                }
            })



        }








        function refreshTableAfterAdmissionYearLoad() {

            var recieved_voucher_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-recieve-voucher-list') }}",
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
                    console.log(data);
                }
            });




            //search functions
            $("#session").on('change', function(e) {

                recieved_voucher_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    recieved_voucher_list.draw();
                }
            });

            $("#search").on("click", function() {

                recieved_voucher_list.draw();

            })


            //form
            $('#voucher-insert').validate({
                errorPlacement: function(error, element) {
                    //  element[0].style.border = "1px solid red";
                },
                rules: {
                    for_the_month:"required",
                    account_name:"required",
                    class_id:"required",
                    student_id:"required",
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('update-recieve-voucher') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);
                            var student_id = $("#fname")[0].value;
                            var for_the_month = $("#for_the_month")[0].value;
                            var class_id = $("#class_id")[0].value;

                            $('#voucher-insert')[0].reset();
                            getHeads();
                            successAlert();
                            // sendMessage(data);
                            recieved_voucher_list.draw();

                            //  var key_name = "phone"+data["id"];

                            // localStorage.setItem(key_name, JSON.stringify(data["admissions"][0]["mobile_no"]));
                            // const user1Data = JSON.parse(localStorage.getItem('status'));

                            // $("#voucher_head_attach_id").val("");
                            // $('#voucher-head-attach-form')[0].reset();
                           
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {


                        }

                    })
                }
            });

        }


        // function sendMessage(data){
        //     const recipient = data["admissions"][0]["mobile_no"];
        //     const apiKey = 'CPeoWjSqCKVX';
        //     const message = 'Your voucher has been received against this id! #'+data["voucher_no"]+' Thank you for choosing Jamia Tazkeer Ul Quran';
        //     const url =`https://api.textmebot.com/send.php?recipient=${encodeURIComponent(recipient)}&apikey=${encodeURIComponent(apiKey)}&text=${encodeURIComponent(message)}&json=yes`;

        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: url,
        //             type: "POST",
        //             success: function(data) {
        //                 console.log(data);
        //             },
        //             error:function(data){
        //                 // removeLastItemFromLocalStorage();
        //             }


        //         })


        // }


        // localStorage.setItem('phone', JSON.stringify("+923441207218"));
        //                     const user1Data = JSON.parse(localStorage.getItem('status'));



        // function processLocalStorageData() {

        //     const data = localStorage.length;
        //     const recipient = data;
        //     const apiKey = 'CPeoWjSqCKVX';
        //     const message = 'This is a test';
        //     console.log(data);
        //     if (data>0) {

        //         const url =
        //             `https://api.textmebot.com/send.php?recipient=${encodeURIComponent(recipient)}&apikey=${encodeURIComponent(apiKey)}&text=${encodeURIComponent(message)}&json=yes`;

        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: url,
        //             type: "POST",
        //             success: function(data) {
        //                 console.log(data);
        //             },
        //             error:function(data){
        //                 removeLastItemFromLocalStorage();
        //             }


        //         })

        //     }
        // }

        // Call the function immediately
        // processLocalStorageData();

        // // Set an interval to call the function every 15 seconds
        // setInterval(processLocalStorageData, 5000); // 15 seconds = 15000 milliseconds

        // function removeLastItemFromLocalStorage() {
        //     // Retrieve the keys from localStorage
        //     const keys = Object.keys(localStorage);

        //     if (keys.length === 0) {
        //         console.log('localStorage is empty, nothing to remove.');
        //         return;
        //     }

        //     // Find the last key
        //     const lastKey = keys[keys.length - 1];

        //     // Remove the key and its associated value
        //     localStorage.removeItem(lastKey);

        //     console.log(`Removed key '${lastKey}' from localStorage.`);
        // }

        // Call the function to remove the last key-value pair

        // removeLastItemFromLocalStorage();







        $(document).on("click", ".unrecieved_voucher", function() {

            var id = $(this).data("id");
            var element = this;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('reverse-fee-voucher') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $(element).parent().parent().parent().parent().fadeOut();

                }
            })

        })







        $(document).on("click", ".delete_add_item", function() {

            var id = $(this).data("id");

            var element = this;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-add-item') }}",
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
    </script>
@endsection
