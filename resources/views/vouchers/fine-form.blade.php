@extends('layout.structure')

@section('content')
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}



        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">داخلوں کی لسٹ</h6>
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
                                    <th> مہینہ </th>
                                    <th> جرمانہ </th>
                                    <th> کلاس </th>
                                    <th>نام</th>
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
                    <h6 class="m-0 font-weight-bold text-primary">جر مانہ فارم</h6>
                </div>
                <div class="card-body">
                    <form id="add-fine-form">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">شعبہ </label>
                                <select name="department_id" id="department_id" class="form-control"
                                    onchange="getClasses()">

                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">درجہ</label>
                                <select name="class_id" id="class_id" class="form-control" >

                                </select>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">سیکشن</label>
                                <select name="section" id="section" class="form-control" onchange="getStudents()">
                                    <option value="">سیکشن منتخب کریں</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section }}">{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                      


                        <div class="row">

                            <div class="col">
                                <label for="exampleFormControlInput1">طالب علم</label>
                                <select name="student_id" id="student" class="form-control toselect-tag"
                                    onchange="removeBorder(this)">
                                    <option value="">Select Students</option>

                                </select>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> جرمانہ </label>
                                <input type="number" class="form-control" id="fine_amount" name="fine_amount"
                                    onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> مہینہ </label>
                                <input type="month" class="form-control" id="month" name="month"
                                    onchange="removeBorder(this)">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> ریمارکس </label>
                                <input type="text" class="form-control" id="remarks" name="remarks"
                                    onkeydown="removeBorder(this)">
                            </div>
                        </div>





                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="fine_hidden_id" id="fine_hidden_id">
                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection




@section('script')
    <script>
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




        function getDepartmentList(edit_department, edit_class_id, edit_student_id) {

            $.ajax({
                url: "{{ url('get-department-list') }}",
                type: "get",

                success: function(data) {



                    var departments = $("#department_id")[0];
                    departments.innerHTML = "";
                    var create_first_option = document.createElement("option");
                    create_first_option.innerText = "شعبہ منتخب کریں";
                    departments.appendChild(create_first_option);

                    $(data).each(function(index, element) {
                        var create_option = document.createElement("option");
                        create_option.innerText = element["department"];
                        create_option.value = element["id"];
                        if (edit_department == element["id"]) {
                            create_option.selected = true;
                            
                        }
                        departments.appendChild(create_option);
                    });

                    //send student_id to getStudent function due to work flow of code
                    getClasses(edit_class_id, edit_student_id);
                   

                }
            })

        }

        getDepartmentList();



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



        var fine_list;

        function refreshTableAfterAdmissionYearLoad() {

            var fine_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-list-of-fine') }}",
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
                        data: 'month',
                        name: 'months'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'class',
                        name: 'class'
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


            // fine_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                fine_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    fine_list.draw();
                }
            });


            //form
            $('#add-fine-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    class_id: "required",
                    student_id: "required",
                    fine_amount: "required",
                    month: "required",
                    remarks: "required",
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);


                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-fine') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            //    console.log(data);
                            $("#fine_hidden_id").val("");
                            $('#add-fine-form')[0].reset();

                            fine_list.draw();
                            successAlert();
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {


                        }

                    })
                }
            });





        }



        $(document).on("click", ".edit_fine", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-fine') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    getDepartmentList(data["class"]["department_id"], data["class_id"], data["student_id"]);
                    $("#fine_amount").val(data["fine_amount"]);
                    $("#month").val(data["for_the_month"]);
                    $("#remarks").val(data["remarks"]);
                    $("#fine_hidden_id").val(data["id"]);
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



        function getStudents(edit_student_id) {

            var class_tag = $("#class_id")[0].style.border = "";
            var class_id = $("#class_id")[0].value;
            var section = $("#section")[0].value;
            console.log(class_id);
            var session_id = $("#session")[0].value;

            $.ajax({
                url: "{{ url('get-student-class-wise') }}",
                type: "get",
                data: {
                    class_id: class_id,
                    section: section,
                    session: session_id
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
