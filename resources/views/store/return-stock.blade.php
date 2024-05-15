@extends('layout.structure')

@section('content')

    @php

        $convert_stock_data_to_array = preg_split("/,(?![^()]+\))/", $data);
        
        // $convert_stock_data_to_array = explode(",",$data);

        $stock_id = $convert_stock_data_to_array[0];
        $vendor_id = $convert_stock_data_to_array[1];
        $vendor_name = $convert_stock_data_to_array[2];
        $item_id = $convert_stock_data_to_array[3];
        $item_name = $convert_stock_data_to_array[4];
        $total_stock = $convert_stock_data_to_array[5];
        $rate = $convert_stock_data_to_array[6];
        $stock_send = $convert_stock_data_to_array[7];
        $branch = $convert_stock_data_to_array[8];

    @endphp
    
   
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Return Stock</h6>
                </div>
                <div class="card-body">
                    <form id="return-stock-form">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Vendor</label>
                            <input type="text" name="vendor_name" readonly id="vendor_name" class="form-control" value="{{ $vendor_name }}">
                            <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $vendor_id }}">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Item</label>
                            <input type="text" name="item_name" id="item_name" readonly class="form-control" value="{{ $item_name }}">
                            <input type="hidden" name="item_id" id="item_id" value="{{ $item_id }}">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Current Stock (KG/Packet/Liter)</label>
                            <input type="text" name="current_stock_qty" id="current_stock_qty" readonly  class="form-control">
                            <input type="hidden" name="current_stock" id="current_stock"  class="form-control">
                       
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Rate (Rs.)</label>
                            <input type="text" name="current_rate_check" id="current_rate_check" readonly value="{{ $rate }}" class="form-control">
                            {{-- <input type="hidden" name="current_rate" id="current_rate"  value="{{ $rate }}" class="form-control"> --}}
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlInput1">Branch</label>
                            <select name="branch_select" id="branch_select" class="form-control" disabled>
                                <option value="">Select Branch</option>
                                <option {{ $branch == "New City" ? "selected" : "" }}>New City</option>
                                <option {{ $branch == "Basti" ? "selected" : "" }}>Basti</option>
                                <option {{ $branch == "Taxila" ? "selected" : "" }}>Taxila</option>
                                <option {{ $branch == "Attock" ? "selected" : "" }} >Attock</option>
                                <option {{ $branch == "Pindi" ? "selected" : "" }} >Pindi</option>
                                <option {{ $branch == "NFC(Cheese Crush)" ? "selected" : "" }} >NFC(Cheese Crush)</option>
                            </select>
                        </div>
                        <input type="hidden" name="branch" id="branch" value="{{ $branch }}">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Stock Qty Return</label>
                            <input type="number" name="stock_qty_send" id="stock_qty_send" onkeyup="calculateQty()" class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Remaining Stock (After Return)</label>
                            <input type="text" name="remaining_stock" readonly id="remaining_stock" class="form-control">
                        </div>

                     

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Total Amount Calculate</label>
                            <input type="number" name="total_amount" readonly id="total_amount_get" class="form-control">
                        </div>





                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Return" id="submit_btn" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="stock_send_hidden_id" id="stock_send_hidden_id">
                        <input type="hidden" name="stock_id" id="stock_id" value="{{ $stock_id }}">
                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-8 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Return Stock List</h6>

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
                                    <th>Branch</th>
                                    <th>Weight/Qty</th>
                                    <th>C_Rate</th>
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
    @endsection

@section('script')
    <script>



