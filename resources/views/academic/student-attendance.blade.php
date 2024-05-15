@extends('layout.structure')

@section('content')
    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                <h6 class="m-0 font-weight-bold text-primary">سٹاف لسٹ</h6>
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
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="سٹاف سرچ کریں۔۔۔۔۔۔۔۔۔">
                    </div>

                    <div class="d-flex justify-content-center ">
                        <div class="w-50 d-flex">
                            <button onclick="checkAttendance()" class="btn btn-danger mb-2 mr-2"> حاضری&nbsp;لگائیں</button>
                        <input type="date" onchange="getStudents()" id="date" class="form-control w-50 mr-2">
                        <select name="class" id="class" class="form-control w-50 mr-2" onchange="getStudents()">
                            <option value="">کلاس منتخب کریں</option>
                        </select>
                        <select name="department_id" id="department_id" class="form-control"
                                     onchange="getClasses()">

                                 </select>
                        </div>
                        

                    </div>
                    <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <th>حاضری کی قسم</th>
                                <th>نام</th>
                                <th>رول نمبر</th>

                            </tr>

                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>



        
function getClasses(edit_class_id) {

var id = $("#department_id")[0].value;

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
        create_first_option.innerText = "کلاس منتخب کریں";
        class_parent.appendChild(create_first_option);

        $(data).each(function(index, element) {
            var create_option = document.createElement("option");
            create_option.innerText = element["class"];
            create_option.value = element["id"];
            if (element["id"] == edit_class_id) {
                create_option.selected = true;
            }
            class_parent.appendChild(create_option);
        });

    }
})

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

    }
})

}

getDepartmentList();



        function refreshTableAfterAdmissionYearLoad() {

            //this function is only define because its code in footer and error is occured due to not defined

        }


        function getStudents() {

           
            if($("#class").val() !== "" && $("#date").val() !== ""){
                
            

            

            var employee_salary_table = $('.table_employee_other').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                order: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },
                ajax: {
                    url: "{{ url('student-list-attendence') }}",
                    data: function(d) {
                        d.date = $("#date").val();
                        d.session = $("#session").val();
                        d.class = $("#class").val();
                    }
                },
                columns: [{

                        data: 'attendance_type',
                        name: 'attendance_type'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'roll_no',
                        name: 'roll_no'
                    }
                
                ],
                success: function(data) {
                    console.log(data);
                }
            });

            employee_salary_table.destroy();

        }

    }


        $("#search").keyup(function() {

            var value = this.value.toLowerCase().trim();

            $(".table_employee_other tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });





        function checkAttendance() {

            var confirm_attendance = confirm("کیا آپ حاضری لگانا چاہتے ہیں");

            if (confirm_attendance) {


                // Get all checkboxes by their class or unique id
                const checkboxes = document.querySelectorAll('.attendence_type'); // by class
                // const checkboxes = document.querySelectorAll('input[type="checkbox"]'); // by type

                // Create an array to store the checked values
                const checkedValues = [];
                const updateCheckedValue = [];

                // Iterate through the checkboxes and add the checked values to the array
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {

                        var today_attendance_date = $("#date").val();
                        var class_id = $("#class").val();
                        const dataId = checkbox.getAttribute('data-id');


                        var date_get_for_time = new Date(); // Current date and time

                        var options = {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            fractionalSecondDigits: 3,
                        };

                        var formattedTime = new Intl.DateTimeFormat('en-US', options).format(date_get_for_time);




                        if (dataId) {

                            checkedValues.push({
                                student_id: dataId,
                                date: today_attendance_date,
                                time_in:formattedTime,
                                attendance_type: checkbox.value
                            });
                        }

                        const updateId = checkbox.getAttribute('data-update');

                        if (updateId) {

                            updateCheckedValue.push({
                                student_id: updateId,
                                attendance_type: checkbox.value
                            });


                        }


                    }

                });

                const insert_update_attendance = {
                    insert_attendance: checkedValues,
                    update_attendance: updateCheckedValue
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("student-attendence-insert") }}',
                    data: JSON.stringify(insert_update_attendance),
                    contentType: 'application/json',
                    success: function(response) {

                        getStudents();
                        successAlert();

                    },
                    error: function() {
                        console.error('AJAX request failed.');
                    }
                });


            }



        }
    </script>
@endsection
