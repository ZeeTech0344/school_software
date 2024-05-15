
   

    {{-- invoice form start --}}

    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Add Stock</h6>
                    <div>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="view_bill">View Bill</a>
        
                        </div>
                </div>

                <div class="card-body">


                <form id="add-invoice-form" class="rounded  col-md-12 col-sm-12 p-0">

                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Bill#</label>
                        
                        <input type="number" name="table" id="table" class="form-control">
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        
                        <input type="button" class=" btn btn-sm btn-warning mt-2" id="reset"
                        style="margin-right: 5px;" value="Reset">
                        <input type="button" class=" btn btn-sm btn-danger mt-2 create-bill" onclick="createBill()"
                            style="margin-right: 5px;" value="Create Bill">
                       
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Item</label>
                        <select class="form-control h-100" id="items" disabled name="items"
                             >
                            @foreach ($items as $item)
                                <option value="{{ $item->id.",".$item->getVendors->id }}">{{ $item->item_name. " - " . $item->getVendors->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-4 mt-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control"  id="quantity" name="quantity"
                                onkeyup="calculate(this)">
                        </div>
                      
                        <div class="col-4 mt-2">
                            <label class="form-label">A.&nbsp;Rate</label>
                            <input type="text" class="form-control" id="rate" name="rate" onkeyup="calculate(this)">
                        </div>
                        <div class="col-4 mt-2">
                            <label class="form-label">A.&nbsp;Weight</label>
                            <select name="weight_type" id="weight_type" class="form-control">
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
                                <option>Piece</option>
                            </select>
                        </div>
                        <div class="col mt-2">
                            <label class="form-label">Total</label>
                            <input type="number" class="form-control" disabled id="rate_after_discount"
                                name="rate_after_discount" >
                        </div>
                    </div>


                    <div class="form-group d-flex justify-content-end">
                        <input type="button" class="btn btn-sm btn-success mt-2 mr-1" id="update_item" value="Update Item" disabled>
                        <input type="button" class=" btn btn-sm btn-danger mt-2 del-item-btn "
                            style="margin-right: 5px;" onclick="deleteItems()" disabled value="Delete Items">
                        <input type="button" class="btn btn-sm btn-dark mt-2 add-item-btn" value="Add Items" disabled>
                        <input type="hidden" id="item_hidden_id" name="item_hidden_id">

                    </div>
                    <div class="form-group mt-2" style="min-height:200px; overflow-x:auto;">
                        <h6 class="text-center table_label p-2 bg-primary text-white rounded d-none m-0">Bill# : <label
                                class="table_name m-0">Table - 1</label></h6>
                        <table class="caption-top invoice-table mb-0 pb-0 w-100 table" style="text-transform: lowercase; border:none;">
                            <thead style="padding:2px;">
                                <tr>
                                    <th scope="col">#</th>
                                    {{-- <th></th> --}}
                                    <th scope="col">Item</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Weight</th>
                                    <th scope="col">Rate</th>
                                    <th scope="col">Total</th>
                                    <th scope="col" class="invoice-action">Action</th>
                                </tr>
                            </thead>
                            <tbody id="invoice_table_body">
                            

                            </tbody>
                        </table>

                        <table class="edit-invoice-table d-none m-0 p-0">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="8">Add Other Items</th>
                                </tr>
                            </thead>

                            <tbody id="edit_invoice_table_body">

                            </tbody>
                        </table>


                    </div>

                    <div class="form-group mt-2">
                        <input type="submit" value="Save Bill" class="btn w-100 btn-primary save-btn">

                        

                        <input type="button" value="Delete Items" class="btn w-100 btn-danger d-none delete-btn"
                            onclick="deleteInvoiceItems()">
                    </div>

                </form>

             </div>

            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
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
                            
                            <select class="form-control toselect-tag" id="get_all_items" onchange="checkVal(this)">
                                <option value="">Select Items</option>
                                @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name. " - " . $item->getVendors->name }}</option>
                                @endforeach
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


