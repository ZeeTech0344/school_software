@extends('layout.structure')

@section('content')
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}



        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">قربانی لسٹ</h6>
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
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value"
                                placeholder="سرچ کریں">
                        </div>
                        <table class="table table-bordered get-list-of-admissions" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> ریمارکس </th>
                                    <th> رقم </th>
                                    <th> حصہ </th>
                                    <th>قربانی</th>
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
                    <h6 class="m-0 font-weight-bold text-primary">قربانی فارم</h6>
                </div>
                <div class="card-body">
                    <form id="qurbani-form">

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">نام</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">پتہ</label>
                                <input type="text" class="form-control" id="address" name="address"
                                onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">رابطہ نمبر</label>
                                <input type="text" class="form-control" id="phone_no" name="phone_no"
                                onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">قربانی</label>
                                <select name="qurbani" id="qurbani" class="form-control" onchange="getQurbaniData()">
                                    <option value="">قربانی منتخب کریں</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col ">
                                    <input type="text" name="qurbani_rate" id="qurbani_rate" class="mt-1 text-center" readonly>
                                <input type="text" name="qurbani_hissa" id="qurbani_hissa" class="mt-1 text-center" readonly>
                                <input type="hidden" name="hidden_qurbani_rate" id="hidden_qurbani_rate" class="mt-1 text-center" readonly>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col ">
                                    <input type="text" name="total_hissa_recieved" id="total_hissa_recieved" class="mt-1 text-center" readonly>
                                    <input type="text" name="amount_recieved" id="amount_recieved" class="mt-1 text-center" readonly>
                                </div>
                        </div>


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">ٹوٹل حصہ</label>
                                <input type="number" class="form-control" id="total_parts" name="total_parts" onkeyup="calculate(this)"
                                onkeydown="removeBorder(this)">
                                <input type="number" id="total_amount" name="total_amount" class="mt-1 text-center" 
                                onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">ریمارکس</label>
                                <input type="text" class="form-control" id="remarks" name="remarks"
                                onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="qurbani_hidden_id" id="qurbani_hidden_id">
                        <input type="hidden" name="total_hissa" id="total_hissa">
                        <input type="hidden" name="calculate_total_hissa" id="calculate_total_hissa">

                        <input type="hidden" name="edit_total_hissa" id="edit_total_hissa">

                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection




