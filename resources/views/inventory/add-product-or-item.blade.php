
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Add Product/Items</h6>
                </div>
                <div class="card-body">
                    <form id="add-product-form">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control" id="product" name="product">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Status</label>
                            <select name="status" id="status" class="form-control">
                              
                                <option>On</option>
                                <option>Off</option>
                            </select>
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="hidden_id" id="hidden_id">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Vendors List</h6>
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
                                    <th>Product</th>
                                    <th>Status</th>
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
                url: "{{ url('get-list-product-or-item') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val()
                }
            },
            columns: [
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'status',
                    name: 'status'
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







        $('#add-product-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                name: "required",
                // type: "required",
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-product-or-item') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);
                         $('#add-product-form')[0].reset();
                         product_list.draw();
                        $("#hidden_id").val("");
                    },
                    error: function(data) {


                    }

                })
            }
        });










        $(document).on("click", ".edit_product", function() {

            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-list-product-or-item') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#product").val(data["product"]);
                    $("#status").val(data["status"]);
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
    </script>
