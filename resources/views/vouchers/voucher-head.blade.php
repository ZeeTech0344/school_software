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
                        <table class="table table-bordered get-list-of-admissions" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> ہیڈ </th>
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
                    <h6 class="m-0 font-weight-bold text-primary">ہیڈ فارم</h6>
                </div>
                <div class="card-body">
                    <form id="voucher-head-form">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> ہیڈ کا نام </label>
                                <input type="text" class="form-control" id="head" name="head"
                                    onkeydown="removeBorder(this)">
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

            var voucher_head_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-voucher-head-list') }}",
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
                        data: 'head',
                        name: 'head'
                    }
                ],

                success: function(data) {
                    console.log(data);
                }
            });


            // fine_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                voucher_head_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    voucher_head_list.draw();
                }
            });


            //form
            $('#voucher-head-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    head: "required"
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-head') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);
                            //    console.log(data);
                            $("#voucher_hidden_id").val("");
                            $('#voucher-head-form')[0].reset();
                            $('#voucher-voucher_hidden_id-form').val("");
                            successAlert();
                            
                            voucher_head_list.draw();
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {
                          

                        }

                    })
                }
            });



            

        }



        $(document).on("click", ".edit_voucher_head", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-voucher-head') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    var class_tag = $("#head").val(data["head"]);
                    var fine_amount = $("#voucher_hidden_id").val(data["id"]);
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



function getStudents(student_id) {

    console.log(student_id);

var class_tag = $("#class")[0].style.border = "";


var class_id = $("#class")[0].value;
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
           
            if(student_id == element["id"]){
                create_option.selected=true;
            }
            class_parent.appendChild(create_option);
        });



    }
})

}


        $(".toselect-tag").select2();
    </script>
@endsection
