{{-- @extends('layout.structure')

@section('content') --}}
<div class="col-12 d-flex justify-content-center">

    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">




            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Stock List</h6>
            </div>

            <div class="row p-4">
                <div class="col">
                    <input type="date" id="from_date" name="from_date" class="form-control" onchange="checkVal(this)">
                </div>
                <div class="col">
                    <input type="date" id="to_date" name="to_date" class="form-control" onchange="checkVal(this)">
                </div>

                <div class="col">
                    
                    <select name="vendors" class="form-control toselect-tag" id="vendors" onchange="checkVal(this)">
                    
                    </select>

                </div>

                <div class="col">
                            
                    <select name="items" class="form-control toselect-tag" id="items" onchange="checkItems(this)">

                    </select>

                </div>


                <div>
                    <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
                    <input type="button" value="PDF" class="btn btn-danger" id="get_pdf">
                    <input type="button" value="View" class="btn btn-primary" id="get_view">
                </div>
            </div>


            <div class="card-body">
                <div class="table-responsive">
                    {{-- <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value"
                                placeholder="Type here to search........">
                        </div> --}}
                    <table class="table table-bordered get_send_stock_list" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Item</th>
                                <th>Weight/Qty</th>
                                <th>Rate</th>
                                {{-- <th>Return</th>
                                <th>Send</th> --}}
                                <th>Remain_Stock</th>
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


    function getVendors(){

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('get-vendors') }}",
            type: "POST",
            success:function(data){

                var parent = $("#vendors")[0];

                    // var create_element

                var select_option = document.createElement("option");
                select_option.innerText = "Select Vendors";
                select_option.value = "";
                parent.appendChild(select_option);
                $.each(data, function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.value = value["id"];
                        create_option.innerText = value["name"];
                        parent.appendChild(create_option);
                });




               
            }
        })

    }
    getVendors();



    function calculateQty() {

        var current_stock = $("#current_stock")[0].value;
        var stock_send_qty = $("#stock_qty_send")[0].value;
        var remaining_stock = $("#remaining_stock").val(current_stock - stock_send_qty);
        if ($("#remaining_stock")[0].value < 0) {
            $("#remaining_stock")[0].style.border = "1px solid red";
        } else {
            $("#remaining_stock")[0].style.border = "";
        }

        var current_rate_check = $("#current_rate_check")[0].value;
        var stock_send_qty = $("#stock_qty_send")[0].value;


        $("#total_amount_get").val(current_rate_check * stock_send_qty);

    }


    var get_send_stock_list = $('.get_send_stock_list').DataTable({
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
                d.search_value = $("#search_value").val()
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

            // {
            //     data: 'return',
            //     name: 'return'
            // },

            // {
            //     data: 'send',
            //     name: 'send'
            // },

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




    function items() {
        get_send_stock_list.draw();

    }

    function checkVal(e){

        getItems(e.value);

       if($("#from_date")[0].value !== "" && $("#to_date")[0].value !== ""){
        get_send_stock_list.draw();
       }
        
    }


    function getItems(vendor_id) {

        console.log(vendor_id);

        var parent = $("#items")[0].innerHTML = "";

        // var vendor_id = $("#vendors")[0].value;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('get-product-vendor-wise') }}",
            type: "GET",
            data:{
                vendor_id:vendor_id
            },
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
                    create_option.innerText = value["item_name"];
                    parent.appendChild(create_option);
                });

            }
        })


    }

    getItems();


    function checkItems(){

        if($("#from_date")[0].value !== "" && $("#to_date")[0].value !== ""){
        get_send_stock_list.draw();
       }
    }



    function reset() {

        $("#from_date").val("");
        $("#to_date").val("");
       
        $("#items").val("");
        get_send_stock_list.draw();
    }




    $("#get_pdf").click(function() {

       

        var from_date = $("#from_date")[0].value;
        var to_date = $("#to_date")[0].value;
        
        var items = $("#items")[0].value;


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('get-stock-grand-pdf-report') }}",
            type: "POST",
            data: {
                from_date: from_date,
                to_date: to_date,
               
                items: items
            },
            success: function(data) {

                const pdfData = data[0];
                // Create a blob object from the base64-encoded data
                const byteCharacters = atob(pdfData);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {
                    type: 'application/pdf'
                });


                // Create a URL for the blob object
                const url = URL.createObjectURL(blob);

                // Create a link element with the URL and click on it to download the PDF file
                const link = document.createElement('a');
                link.href = url;
                link.download = 'nfc_stock_grand_report.pdf';
                document.body.appendChild(link);
                link.click();
            }


        })


    });



    $("#get_view").click(function(){

    var from_date = $("#from_date")[0].value;
    var to_date = $("#to_date")[0].value;
    var vendors = $("#vendors")[0].value;
    var item = $("#items")[0].value;

    var url = "{{ url('get-view-grand-stock-report') }}" + "/" + from_date + "/" + to_date + "/" + vendors + "/" + item;

    viewModal(url);

    });


    // $("#get_view").click(function(){

    // var from_date = $("#from_date")[0].value;
    // var to_date = $("#to_date")[0].value;
    // var vendors = $("#vendors")[0].value;
    // var items = $("#items")[0].value;

    // var url = "{{ url('get-view-grand-stock-report') }}" + "/" + from_date + "/" + to_date + "/" + branch;

    // forListModalView(url);

    // });







    $(".toselect-tag").select2();
</script>
{{-- @endsection --}}
