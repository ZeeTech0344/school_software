
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Send Inventory</h6>
                </div>
                <div class="card-body">
                    <form id="send-inventory-form">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">product</label>
                        <select name="branch"  class="form-control mr-3" id="branch">
                            <option value="">Select Branch</option>
                            <option>New City</option>
                            <option>Basti</option>
                            <option>Taxila</option>
                            <option>Attock</option>
                            <option>Pindi</option>
                            <option>Chakwal</option>
                            <option>Head Office</option>
                        </select>
                    </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">product</label>
                            <select name="product_id" id="product_id" class="form-control toselect-tag" onchange="getProductQty()">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Current Quantity</label>
                            <input type="number" class="form-control" id="current_qty" disabled>
                            <input type="hidden" class="form-control" id="current_qty_hidden" disabled>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Send Quantity</label>
                            <input type="number" class="form-control" id="send_qty" name="send_qty" onkeyup="calculateQty()">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Remaining Quantity</label>
                            <input type="number" class="form-control" id="remaining_quantity" disabled>
                        </div>
                        

                        
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Remarks</label>
                            <input type="text" class="form-control" id="remarks" name="remarks">
                        </div>

                        
                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Send" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="hidden_id" id="hidden_id">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Send Inventory List</h6>
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
                                    <th>Branch</th>
                                    <th>Product</th>
                                    <th>Send_Qty</th>
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
        

        function getProductQty(edit_qty=0){
           
            var product_id = $("#product_id")[0].value;

            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('get-qty-inventory-product') }}",
                    type: "GET",
                    data:{product_id:product_id, edit_qty:edit_qty},
                    success: function(sum) {
                        $("#current_qty").val(sum);
                        $("#current_qty_hidden").val(sum);
                    },
                   

                })
        }



        function calculateQty(){

            
                var current_qty_hidden = $("#current_qty_hidden")[0].value;
                var send_quantity = $("#send_qty")[0].value;
                $("#remaining_quantity").val(current_qty_hidden - send_quantity);
           
                if($("#remaining_quantity")[0].value<0){
                    $("#remaining_quantity")[0].style.border="1px solid red";
                }else{
                    $("#remaining_quantity")[0].style.border="";
                }
           
        }



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
                url: "{{ url('send-inventory-product-list') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val()
                }
            },
            columns: [
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'send_qty',
                    name: 'send_qty'
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







        $('#send-inventory-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                branch: "required",
                send_quantity: "required",
                product_id: "required"
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('send-inventory-product') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        // console.log(data);
                         $('#send-inventory-form')[0].reset();
                         product_list.draw();
                         $("#hidden_id").val("");
                    },
                    error: function(data) {


                    }

                })
            }
        });










        $(document).on("click", ".edit_send_inventory", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-send-inventory') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    console.log(data);

                    $("#branch").val(data["branch"]);
                    $("#product_id").val(data["product_id"]);
                    getProductQty(data["send_qty"]);
                    $("#send_qty").val(data["send_qty"]);
                    $("#remarks").val(data["remarks"]);
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
