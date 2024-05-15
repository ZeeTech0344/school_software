
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Add Inventory</h6>
                </div>
                <div class="card-body">
                    <form id="add-inventory-form">


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Select Option</label>
                            <select name="option" id="option" class="form-control">
                                <option>New Item</option>
                                <option>Return Item</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">product</label>
                            <select name="product_id" id="product_id" class="form-control toselect-tag">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount">
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Remarks</label>
                            <input type="text" class="form-control" id="remarks" name="remarks">
                        </div>

                        
                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="hidden_id" id="hidden_id">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Inventory List</h6>
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
                                placeholder="Type here to search........">
                        </div>
                        <table class="table table-bordered employee_front_table" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Option</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>T_Amount</th>
                                    <th>Remarks</th>
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

    <script>
        var product_list = $('.employee_front_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-inventory-list') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val()
                }
            },
            columns: [
                {
                    data: 'option',
                    name: 'option'
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 't_amount',
                    name: 't_amount'
                },
                {
                    data: 'remarks',
                    name: 'remarks'
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



        $("#search_value").on('keyup', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                product_list.draw();
            }
        });







        $('#add-inventory-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                product_id: "required",
                quantity: "required",
                total_amount: "required",
                remarks: "required",
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-inventory') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);
                         $('#add-inventory-form')[0].reset();
                         product_list.draw();
                         $("#hidden_id").val("");
                    },
                    error: function(data) {


                    }

                })
            }
        });










        $(document).on("click", ".edit_inventory", function() {

            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-inventory') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#product_id").val(data["product_id"]);
                    $("#quantity").val(data["quantity"]);
                    $("#total_amount").val(data["total_amount"]);
                    $("#remarks").val(data["remarks"]);

                    //because select2 selected when edit but not shown
                    get_inner_text = $('#product_id').find(":selected").text();
                    $("#select2-product_id-container")[0].innerText = get_inner_text;
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


        $(".toselect-tag").select2();
    </script>

