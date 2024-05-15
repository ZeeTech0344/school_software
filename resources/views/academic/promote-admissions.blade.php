@extends('layout.structure')

@section('content')
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}



        <div class="col-lg-12 col-sm-12">
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
                        <div class="mb-3">

                            <div class="form-inline d-flex justify-content-center">
                                <button id="promote-student" class="btn btn-success mb-2 mr-2"> پروموٹ کریں </button>

                                <div class="form-group mx-sm-2 mb-2">

                                    <select name="promote_section" id="promote_section" class="form-control">
                                        <option value="">سیکشن منتخب کریں</option>
                                        @foreach ($sections as $section)
                                            <option>{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group mx-sm-1 mb-2 ml-0">

                                    <select name="promote_class_id" id="promote_class_id" class="form-control">
                                        <option value="">درجہ منتخب کریں</option>

                                    </select>
                                </div>


                                <div class="form-group mx-sm-1 mb-2 ml-0">
                                    <select name="promote_department_id" id="promote_department_id" class="form-control"
                                        onchange="getClasses(this)">

                                    </select>
                                </div>





                                <input type="button" id="search" class="btn btn-primary mb-2" value="سرچ کریں">



                                <div class="form-group mx-sm-2 mb-2">

                                    <select name="section" id="section" class="form-control">
                                        <option value="">سیکشن منتخب کریں</option>
                                        @foreach ($sections as $section)
                                            <option>{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group mx-sm-1 mb-2 ml-0">
                                    <select name="class" id="class" class="form-control"
                                        onchange="removeBorder(this)">
                                        <option value="">درجہ منتخب کریں</option>
                                    </select>
                                </div>


                                <div class="form-group mx-sm-1 mb-2 ml-0">
                                    <select name="department_id" id="department_id" class="form-control"
                                        onchange="getClasses(this)">

                                    </select>
                                </div>




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
                                    <th>سیکشن</th>
                                    <th>درجہ</th>
                                    <th>شعبہ</th>
                                    <th> والد کا نام </th>
                                    <th> نام </th>
                                    <th> رجسٹر نمبر </th>
                                    <th> چیک باکس </th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection




@section('script')
    <script>
        function getClasses(e) {

            if (e.id == "department_id") {

                var id = e.value;
                $.ajax({
                    url: "{{ url('get-classes-department-wise') }}",
                    type: "get",
                    data: {
                        id: id
                    },
                    success: function(data) {

                        var class_parent = $("#class")[0];
                        class_parent.innerHTML = "";
                        var create_first_option = document.createElement("option");
                        create_first_option.innerText = "درجہ منتخب کریں";
                        create_first_option.value = "";
                        class_parent.appendChild(create_first_option);

                        $(data).each(function(index, element) {
                            var create_option = document.createElement("option");
                            create_option.innerText = element["class"];
                            create_option.value = element["id"];
                            class_parent.appendChild(create_option);
                        });

                    }
                })


            } else if (e.id == "promote_department_id") {

                var id = e.value;
                $.ajax({
                    url: "{{ url('get-classes-department-wise') }}",
                    type: "get",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        var class_parent = $("#promote_class_id")[0];
                        class_parent.innerHTML = "";
                        var create_first_option = document.createElement("option");
                        create_first_option.innerText = "درجہ منتخب کریں";
                        create_first_option.value = "";
                        class_parent.appendChild(create_first_option);

                        $(data).each(function(index, element) {
                            var create_option = document.createElement("option");
                            create_option.innerText = element["class"];
                            create_option.value = element["id"];
                            class_parent.appendChild(create_option);
                        });

                    }
                })

            }

        }


        function getDepartmentList() {

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
                        departments.appendChild(create_option);
                    });



                    var promoted_departments = $("#promote_department_id")[0];
                    promoted_departments.innerHTML = "";
                    var create_first_option = document.createElement("option");
                    create_first_option.innerText = "شعبہ منتخب کریں";
                    promoted_departments.appendChild(create_first_option);


                    $(data).each(function(index, element) {
                        var create_option = document.createElement("option");
                        create_option.innerText = element["department"];
                        create_option.value = element["id"];
                        promoted_departments.appendChild(create_option);
                    });


                }
            })

        }

        getDepartmentList();



        function refreshTableAfterAdmissionYearLoad() {

            var voucher_head_attach_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('/promote-admissions/student-list') }}",
                    data: function(d) {
                        d.session_id = $("#session").val();
                        d.class_id = $("#class").val();
                        d.section = $("#section").val();
                    }
                },

                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'section',
                        name: 'section'
                    },

                    {
                        data: 'class',
                        name: 'class'
                    },

                    {
                        data: 'department',
                        name: 'department'
                    },

                    {
                        data: 'father_name',
                        name: 'father_name'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'register_no',
                        name: 'register_no'
                    },
                    {
                        data: 'check_box',
                        name: 'check_box'
                    }
                ],

                success: function(data) {
                    console.log(data);
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

            $("#search").on('click', function(e) {

                if ($("#class")[0].value !== "" && $("#department_id")[0].value !== "") {
                    voucher_head_attach_list.draw();
                }

            });



            $("#promote-student").on('click', function() {

                var previous_class_id = $("#class").val();
                var promote_class_id = $("#promote_class_id").val();
                var promote_section = $("#promote_section").val();

                var session_id = $("#session").val();

                var promoted_student = [];
                $('.promoted_student:checked').each(function() {
                    promoted_student.push($(this).val());
                });


                if (promoted_student.length > 0) {

                    var department = $('#department_id option:selected').text();
                    var class_name = $('#class option:selected').text();
                    var section_name = $('#section option:selected').text();

                    var promote_department = $('#promote_department_id option:selected').text();
                    var promote_class_name = $('#promote_class_id option:selected').text();
                    var promote_section_name = $('#promote_section_name option:selected').text();

                    if ($("#department_id").val() !== "" && $("#class").val() !== "" &&
                        $("#promote_department_id").val() !== "" && $("#promote_class_id").val() !== "") {


                        if ($("#department_id").val() !== $("#promote_department_id").val() || $("#class").val() !==
                            $("#promote_class_id").val()) {

                            var confirm_promotion = confirm("کیا آپ اس بات کی تصدیق کرتے ہیں" + class_name + " " +
                                department + " کو" + promote_class_name + " " + promote_department + " " +
                                "پروموٹ کرنے کی؟نوٹ اگر ایک دفعہ کلاس پروموٹ ہو گی اس کو دوبارہ پروموٹ نہیں کر سکتے"
                                );

                            if (confirm_promotion) {

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ url('promote-admissions/student-list/promoted') }}",
                                    type: "POST",
                                    data: {
                                        previous_class_id: previous_class_id,
                                        class_id: promote_class_id,
                                        section: promote_section,
                                        promoted_student: promoted_student,
                                        session_id: session_id
                                    },
                                    success: function(data) {

                                        voucher_head_attach_list.draw();
                                        successAlert();
                                    }
                                })

                            }

                        } else {
                            alert("آپ ایک جیسی کلاس کو پروموٹ نہیں کر سکتے");
                        }


                    } else {
                        alert("Please select all fields");
                    }


                }else{
                    alert("Too low strength!");
                }



            });

        }


        $(document).ready(function() {
            $('#search_value').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();

                $('#dataTable tr:gt(0)').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.includes(searchText));
                });
            });
        });




        // function promoteStudent() {

        // }



        $(document).on("click", ".edit_voucher_head_attach", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-attach-voucher-head') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    console.log(data);

                    $("#class_id").val(data["class_id"]);
                    $("#head_id").val(data["head_id"]);
                    $("#select2-head_id-container")[0].innerText = data["get_head"]["head"];
                    $("#amount").val(data["amount"]);
                    $("#voucher_head_attach_id").val(data["id"]);


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
