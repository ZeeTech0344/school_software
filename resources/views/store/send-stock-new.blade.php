@extends('layout.structure')

@section('content')

    @php
        
        // $convert_stock_data_to_array = explode(",",$data);

        // $stock_id = $convert_stock_data_to_array[0];
        // $vendor_id = $convert_stock_data_to_array[1];
        // $vendor_name = $convert_stock_data_to_array[2];
        // $item_id = $convert_stock_data_to_array[3];
        // $item_name = $convert_stock_data_to_array[4];
        // $total_stock = $convert_stock_data_to_array[5];
        // $rate = $convert_stock_data_to_array[6];

    @endphp
    
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                
                <div class="card-body">
                    <form id="send-stock-form">
                        
                        <div class="form-group">
                        <label for="exampleFormControlInput1">Vendors</label>
                        <select name="vendor_id" id="vendor_id" class="form-control toselect-tag" onchange="getItemVendorWise()">
                        </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Items</label>
                            <select name="item_id" id="item_id" class="form-control toselect-tag" onchange="getItemRate()">
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Current Stock (KG/Packet/Liter)</label>
                            <input type="text" name="current_stock_qty" id="current_stock_qty" readonly  class="form-control">
                            <input type="hidden" name="current_stock" id="current_stock"  class="form-control">
                       
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Branch</label>
                            <select name="branch" id="branch" class="form-control">
                                <option value="">Select Branch</option>
                                <option>New City</option>
                                <option>Basti</option>
                                <option>Taxila</option>
                                <option>Attock</option>
                                <option>Pindi</option>
                                <option>NFC(Cheese Crush)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Stock Qty Send</label>
                            <input type="number" name="stock_qty_send" id="stock_qty_send" onkeyup="calculateQty()" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Remaining Stock</label>
                            <input type="text" name="remaining_stock" readonly id="remaining_stock" class="form-control">
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Send" id="submit_btn" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="stock_send_hidden_id" id="stock_send_hidden_id">
                        <input type="hidden" name="stock_id" id="stock_id">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-8 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Send Stock List</h6>

                    {{-- <div> 
                         <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_employee_other_report"><i
                        class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a> 

                         <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            id="employee_other_reports"><i class="fas fa-download fa-sm text-white-50"></i>Generate Full
                            Report</a> 
                    </div>  --}}

                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <div class="mb-3 d-flex">
                            <input type="date" class="form-control mr-3" id="from_date" name="from_date"
                                onchange="checkVal(this)">
                            <input type="date" class="form-control mr-3" id="to_date" name="to_date"
                                onchange="checkVal(this)">
                        </div>

                        <div class="mb-3 d-flex">
                            <input type="text" class="form-control mr-3" id="search_value" name="search_value"
                                placeholder="Type item/Product to search........">
                            <select name="branch"  class="form-control mr-3" id="branch_for_search" onchange="checkVal(this)">
                                <option value="">Select Branch</option>
                                <option>New City</option>
                                <option>Basti</option>
                                <option>Taxila</option>
                                <option>Attock</option>
                                <option>Pindi</option>
                            </select>
                        </div>
                        <table class="table table-bordered get_send_stock_list" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Item</th>
                                    <th>Weight/Qty</th>
                                    <th>Branch</th>
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
    @endsection

@section('script')
    <script>


function getItemRate(){

var item_id = $("#item_id")[0].value;
var vendor_id = $("#vendor_id")[0].value;


$.ajax({
    headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    url:"{{ url('get-items-rate') }}",
    type:"POST",
    data:{item_id:item_id, vendor_id:vendor_id},
    success:function(sum){
        
        $("#current_stock_qty").val(sum);
    }
})

}




function getVendors(){

    getItemRate();
    var vendors_parent = document.getElementById("vendor_id");
    vendors_parent.innerHTML="";

    $.ajax({
        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"{{ url('get-vendors') }}",
        type:"POST",
        success:function(data){

            var select_option = document.createElement("option");;
            select_option.value = "";
            select_option.innerText="Select Vendors";
            vendors_parent.appendChild(select_option);


           $.each(data, function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.innerText = value["name"];
                        create_option.value = value["id"];
                        vendors_parent.appendChild(create_option);


                    });
            }
    })
}

getVendors();


