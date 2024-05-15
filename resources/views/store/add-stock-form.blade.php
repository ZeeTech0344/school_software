{{-- @extends('layout.structure')

@section('content') --}}

    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Add Stock</h6>
                </div>
                <div class="card-body">
                    <form id="add-stock-form">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Stock Date</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Vendors</label>
                            <select name="vendor_id" id="vendor_id" class="form-control toselect-tag"
                                onchange="changeProductVendorWise()">
                                <option value="">Select Vendors</option>
                                @foreach ($vendor as $get_data)
                                    <option value="{{ $get_data->id }}">{{ $get_data->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Items/Products</label>
                            <select name="item_id" id="item_id" class="form-control toselect-tag">
                                <option value="">Select Items/Products</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Weight/Nos</label>
                            <input type="text" name="weight" id="weight" onkeyup="calculateAmount(this)"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Measurement In</label>
                            <select name="weight_type" id="weight_type" class="form-control">
                                <option value="">Select Measurement</option>
                                <option>KG</option>
                                <option>Packets</option>
                                <option>Liter</option>
                                <option>Tin</option>
                                <option>Bottle</option>
                                <option>Gram</option>
                                <option>Bundle</option>
                                <option>Roll</option>
                                <option>Pet</option>
                                <option>Carton</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Current Rate</label>
                            <input type="text" name="current_rate" id="current_rate" class="form-control"
                                onkeyup="calculateAmount(this)">
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlInput1">Total Amount Calculate</label>
                            <input type="number" name="total_amount" readonly id="total_amount" class="form-control">
                        </div>





                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Add" id="submit_btn" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="stock_hidden_id" id="stock_hidden_id">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-8 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Stock List</h6>
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

                        <div class="mb-3 d-flex justify-content-between">
                            <input type="date" class="form-control mr-3" id="from_date" name="from_date"
                                onchange="checkVal(this)">
                            <input type="date" class="form-control" id="to_date" name="to_date"
                                onchange="checkVal(this)">
                        </div>

                        <div class="mb-3 d-flex justify-content-between">
                            
                            <select name="items" class="form-control toselect-tag" id="items" onchange="checkVal(this)">

                            </select>

                        </div>
                        
                        {{-- <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value"
                                placeholder="Type here to search........">
                        </div> --}}
                        <table class="table table-bordered employee_front_table" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Item</th>
                                    <th>Weight/Qty</th>
                                    <th>C_Rate</th>
                                    <th>Remaining</th>
                                    <th>T_Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- </div> --}}
    </div>
{{-- @endsection
@section('script') --}}
    <script>
        $(document).on("click", ".send_items", function() {

            var data = $(this).data("id");

            var url = "{{ url('send-stock') }}" + "/" + data;

            viewModal(url);

        })



        function calculateAmount() {

            var weight = $("#weight")[0].value;

            var current_rate = $("#current_rate")[0].value;

            var total_amount = $("#total_amount").val(weight * current_rate);

        }





        var get_stock_list = $('.employee_front_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-list-of-stock') }}",
                data: function(d) {
                    d.from_date = $("#from_date").val()
                    d.to_date = $("#to_date").val()
                    d.vendors = $("#vendors").val()
                    d.items = $("#items").val()
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },

                {
                    data: 'vendor',
                    name: 'vendor'
                },

                {
                    data: 'items',
                    name: 'items'
                },

                {
                    data: 'weight',
                    name: 'weight'
                },

                {
                    data: 'current_rate',
                    name: 'current_rate'
                },

                {
                    data: 'remaining_stock',
                    name: 'remaining_stock'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

            success: function(data) {
                console.log(data);
            }
        });


        function checkVal() {

            get_stock_list.draw();
        }



        function getItems() {


            var parent = $("#items")[0].innerHTML = "";

            // var vendor_id = $("#vendors")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-product-vendor-wise') }}",
                type: "GET",
                success: function(data) {

                    
                    var parent = $("#items")[0];

                    // var create_element

                    var select_option = document.createElement("option");
                    select_option.innerText = "Select Items/Products";
                    select_option.value = "";
                    parent.appendChild(select_option);
                    $.each(data, function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.value = value["id"];
                        create_option.innerText = value["item_name"] + " - " + value["get_vendors"]["name"];
                        parent.appendChild(create_option);
                    });

                    get_stock_list.draw();



                }
            })


        }

        getItems();




        // $("#search_value").on('keyup', function(e) {
        //     if (e.key === 'Enter' || e.keyCode === 13) {
        //         employee_front_table.draw();
        //     }
        // });

        var wait = false;

        $('#add-stock-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                date: "required",
                vendor_id: "required",
                item_id: "required",
                weight: "required",
                weight_type: "required",
                current_rate: "required",
                total_amount: "required",
            },

            submitHandler: function(form) {

                $("#submit_btn")[0].disabled = true;
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-stock') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);

                        $("form input[type='text'] , form input[type='number']").val("");

                        // $('#add-stock-form')[0].reset();
                        $("#submit_btn")[0].disabled = false;
                        get_stock_list.draw();
                        $("#stock_hidden_id").val("");
                    },
                    error: function(data) {


                    }

                })
            }
        });


        $(document).on("click", ".edit_stock", function() {

            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-stock') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#data").val(data["date"]);
                    $("#vendor_id").val(data["vendor_id"]);
                    $("#select2-vendor_id-container")[0].innerText = data["get_vendors"]["name"];
                    changeProductVendorWise(data["item_id"]);
                    $("#weight").val(data["weight"]);
                    $("#weight_type").val(data["weight_type"]);
                    $("#current_rate").val(data["current_rate"]);
                    $("#total_amount").val(data["total_amount"]);
                    $("#stock_hidden_id").val(data["id"]);

                }
            })

        })



        $(document).on("click", ".delete_stock", function() {

            var id = $(this).data("id");
            var element = this;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-stock') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $(element).parent().parent().parent().parent().fadeOut();

                }
            })

        })



        function changeProductVendorWise(get_product) {

            var parent = $("#item_id")[0].innerHTML = "";

            var vendor_id = $("#vendor_id")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-product-vendor-wise') }}",
                type: "GET",
                data: {
                    vendor_id: vendor_id
                },
                success: function(data) {
                    var parent = $("#item_id")[0];
                    $.each(data, function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.value = value["id"];
                        create_option.innerText = value["item_name"];
                        if (get_product == value["id"]) {
                            create_option.selected = true;
                        }
                        parent.appendChild(create_option);
                    });



                }
            })

        }



        $(".toselect-tag").select2();
    </script>
{{-- @endsection --}}
