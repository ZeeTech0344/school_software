
<div class="col-12 d-flex justify-content-center">

    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Inventory List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="mb-3">

                        <div class="p-4 d-flex justify-content-center">
    
                            
                                <select class="form-control w-25 mr-2" onchange="checkVal()" name="option" id="option">
                                    <option>New Item</option>
                                    <option>Return Item</option>
                                </select>
                                
                                <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
                                <input type="button" value="PDF" class="btn btn-danger ml-2" id="get_send_stock_pdf">
                                {{-- <input type="button" value="View" class="btn btn-danger ml-2" id="get_inventory_view"> --}}
                            
        
  
                            
                        </div>


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
                d.option = $("#option").val()
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
           
        ],

        success: function(data) {
            console.log(data);
        }
    });


            function checkVal(){
                product_list.draw();
            }
   
      
   

            $("#get_send_stock_pdf").click(function(){

            var option = $("#option")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ url('add-inventory-report-pdf') }}",
                type:"POST",
                data:{option:option},
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
                    link.download = 'inventory_report.pdf';
                    document.body.appendChild(link);
                    link.click();  
                
                }
            })
    });


    $("#get_inventory_views").click(function(){


    });


    $(".toselect-tag").select2();
</script>

