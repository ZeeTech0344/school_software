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
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value"
                                placeholder="سرچ کریں">
                        </div>
                        <table class="table table-bordered get-list-of-classes" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> ڈیپارٹمینٹ </th>
                                    <th> درجہ </th>
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
                    <h6 class="m-0 font-weight-bold text-primary">شعبہ فارم</h6>
                </div>
                <div class="card-body">
                    <form id="add-classes-form">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> شعبہ </label>
                                <select name="department_id" id="department_id" class="form-control">
                                  
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> درجہ </label>
                                <input type="text" name="class" id="class" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> سٹیٹس </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">آن</option>
                                    <option value="0">آف</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="class_hidden_id" id="class_hidden_id">
                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection




@section('script')
    <script>
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



        // var fine_list;

        function refreshTableAfterAdmissionYearLoad() {

            var class_list = $('.get-list-of-classes').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-classes') }}",
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
                        data: 'department_id',
                        name: 'department_id'
                    },

                    {
                        data: 'class',
                        name: 'class'
                    }
                ],

                success: function(data) {
                    console.log(data);
                }
            });


            // fine_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                class_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    class_list.draw();
                }
            });


            //form
            $('#add-classes-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    department_id: "required",
                    class: "required"
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-classes') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);
                            //    console.log(data);
                            $("#department_hidden_id").val("");
                             $('#add-classes-form')[0].reset();
                             successAlert();
                            // $('#voucher-voucher_hidden_id-form').val("");
                            
                            class_list.draw();
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {
                          

                        }

                    })
                }
            });



            

        }



        $(document).on("click", ".edit_classes", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-classes') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                     $("#department_id").val(data["department_id"]);
                     $("#class").val(data["class"]);
                     $("#class_hidden_id").val(data["id"]);
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



function getDepartment(student_id) {

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

getDepartment();




$(".toselect-tag").select2();
    </script>
@endsection
