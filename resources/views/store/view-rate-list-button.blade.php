<div class="col-12 d-flex justify-content-center">

    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">


            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Rate List</h6>
            </div>

            <div class="row p-4">
                <div class="col">
                    <input type="date" id="from_date" name="from_date" class="form-control" onchange="getRateList()">
                </div>

                <div class="col">
                    <input type="date" id="to_date" name="to_date" class="form-control" onchange="getRateList()">
                </div>

                <div class="col">
                    <select name="item_id" id="item_id" class="form-control toselect-tag" onchange="getRateList()">
                        <option value="">Select Items/Products</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name . " - " . $item->getVendors->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered get_rate_list" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Item</th>
                                <th>Wgt/Qty</th>
                                <th>Rate</th>
                                <th>T_Amt</th>
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
    var get_rate_list = $('.get_rate_list').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        paging: false,
        "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('view-rate-list') }}",
            data: function(d) {
                d.from_date = $("#from_date").val()
                d.to_date = $("#to_date").val()
                d.item_id = $("#item_id").val()
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
                data: 'rate',
                name: 'rate'
            },

            {
                data: 't_amount',
                name: 't_amount'
            },

        ],

        success: function(data) {
            console.log(data);
        }
    });


    function getRateList() {

        var from_date = $("#from_date")[0].value;
        var to_date = $("#to_date")[0].value;
        var item_id =  $("#item_id")[0].value;
        if (from_date !== "" && to_date !== "" && item_id !=="" ) {
            get_rate_list.draw();
        }
    }

    $(".toselect-tag").select2();


</script>