@section('script')
    <script>

        function calculate(e){

            var total_amount_calculate = e.value*$("#hidden_qurbani_rate").val();
            var total_hissa = $("#total_hissa").val();

            var edit_total_hissa = $("#edit_total_hissa").val();


            $("#total_amount").val(parseFloat(total_amount_calculate.toFixed(2)));
            $("#calculate_total_hissa").val((parseInt(total_hissa)+parseInt(e.value)) - edit_total_hissa);

        }

        function getClasses(edit_class_id, edit_student_id) {

            var id = $("#department_id")[0].value;

        

            $.ajax({
                url: "{{ url('get-classes-department-wise') }}",
                type: "get",
                data: {
                    id: id
                },
                success: function(data) {

                    var class_parent = $("#class_id")[0];
                    class_parent.innerHTML = "";
                    var create_first_option = document.createElement("option");
                    create_first_option.innerText = "کلاس منتخب کریں";
                    class_parent.appendChild(create_first_option);

                    $(data).each(function(index, element) {
                        var create_option = document.createElement("option");
                        create_option.innerText = element["class"];
                        create_option.value = element["id"];
                        if (element["id"] == edit_class_id) {

                            create_option.selected=true;

                        }
                        class_parent.appendChild(create_option);
                    });

                    getStudents(edit_student_id);

                }
            })

        }





        function getQurbaniList(select_edit){
            $.ajax({
                url: "{{ url('get-all-qurbani') }}",
                type: "get",
                success: function(data) {
                  
                    var select = $('#qurbani');
                    // Iterate over the data array using jQuery's $.each function
                    $.each(data, function(index, data) {
                    // Create a new option element



                    if(data["id"] == select_edit){
                      
                    var option = $('<option selected></option>');
                    // Set the value and text of the option
                    option.val(data["id"]);
                    option.text(data["qurbani_name"]);

                    }else{
                        var option = $('<option></option>');
                    // Set the value and text of the option
                    option.val(data["id"]);
                    option.text(data["qurbani_name"]);
                    }
                
                    // Append the option to the select element
                    select.append(option);
                    });

                }
            })

        }

        getQurbaniList();
          

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



        var qurbani_list;

        function refreshTableAfterAdmissionYearLoad() {

            var qurbani_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-all-data-qurbani') }}",
                    data: function(d) {
                        d.search_value = $("#search_value").val();
                        d.session = $("#session").val();
                    }
                },

                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'total_parts_amount',
                        name: 'total_parts_amount'
                    },
                    {
                        data: 'total_parts',
                        name: 'total_parts'
                    },
                    {
                        data: 'qurbani_id',
                        name: 'qurbani_id'
                    }
                ],

                success: function(data) {
                 
                }
            });


            // qurbani_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                qurbani_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    qurbani_list.draw();
                }
            });


            //form
            $('#qurbani-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    qurbani: "required",
                    total_parts: "required",
                    full_name: "required",
                    address: "required",
                    phone_no: "required",
                    remarks: "required",
                },

            submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);


                  
                    if($("#calculate_total_hissa").val() !=="" && $("#calculate_total_hissa").val()<=7){

                        $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-qurbani-parts-data') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            
                            $('#qurbani-form')[0].reset();
                            $("#qurbani_hidden_id").val("");
                            $("#total_hissa").val("");
                            $("#calculate_total_hissa").val("");
                            $('#edit_total_hissa').val("");

                            qurbani_list.draw();
                            successAlert();
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {

                       

                        }

                    })

                    }else{
                        alert("قربانی کے حصہ مکمل ہو گئے");
                    }

                   
                }
            });

        }



        // $("#qurbani").on("change", function(){

            function getQurbaniData(edit_qurbani, total_parts=0){

            var get_qurbani_id = $("#qurbani").val();

            if(get_qurbani_id !== "" || edit_qurbani !== undefined ){

                if(edit_qurbani !== undefined){
                    get_qurbani_id = edit_qurbani;
                }

                $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                    url:"{{ url('get-qurbani-data') }}",
                    type:"POST",
                    data:{qurbani_id:get_qurbani_id},
                    success:function(data){

                        $("#total_hissa").val(data[1]-total_parts);

                        $("#amount_recieved").val( "ملا گیا حصہ:" + data[1]);
                        $("#total_hissa_recieved").val( "ملے ہوۓ حصہ کی رقم: " + parseFloat(data[2].toFixed(2)));

                        var amount = data[0]["amount"];
                        var part_rate = data[0]["amount"]/7;

                        $("#qurbani_hissa").val("قربانی حصہ:" + parseFloat(part_rate.toFixed(2)));
                        $("#qurbani_rate").val("ٹوٹل رقم:" + amount);
                        $("#hidden_qurbani_rate").val(parseFloat(part_rate.toFixed(2)));

                    }
            })

            }

            }
            



        $(document).on("click", ".edit-qurbani-parts-data", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-qurbani-parts-data') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    getQurbaniData(data["qurbani_id"],data["total_parts"]);
                    $("#total_parts").val(data["total_parts"]);
                    $("#remarks").val(data["remarks"]);
                    $("#hidden_id").val(data["id"]);
                    $("#qurbani").val(data["qurbani_id"]);
                   
                    $("#qurbani_hidden_id").val(data["id"]);

                }
            })

        })







        $(document).on("click", ".delete-qurbani-parts-data", function() {

            var id = $(this).data("id");

            console.log(id);

            var element = this;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-qurbani-parts') }}",
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



        function getStudents(edit_student_id) {

            var class_tag = $("#class_id")[0].style.border = "";
            var class_id = $("#class_id")[0].value;
            var session_id = $("#session")[0].value;

            $.ajax({
                url: "{{ url('get-student-class-wise') }}",
                type: "get",
                data: {
                    class_id: class_id,
                    session_id: session_id
                },
                success: function(data) {

                    var class_parent = $("#student")[0];

                    class_parent.innerHTML = "";
                    $(data).each(function(index, element) {
                        var create_option = document.createElement("option");
                        create_option.innerText = element["name"] + "(" + element["roll_no"] + ")";
                        create_option.value = element["id"];

                        if (edit_student_id == element["id"]) {
                            create_option.selected = true;
                        }
                        class_parent.appendChild(create_option);
                    });



                }
            })

        }


        $(".toselect-tag").select2();
    </script>
@endsection