function calculateQty(){

    var current_stock = $("#current_stock")[0].value;
    var stock_send_qty = $("#stock_qty_send")[0].value;
    console.log(current_stock);
    console.log(stock_send_qty);

    var remaining_stock = $("#remaining_stock").val( parseInt(current_stock) + parseInt(stock_send_qty) );
    if($("#remaining_stock")[0].value<0){
        $("#remaining_stock")[0].style.border="1px solid red";
    }else{
        $("#remaining_stock")[0].style.border="";
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
                url: "{{ url('get-return-stock-list') }}",
                data: function(d) {
                    d.search_value = $("#search_value").val()
                    d.from_date = $("#from_date").val()
                    d.to_date = $("#to_date").val()
                    d.branch = $("#branch_for_search").val()
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
                    data: 'items',
                    name: 'items'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },

                {
                    data: 'weight',
                    name: 'weight'
                },

                {
                    data: 'rate',
                    name: 'rate'
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



        function checkVal(e){

            get_send_stock_list.draw();
        }

    

            function getSendStock(){

            var stock_id = "<?php echo $stock_id;  ?>";
            var vendor_id = "<?php echo $vendor_id;  ?>";
            var item_id = "<?php echo $item_id;  ?>";
            var total_stock = "<?php echo $total_stock;  ?>";

            

            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url:"{{ url('get-send-stock-record') }}",
                type:"POST",
                data:{stock_id:stock_id, vendor_id:vendor_id, item_id, item_id},
                success:function(data){
                    console.log(data);

                    var return_stock = data[1]["sum"];
                    var send_stock = data[0]["sum"];

                    if(return_stock == null){
                        return_stock = 0;
                    }

                    if(send_stock == null){
                        send_stock = 0;
                    }

                    $("#current_stock_qty").val( parseInt(return_stock) + parseInt((total_stock - send_stock)) );
                    $("#current_stock").val( parseInt(return_stock) + parseInt((total_stock - send_stock)) );
                }
            })


            }

            getSendStock();

       
      

        



        $("#search_value").on('keyup', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                get_send_stock_list.draw();
            }
        });

        


        

        $('#return-stock-form').validate({
            errorPlacement: function(error, element) {
                // element[0].style.border = "1px solid red";
            },
            rules: {
                stock_id: "required",
                vendor_id: "required",
                item_id: "required",
                stock_send_qty: "required",
                branch: "required",
            },

            submitHandler: function(form) {

                
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-return-stock') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        var item_name = $("#item_name")[0].value;
                        var current_stock = $("#current_stock_qty")[0].value;
                        var stock_qty_send =  $("#stock_qty_send")[0].value;
                        var branch =  $("#branch")[0].value;

                        var remaining_stock =  $("#remaining_stock")[0].value;

                        var stock_send = "<?php echo $stock_send;  ?>";
                        console.log(stock_qty_send ,stock_send)

                        //this is not stock qty send this is stock qty reuturn kun k start say hi aisay aa rha is wjah sy aisay hi jany dia
                        if(parseInt(stock_qty_send) > parseInt(stock_send)){
                            alert("Your return stock  is always greater then zero or less then send stock");
                            return false;
                        }else{  
                            var confirm_stock =  confirm("Are you sure! Return Stock of " + item_name + " ( " + stock_qty_send + ") from "  + branch);
                            if(!confirm_stock){
                                return false;
                            }
                        }
                     
                    },
                    success: function(data) {
                        console.log(data);
                        if(data=="edited"){
                             window.location.reload();
                        }else{
                            getSendStock();
                        $("#stock_qty_send").val("");
                        $("#remaining_stock").val("");
                        $("#total_amount_get").val("");
                        $("#stock_send_hidden_id").val("");
                        
                        get_send_stock_list.draw();
                        $("#send_stock_hidden_id").val("");
                        // window.location.href = "{{ url('/') }}";
                        }
                       
                       
                    },
                    error: function(data) {


                    }

                })
            }
        });


        $(document).on("click", ".edit_return_stock", function() {

            
           
            var id = $(this).data("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-return-stock') }}",
                type: "GET",
                data: {
                    id: id
                },
                
                success: function(data) {

                    console.log(data);
                    
                    
                    var vendor_name = $("#vendor_name").val(data[1]["name"]);
                    var vendor_id = $("#vendor_id").val(data[0]["vendor_id"]);
                    var item_name = $("#item_name").val(data[2]["item_name"]);
                    var item_id = $("#item_id").val(data[0]["item_id"]);


                    var current_stock_qty = $("#current_stock_qty").val( (parseInt ( data[4]["weight"] - parseInt(data[5]) )  + parseInt(data[3]) )  ) ;
                    var current_stock = $("#current_stock").val( (parseInt ( data[4]["weight"] - parseInt(data[5]) )  + parseInt(data[3]) ) );

                  
                    $("#total_amount_get").val(data[0]["total_amount"]);


                    var current_rate_check = $("#current_rate_check").val(data[4]["current_rate"]);
                    var branch = $("#branch").val(data[0]["branch"]);
                    var branch_select = $("#branch_select").val(data[0]["branch"]);
                    var stock_qty_send = $("#stock_qty_send").val(data[0]["stock_qty_return"]);
                    var stock_send_hidden_id = $("#stock_send_hidden_id").val(data[0]["id"]);

                    //for disable send button if stock is less then 0
                    var stock_remining = (parseInt(data[4]["weight"]) - parseInt(data[3])) + data[0]["stock_qty_send"];
                    if(stock_remining<1){
                        $("#submit_btn")[0].disabled=true;
                    }else{
                        $("#submit_btn")[0].disabled=false;
                    }

                    calculateQty();
                }
            })

        })



        $(document).on("click", ".delete_send_stock", function() {

        var id = $(this).data("id");
        var element = this;

        var confirm_delete = confirm("Are you sure!Delete Record");
        if(confirm_delete){
            $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('delete-send-stock') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(data) {

                $(element).parent().parent().parent().parent().fadeOut();

            }
        })
        }
       

        })



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
