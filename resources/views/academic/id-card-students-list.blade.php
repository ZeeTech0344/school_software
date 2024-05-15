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
                                <button onclick="sendData()" class="btn btn-success mb-2 mr-2">کارڈ بنائیں </button>
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

                                    

                                    <select name="class_id" id="class_id" class="form-control toselect-tag">
                                        <option value="">کلاس منتخب کریں</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class."(".$class->getDepartments->department.")" }}</option>
                                        @endforeach
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
                                    <th>کلاس</th>
                                    <th> والد کا نام </th>
                                    <th> نام </th>
                                    <th> رجسٹر نمبر </th>
                                    <th> منتخب </th>
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
                    url: "{{ url('/id-cards/student-list/data') }}",
                    data: function(d) {
                        d.search_value = $("#search_value").val();
                        d.session_id = $("#session").val();
                        d.class_id = $("#class_id").val();
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
                        data: 'class',
                        name: 'class'
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

                if ($("#class_id")[0].value !== "") {
                    voucher_head_attach_list.draw();
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




        function sendData() {

            var checkboxes = $('input[type="checkbox"]:checked');
            var checkboxValues = checkboxes.map(function() {
                return this.value;
            }).get();


            var class_id_get =  $("#class_id")[0].value;
            var session_id_get =  $("#session")[0].value;
            var section_get =  $("#section")[0].value;
            var data = {
                checkboxValues: checkboxValues,
                class_id :class_id_get,
                session_id :session_id_get,
                section :section_get
            }

          

            sendAjax(data);
        }

        function sendAjax(data) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url("id-cards") }}', // Replace with your server endpoint
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {

                    //console.log(response);

                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(response);
                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 500); 

                },
                error: function() {
                    console.error('AJAX request failed.');
                }
            });

        }









        function printContentViaAjax(student_id_get, session_id_get, for_the_month_get, class_id_get) {

            console.log(student_id_get, session_id_get, for_the_month_get, class_id_get);

            $.ajax({
                url: "{{ url('get-voucher-print') }}" + "/" + class_id_get + "/" + session_id_get + "/" +
                    for_the_month_get + "/" + student_id_get,
                type: 'GET',
                success: function(response) {
                    // Open a new window or tab and set the content
                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(response);

                    // Print the content
                    printWindow.print();

                    // Close the window after printing or if the user cancels the print
                    setTimeout(function() {
                        printWindow.close();
                    }, 50);
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
