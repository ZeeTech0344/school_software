{{-- @extends('layout.structure')

@section('content') --}}



    <div class="col-12 d-flex justify-content-center">

        <div class="col-lg-12 col-sm-12">
            <div class="card shadow mb-4">


                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Send Stock List</h6>
                </div>

                <div class="row p-4">
                    <div class="col">
                        <input type="date" id="from_date" name="from_date" class="form-control" onchange="checkVal(this)">
                    </div>

                    <div class="col">
                        <input type="date" id="to_date" name="to_date" class="form-control" onchange="checkVal(this)">
                    </div>

                    <div class="col">
                        <select class="form-control" onchange="checkVal(this)" name="branch" id="branch">
                            <option value="">Select Branch</option>
                            <option>New City</option>
                            <option>Basti</option>
                            <option>Taxila</option>
                            <option>Attock</option>
                            <option>Pindi</option>
                            <option>NFC(Cheese Crush)</option>
                        </select>
                    </div>

                    <div class="col">
                        <select class="form-control toselect-tag" onchange="checkVal(this)" name="items" id="items">
                            <option value="">Select Items/Products</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name . "-" . $item->getVendors->name }}</option>
                            @endforeach
                        </select>
                    </div>


                  


                    <div>
                        <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
                        <input type="button" value="PDF" class="btn btn-danger" id="get_send_stock_pdf">
                        <input type="button" value="View" class="btn btn-primary" id="get_send_stock_view">
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
{{-- @endsection

@section('script') --}}
    <script>
        // function calculateQty() {

        //     var current_stock = $("#current_stock")[0].value;
        //     var stock_send_qty = $("#stock_qty_send")[0].value;
        //     var remaining_stock = $("#remaining_stock").val(current_stock - stock_send_qty);
        //     if ($("#remaining_stock")[0].value < 0) {
        //         $("#remaining_stock")[0].style.border = "1px solid red";
        //     } else {
        //         $("#remaining_stock")[0].style.border = "";
        //     }

        //     var current_rate_check = $("#current_rate_check")[0].value;
        //     var stock_send_qty = $("#stock_qty_send")[0].value;


        //     $("#total_amount_get").val(current_rate_check * stock_send_qty);

        // }


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
                url: "{{ url('get-send-record') }}",
                data: function(d) {
                    d.from_date = $("#from_date").val()
                    d.to_date = $("#to_date").val()
                    d.branch = $("#branch").val()
                    d.items = $("#items").val()
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


        function checkVal() {
        
            if($("#from_date")[0].value !== "" && $("#to_date")[0].value !== ""){
                get_send_stock_list.draw();
            }
            
            // checkItems();
        }

        
        

        function reset(){

            $("#from_date").val("");
            $("#to_date").val("");
            $("#branch").val("");
            get_send_stock_list.draw();
        }


        $("#get_send_stock_view").click(function(){

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            var branch = $("#branch")[0].value;
            var items = $("#items")[0].value;
            
            if(branch !== ""){
                var url = "{{ url('get-send-record-view') }}" + "/" + from_date + "/" + to_date  + "/" + branch + "/" + items;
            }else{

                var url = "{{ url('get-send-record-item-view') }}" + "/" + from_date + "/" + to_date  + "/" + items;
            }
           

          

            viewModal(url);

            

        });
     

            $("#get_send_stock_pdf").click(function(){

                $("#wait").css("display","block");

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            var branch = $("#branch")[0].value;
            var items = $("#items")[0].value;
            
         $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ url('get-send-record-pdf-report') }}",
                type:"POST",
                data:{from_date:from_date, to_date:to_date, branch:branch, items:items},
                success:function(data){

                    const pdfData = data[0];
                    // Create a blob object from the base64-encoded data
                    const byteCharacters = atob(pdfData);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});


                    // Create a URL for the blob object
                    const url = URL.createObjectURL(blob);

                    // Create a link element with the URL and click on it to download the PDF file
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'nfc_send_stock_grand_report.pdf';
                    document.body.appendChild(link);
                    link.click(); 
                    $("#wait").css("display","none");
                }
            })
        });
          

        // $("#search_value").on('keyup', function(e) {
        //     if (e.key === 'Enter' || e.keyCode === 13) {
        //         employee_front_table.draw();
        //     }
        // });





        $(".toselect-tag").select2();
    </script>
{{-- @endsection --}}
