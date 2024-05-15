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
                        <div class="mb-3 d-flex">

                            
                            <input type="text" class="form-control mr-2" id="search_value" name="search_value"
                                placeholder="سرچ کریں">

                                <select name="class_id_search" id="class_id_search"  class="form-control toselect-tag" >
                                    <option value="">درجہ منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class . "(". $class->getDepartments->department.")"}}</option>
                                    @endforeach
                                  </select>
    
                        </div>
                        <table class="table table-bordered get-list-of-admissions" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> رقم </th>
                                    <th> کلاس </th>
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
                    <h6 class="m-0 font-weight-bold text-primary"> ہیڈ بنائیں فارم</h6>
                </div>
                <div class="card-body">
                    <form id="voucher-head-attach-form">

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">درجہ</label>
                                <select name="class_id" id="class_id" class="form-control toselect-tag">
                                    <option value="">درجہ منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class . "(". $class->getDepartments->department.")" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">ہیڈ </label>
                                <select name="head_id" id="head_id" class="form-control toselect-tag"
                                    onchange="removeBorder(this)">
                                    <option value="">ہیڈز منتخب کریں</option>
                                    @foreach ($heads as $head)
                                        <option value="{{ $head->id }}">{{ $head->head }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> رقم </label>
                                <input type="number" class="form-control" id="amount" name="amount"
                                    onkeydown="removeBorder(this)">
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="voucher_head_attach_id" id="voucher_head_attach_id">
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



      

        function refreshTableAfterAdmissionYearLoad() {

            var voucher_head_attach_list = $('.get-list-of-admissions').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('attach-voucher-head-list') }}",
                    data: function(d) {
                        d.search_value = $("#search_value").val();
                        d.session = $("#session").val();
                        d.class_id_search = $("#class_id_search").val();
                    }
                },

                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                        data: 'head',
                        name: 'head'
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


            $("#class_id_search").on('change', function(e) {

            voucher_head_attach_list.draw();

            });


            


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    voucher_head_attach_list.draw();
                }
            });


            //form
            $('#voucher-head-attach-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    class_id: "required",
                    head_id: "required",
                    amount: "required"
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);


                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-head-attach-amount') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                               console.log(data);
                            $("#voucher_head_attach_id").val("");
                            $('#voucher-head-attach-form')[0].reset();
                            voucher_head_attach_list.draw();
                            successAlert();
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {


                        }

                    })
                }
            });



            

        }



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
                    $("#select2-head_id-container")[0].innerText = data["get_head"]["head"] ;
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
