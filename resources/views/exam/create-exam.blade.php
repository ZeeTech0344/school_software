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
                           
                        </div>
                        <table class="table table-bordered get-list-of-exam" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> امتحان کا نام </th>
                                 
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
                    <h6 class="m-0 font-weight-bold text-primary">امتحان فارم</h6>
                </div>
                <div class="card-body">
                    <form id="exam-form">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> نام </label>
                                <input type="text" name="exam_name" id="exam_name" class="form-control">
                            </div>
                         </div>

                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="hidden_id" id="hidden_id">

                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>

@endsection




@section('script')
    <script>
        
        function refreshTableAfterAdmissionYearLoad() {

            var list = $('.get-list-of-exam').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-exam-list') }}",
                    data: function(d) {
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
                        data: 'exam_name',
                        name: 'exam_name'
                    }
                ],

                success: function(data) {
                    console.log(data);
                }
            });

            // fine_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    list.draw();
                }
            });


            //form
            $('#exam-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    exam_name: "required",
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-exam') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                           
                           console.log(data);
                            $("#hidden_id").val("");
                            $('#exam-form')[0].reset();
            
                            list.draw();
                            successAlert();
                          
                        },
                        error: function(data) {
                          

                        }

                    })
                }
            });



            

        }



        $(document).on("click", ".edit_exam", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-exam-name') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    
                    $("#exam_name").val(data["exam_name"]);
                    $("#hidden_id").val(data["id"]);
                   
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

        // Set to true if in edit mode, false otherwise

        // Set the multiple option based on the edit state
        
        function selectTwoMultiple(multiple = true){
           console.log(multiple);
            $('.multiple').select2({

           multiple: multiple,
           theme: "classic"
    });
}


selectTwoMultiple();
       


    </script>
@endsection