</div>



    <script>


$("#reset").click(function(){

    $("#table").val("");
    $("#item_hidden_id").val("");
    $("#table")[0].disabled=false;
    $("#items")[0].disabled=true;
    $("#quantity").val("");
    $("#rate").val("");
    $("#weight_type").val("");

})

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
        $("#table").val(data["bill_no"]);
      
        $("#select2-items-container")[0].innerText = data["get_items"]["item_name"] +"-"+ data["get_vendors"]["name"];
        $("#items").val(data["get_items"]["id"]+","+data["get_vendors"]["id"]);
        $("#items")[0].disabled=false;
        $("#quantity").val(data["weight"]);
        $("#weight_type").val(data["weight_type"]);
        console.log(data["weight_type"]);
        $("#rate").val(data["current_rate"]);
        $("#rate_after_discount").val(data["total_amount"]);
        $("#item_hidden_id").val(data["id"]);
        $("#update_item")[0].disabled=false;

    }
})

})




$("#view_bill").click(function(){

    var bill_no = $("#table")[0].value;
    var url = "{{ url('/view-bill') }}" + "/" + bill_no;

    payNowModalBody(url);

})







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
                    d.items = $("#get_all_items").val()
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


$(document).on("click", "#update_item", function() {

var table = $("#table")[0].value;
var items = $("#items")[0].value;
var quantity = $("#quantity")[0].value;
var rate = $("#rate")[0].value;
var weight_type = $("#weight_type")[0].value;
var rate_after_discount = $("#rate_after_discount")[0].value;
var item_hidden_id = $("#item_hidden_id")[0].value;

$.ajax({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
url: "{{ url('update-item') }}",
type: "POST",
data: {
    table:table,
    items:items,
    quantity:quantity,
    rate:rate,
    weight_type:weight_type,
    rate_after_discount:rate_after_discount,
    item_hidden_id:item_hidden_id
},
success: function(data) {
    console.log(data);
    get_stock_list.draw();
    $("#table").val("");
    $("#item_hidden_id").val("");
    
    $("#items")[0].disabled=true;
    $("#quantity").val("");
    $("#rate").val("");
    $("#weight_type").val("");


}
})

})






        function calculate(){

            var quantity = $("#quantity")[0].value;
            var rate = $("#rate")[0].value;
            $("#rate_after_discount").val(quantity*rate);

        }


        $(document).on("click", ".addInvoice", function(e) {

            // window.location.reload();
            $("#add-invoice-form")[0].reset();
            $("#invoice_table_body")[0].innerHTML = "";
            $("#table").select2("val", "");
            $("#select2-table-container")[0].innerHTML = "";
            $(".add-invoice-div")[0].classList.remove("d-none");
            // $(".edit-invoice-div")[0].classList.add("d-none");
            $(".add-item-div")[0].classList.add("d-none");
            $("#items")[0].innerHTML = "";
           
            
            $("#items")[0].disabled = true;
            
            $("#quantity")[0].disabled = true;
            $("#rate_after_discount")[0].disabled = true;
            $("#table")[0].disabled = false;

            $(".delete-btn")[0].classList.add("d-none");
            $(".save-btn")[0].classList.remove("d-none");
            $(".del-item-btn")[0].classList.remove("d-none");
            $(".edit-invoice-table")[0].classList.add("d-none");
            $(".add-item-btn")[0].classList.add("btn-dark");
            $(".add-item-btn")[0].classList.remove("btn-info");
            $(".add-item-btn")[0].value = "Add Items";
            $(".add-expense-div")[0].classList.add("d-none");
            $(".create-bill")[0].disabled = false;
            $(".update-tables")[0].disabled = true;
           
        })



        var sr = 0;
        //add item button functionality
        $(document).ready(function() {
            var tbody = $("#invoice_table_body")[0];
            var edit_tbody = $("#edit_invoice_table_body")[0];

            $(".add-item-btn").on("click", function() {

                var item = $("#select2-items-container")[0].innerText;
                var item_value = $("#items")[0].value;
                sr++;
                // var items = $("#items")[0].innerText;
                var quantity = $("#quantity")[0].value;
        
                var rate = $("#rate")[0].value;
                var weight_type = $("#weight_type")[0].value;
                var rate_after_discount = $("#rate_after_discount")[0].value;

                if (items !== "" && quantity !== ""  && rate_after_discount !== "") {

                    if (quantity > 0) {
                        var match = false;
                        // check name already exist or not
                        if (this.value == "Add Items") {
                            var check_name = $("#invoice_table_body .td_item_name");
                            var check_name_length = check_name.length;
                            for (var b = 0; b < check_name_length; b++) {
                                if (check_name[b].innerText == item) {
                                    match = true;
                                    break;
                                }
                            }
                        } else if (this.value == "Add Items-Edit") {
                            var check_name = $("#edit_invoice_table_body .td_item_name");
                            var check_name_length = check_name.length;
                            for (var b = 0; b < check_name_length; b++) {
                                if (check_name[b].innerText == item) {
                                    match = true;
                                    break;
                                }
                            }

                            var check_name_invoice = $("#invoice_table_body .td_item_name");
                            var check_name_invoice_length = check_name_invoice.length;

                            for (var c = 0; c < check_name_invoice_length; c++) {
                                if (check_name_invoice[c].innerText == item) {
                                    match = true;
                                    break;
                                }
                            }


                        }



                        // start from here

                        // check krna hai table main duplicate value kun ja rhi hai

                        if (check_name_length > 0) {

                            if (match == false) {

                                var tr = document.createElement("tr");
                                var td_zero = document.createElement("td");
                                td_zero.innerText = sr;
                                var td_one = document.createElement("td");
                                td_one.classList.add("td_item_name");
                                td_one.innerText = item;
                                var td_two_rate = document.createElement("td");
                                td_two_rate.innerText = rate;

                                var td_weight = document.createElement("td");
                                td_weight.innerText = weight_type;

                                var td_two = document.createElement("td");
                                td_two.innerText = quantity;
                              
                                var td_four = document.createElement("td");
                                td_four.innerText = rate_after_discount;

                                var td_six = document.createElement("td");
                                td_six.style.display = "none";
                                td_six.innerText = item_value; //this is item id

                                var td_seven = document.createElement("td");
                                td_seven.style.textAlign = "center";
                                var checkbox_invoice = document.createElement("input");
                                checkbox_invoice.setAttribute("type", "checkbox");
                                checkbox_invoice.setAttribute("style", "width:15px; height:15px;");
                                checkbox_invoice.name = "invoice-item-checkbox";
                                td_seven.appendChild(checkbox_invoice);

                                tr.appendChild(td_zero);
                                tr.appendChild(td_one);
                                tr.appendChild(td_two);
                                tr.appendChild(td_weight);
                                tr.appendChild(td_two_rate);
                               
                                tr.appendChild(td_four);

                                tr.appendChild(td_six);
                                tr.appendChild(td_seven);

                                if (this.value == "Add Items") {
                                    tbody.appendChild(tr);
                                } else if (this.value == "Add Items-Edit") {
                                    edit_tbody.appendChild(tr);
                                }
                                $("#add-invoice-form")[0].reset();
                            }

                        } else {

                            if (this.value == "Add Items-Edit") {
                                if (match == false) {


                                    // var tr = document.createElement("tr");
                                    // var td_zero = document.createElement("td");
                                    // td_zero.innerText = sr;
                                    // var td_one = document.createElement("td");
                                    // td_one.classList.add("td_item_name");
                                    // td_one.innerText = item;
                                    // var td_two_rate = document.createElement("td");
                                    // td_two_rate.innerText = rate;
                                    // var td_weight = document.createElement("td");
                                    // td_weight.innerText = weight_type;
                                    // var td_two = document.createElement("td");
                                    // td_two.innerText = quantity;
                                    
                                    // var td_four = document.createElement("td");
                                    // td_four.innerText = rate_after_discount;
                                    // var td_five = document.createElement("td");
                                    // td_five.innerText = rate_after_discount;
                                    // var td_six = document.createElement("td");
                                    // td_six.style.display = "none";
                                    // td_six.innerText = item_value;


                                    // var td_seven = document.createElement("td");
                                    // td_seven.style.textAlign = "center";
                                    // var checkbox_invoice = document.createElement("input");
                                    // checkbox_invoice.setAttribute("type", "checkbox");
                                    // checkbox_invoice.setAttribute("style", "width:15px; height:15px;");
                                    // checkbox_invoice.name = "invoice-item-checkbox";
                                    // td_seven.appendChild(checkbox_invoice);


                                    // tr.appendChild(td_zero);
                                    // tr.appendChild(td_one);
                                    // tr.appendChild(td_two_rate);
                                    // tr.appendChild(td_weight);
                                    // tr.appendChild(td_two);
                                    
                                    // tr.appendChild(td_four);
                                    // tr.appendChild(td_five);
                                    // tr.appendChild(td_six);
                                    // tr.appendChild(td_seven);


                                    
                                var tr = document.createElement("tr");
                                var td_zero = document.createElement("td");
                                td_zero.innerText = sr;
                                var td_one = document.createElement("td");
                                td_one.classList.add("td_item_name");
                                td_one.innerText = item;
                                var td_two_rate = document.createElement("td");
                                td_two_rate.innerText = rate;

                                var td_weight = document.createElement("td");
                                td_weight.innerText = weight_type;

                                var td_two = document.createElement("td");
                                td_two.innerText = quantity;
                              
                                var td_four = document.createElement("td");
                                td_four.innerText = rate_after_discount;

                                var td_six = document.createElement("td");
                                td_six.style.display = "none";
                                td_six.innerText = item_value; //this is item id

                                var td_seven = document.createElement("td");
                                td_seven.style.textAlign = "center";
                                var checkbox_invoice = document.createElement("input");
                                checkbox_invoice.setAttribute("type", "checkbox");
                                checkbox_invoice.setAttribute("style", "width:15px; height:15px;");
                                checkbox_invoice.name = "invoice-item-checkbox";
                                td_seven.appendChild(checkbox_invoice);

                                tr.appendChild(td_zero);
                                tr.appendChild(td_one);
                                tr.appendChild(td_two);
                                tr.appendChild(td_weight);
                                tr.appendChild(td_two_rate);
                               
                                tr.appendChild(td_four);

                                tr.appendChild(td_six);
                                tr.appendChild(td_seven);





                                    if (this.value == "Add Items") {
                                        tbody.appendChild(tr);
                                    } else if (this.value == "Add Items-Edit") {
                                        edit_tbody.appendChild(tr);
                                    }
                                    $("#add-invoice-form")[0].reset();
                                    $(".del-item-btn").removeAttr('disabled');
                                }
                            } else {


                                // var tr = document.createElement("tr");
                                // var td_zero = document.createElement("td");
                                // td_zero.innerText = sr;
                                // var td_one = document.createElement("td");
                                // td_one.classList.add("td_item_name");
                                // td_one.innerText = item;
                                // var td_two_rate = document.createElement("td");
                                // td_two_rate.innerText = rate;

                                // var td_weight = document.createElement("td");
                                // td_weight.innerText = weight_type;

                                // var td_two = document.createElement("td");
                                // td_two.innerText = quantity;
                                
                                // var td_four = document.createElement("td");
                                // td_four.innerText = rate_after_discount;
                                // var td_five = document.createElement("td");
                                // td_five.innerText = rate_after_discount;
                                // var td_six = document.createElement("td");
                                // td_six.style.display = "none";
                                // td_six.innerText = item_value;


                                // var td_seven = document.createElement("td");
                                // td_seven.style.textAlign = "center";
                                // var checkbox_invoice = document.createElement("input");
                                // checkbox_invoice.setAttribute("type", "checkbox");
                                // checkbox_invoice.setAttribute("style", "width:15px; height:15px;");
                                // checkbox_invoice.name = "invoice-item-checkbox";
                                // td_seven.appendChild(checkbox_invoice);


                                // tr.appendChild(td_zero);
                                // tr.appendChild(td_one);
                                // tr.appendChild(td_two_rate);
                                // tr.appendChild(td_weight);
                                // tr.appendChild(td_two);
                              
                                // tr.appendChild(td_four);
                                // tr.appendChild(td_five);
                                // tr.appendChild(td_six);
                                // tr.appendChild(td_seven);

                                
                                var tr = document.createElement("tr");
                                var td_zero = document.createElement("td");
                                td_zero.innerText = sr;
                                var td_one = document.createElement("td");
                                td_one.classList.add("td_item_name");
                                td_one.innerText = item;
                                var td_two_rate = document.createElement("td");
                                td_two_rate.innerText = rate;

                                var td_weight = document.createElement("td");
                                td_weight.innerText = weight_type;

                                var td_two = document.createElement("td");
                                td_two.innerText = quantity;
                              
                                var td_four = document.createElement("td");
                                td_four.innerText = rate_after_discount;

                                var td_six = document.createElement("td");
                                td_six.style.display = "none";
                                td_six.innerText = item_value; //this is item id

                                var td_seven = document.createElement("td");
                                td_seven.style.textAlign = "center";
                                var checkbox_invoice = document.createElement("input");
                                checkbox_invoice.setAttribute("type", "checkbox");
                                checkbox_invoice.setAttribute("style", "width:15px; height:15px;");
                                checkbox_invoice.name = "invoice-item-checkbox";
                                td_seven.appendChild(checkbox_invoice);

                                tr.appendChild(td_zero);
                                tr.appendChild(td_one);
                                tr.appendChild(td_two);
                                tr.appendChild(td_weight);
                                tr.appendChild(td_two_rate);
                               
                                tr.appendChild(td_four);

                                tr.appendChild(td_six);
                                tr.appendChild(td_seven);


                                if (this.value == "Add Items") {
                                    tbody.appendChild(tr);
                                } else if (this.value == "Add Items-Edit") {
                                    edit_tbody.appendChild(tr);
                                }
                                // $("#add-invoice-form")[0].reset();
                                $(".del-item-btn").removeAttr('disabled');

                            }


                        }

                    }

                }

            })
        });


        $(document).ready(function() {
            $('#items').select2({
                width: "100%"

            });

            $('#get_all_items').select2({
                width: "100%"

            });
        });

        //edit item button functionality


        function createBill() {

            if ($("#table")[0].value !== "") {

                $("#table")[0].disabled = true;
                $("#items").removeAttr("disabled");
                $("#quantity").removeAttr("disabled");
               
                $(".add-item-btn").removeAttr("disabled");
               
                var table = $('#table')[0].value;
                $(".table_name")[0].innerText = table;
                $(".table_label")[0].classList.remove("d-none");

            }

        }

        // var url = "{{ url('view-invoice') }}/" + 1019;
        // showInvoice(url);

        $("#add-invoice-form").on("submit", function(e) {
        
        console.log("yes");
            // $(".save-btn").on("click", function(e){
            e.preventDefault();
            var check_items_exist = $("#invoice_table_body .td_item_name").length;
            var check_items_exist_edited = $("#edit_invoice_table_body .td_item_name").length;


          
            
            // console.log(check_items_exist);

            var table_names = $(".table_name")[0].innerText;
            if (check_items_exist_edited > 0) {
                var invoice_no = $(".invoice_no")[0].value;
                const myTable = document.getElementsByClassName('edit-invoice-table')[0];
                const myArrays = Array.from(myTable.rows).map(row => Array.from(row.children).map(cell => cell
                    .innerText));
                myArrays.shift();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-invoice') }}",
                    type: "POST",
                    data: {
                        invoice_data: myArrays,
                        invoice_no_edit: invoice_no,
                        table_names: table_names,
                       
                    },
                    // xhrFields: {
                    //     responseType: 'blob'
                    // },
                    success: function(response) {
                        sr = 0;
                      

                        $("#table")[0].value = "";
                        $("#invoice_table_body")[0].innerHTML = "";
                        $("#edit_invoice_table_body")[0].innerHTML = "";
                        $(".del-item-btn").attr('disabled', true);

                        $("#items").attr('disabled', true);
                        $("#quantity").attr('disabled', true);

                        $("#table").val("");
                        $("#item_hidden_id").val("");
                        $("#table")[0].disabled = false;
                        $("#items")[0].disabled=true;
                        $("#quantity").val("");
                        $("#rate").val("");
                        $("#weight_type").val("");
                       
                        $(".add-item-btn").attr('disabled', true);
                       
                        $(".select2-selection__rendered")[0].innerText = "";

                        
                    },

                    
                })
            } else if (check_items_exist > 0 && $(".add-item-btn")[0].value !== "Add Items-Edit") {
                const myTable = document.getElementsByClassName('invoice-table')[0];
                const myArrays = Array.from(myTable.rows).map(row => Array.from(row.children).map(cell => cell
                    .innerText));
                myArrays.shift();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-invoice') }}",
                    type: "POST",
                    data: {
                        invoice_data: myArrays,
                        table_names: table_names,
                        
                    },
                    // xhrFields: {
                    //     responseType: 'blob'
                    // },
                    success: function(response) {


                     
                        console.log(response);
                        sr = 0;
                        $("#table")[0].value = "";
                        $("#invoice_table_body")[0].innerHTML = "";
                        $("#edit_invoice_table_body")[0].innerHTML = "";
                        
                        $(".del-item-btn").attr('disabled', true);
                       
                        $(".select2-selection__rendered")[0].innerText = "";
                      
                        $("#table").val("");
                        $("#item_hidden_id").val("");
                        $("#items").attr('disabled', true);
                        $("#table")[0].disabled=false;
                      
                        $("#quantity").val("");
                        $("#rate").val("");
                        $("#weight_type").val("");
                        $("#rate_after_discount").val("");
                        get_stock_list.draw();
                    
                        // var blob = new Blob([response]);
                        // var link = document.createElement('a');
                        // link.href = window.URL.createObjectURL(blob);
                        // link.download = "desidhabaInvoice.pdf";
                        // link.click();
                        // getTopTenInvoices();

                    },

                    // success: function(data) {
                    //     console.log(data);
                    //     $("#invoice_table_body")[0].innerHTML = "";
                    //     $(".del-item-btn").attr('disabled', true);
                    // }
                })
            }
        })


       


        $("#table").on("change", function() {

            // var 
            $(".table_label")[0].classList.remove("d-none");
            //    $(".table_name")[0].innerText = "table";
            var table_name = [];
            var selected_option = $('#table option:selected');
            for (var i = 0; i <= selected_option.length - 1; i++) {
                table_name.push(selected_option[i].text);
            }
            $(".table_name")[0].innerText = table_name.join(" - ");


        })


        function deleteItems() {

            let checkboxes_invoice_item = $("input[name='invoice-item-checkbox']:checked");
            $.each(checkboxes_invoice_item, function(index, value) {
                $(value).parent().parent().remove();
            });

        }


       
    </script>