function getItemVendorWise(){   

    var item_parent = $("#item_id")[0];
    item_parent.innerHTML="";

    var vendor_id = $("#vendor_id")[0].value;

    $.ajax({
        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"{{ url('get-items-vendor-wise') }}",
        type:"POST",
        data:{vendor_id:vendor_id},
        success:function(data){

            var select_option = document.createElement("option");;
            select_option.value = "";
            select_option.innerText="Select Items";
            item_parent.appendChild(select_option);
           
           $.each(data, function(key, value) {
                        var create_option = document.createElement("option");
                        create_option.innerText = value["item_name"];
                        create_option.value = value["id"];
                        item_parent.appendChild(create_option);
                    });
            }
    })

}   


function calculateQty(){

    var current_stock = $("#current_stock_qty")[0].value;
    var stock_send_qty = $("#stock_qty_send")[0].value;
    var remaining_stock = $("#remaining_stock").val(current_stock - stock_send_qty);
    

}




        var table = $('.get_send_stock_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-send-stock-list') }}",
                data: function(d) {
                    d.date = $("#date").val()
                }
            },
            columns: [

                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'vendor',
                    name: 'vendor'
                },
                {
                    data: 'item',
                    name: 'item'
                },
                {
                    data: 'stock_send',
                    name: 'stock_send'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

            success: function(data) {
              
            }
        });


        $(document).on("click", "#create-closing", function() {

            table.draw();

            var value = $("#date")[0].value;
            
            $("#get_pdf").attr("data-date",value);
           
    
        })


        



        $("#search_value").on('keyup', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                //get_send_stock_list.draw();
            }
        });

        // var wait = false;

        $('#send-stock-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                vendor_id: "required",
                item_id: "required",
                stock_send_qty: "required",
                branch: "required",
            },

            submitHandler: function(form) {

                 $("#submit_btn")[0].disabled = true;
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-send-stock-new') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $("#submit_btn")[0].disabled = false;
                        $('#send-stock-form')[0].reset();
                    },
                    error: function(data) {


                    }

                })
            }
        });




        // $(document).on("click", ".edit_send_stock", function() {

        //     var id = $(this).data("id");
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('edit-send-stock') }}",
        //         type: "GET",
        //         data: {
        //             id: id
        //         },
        //         success: function(data) {
        //             console.log(data);
        //             var vendor_name = $("#vendor_name").val(data[1]["name"]);
        //             var vendor_id = $("#vendor_id").val(data[0]["vendor_id"]);
        //             var item_name = $("#item_name").val(data[2]["item_name"]);
        //             var item_id = $("#item_id").val(data[0]["item_id"]);

        //             var current_stock_qty = $("#current_stock_qty").val(data[3] + data[0]["stock_qty_send"]);
        //             var current_stock = $("#current_stock").val(data[3] + data[0]["stock_qty_send"]);

        //             var current_rate_check = $("#current_rate_check").val(data[4]["current_rate"]);
        //             var branch = $("#branch").val(data[0]["branch"]);
        //             var stock_qty_send = $("#stock_qty_send").val(data[0]["stock_qty_send"]);
        //             var stock_send_hidden_id = $("#stock_send_hidden_id").val(data[0]["id"]);
        //         }
        //     })

        // })



        // $(document).on("click", ".delete_send_stock", function() {

        // var id = $(this).data("id");
        // var element = this;

        // var confirm_delete = confirm("Are you sure!Delete Record");
        // if(confirm_delete){
        //     $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     url: "{{ url('delete-send-stock') }}",
        //     type: "GET",
        //     data: {
        //         id: id
        //     },
        //     success: function(data) {

        //         $(element).parent().parent().parent().parent().fadeOut();

        //     }
        // })
        // }
       

        // })



    //     function changeProductVendorWise(get_product){

    //     var parent = $("#item_id")[0].innerHTML="";

    //     var vendor_id = $("#vendor_id")[0].value;

    //     $.ajax({
    //         headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //         url:"{{ url('get-product-vendor-wise') }}",
    //         type:"GET",
    //         data:{vendor_id:vendor_id},
    //         success:function(data){
    //             var parent = $("#item_id")[0];
    //             $.each(data, function(key, value) {
    //             var create_option = document.createElement("option");
    //             create_option.value =  value["id"];
    //             create_option.innerText =  value["item_name"];
    //             if(get_product == value["id"]){
    //                 create_option.selected = true;
    //             }
    //             parent.appendChild(create_option);
    //             });
                


    //         }
    //     })

    // }



        $(".toselect-tag").select2();
    </script>

    @endsection
