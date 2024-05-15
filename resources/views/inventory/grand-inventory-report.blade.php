{{-- {{ $products }} --}}




<style>

    td{
        text-align: center;
    }
    th{
        text-align: center;
    }

    table{
        text-align: center;
    }

</style>

@section('content')
    <div class="col-12 d-flex justify-content-center">
        <div class="col-12 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Inventory Grand Report</h6>
                </div>
                 <div class="card-body">
@php
    $sr = 1;
    
@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Inventory......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<table class="table table-bordered no-footer" id="stock_grand_report">
    <thead>
        <tr>
            <th>
                Sr#
            </th>
            <th>
                Inventory
            </th>
            <th>
                Total
            </th>
            <th>
                Remaining
            </th>
         
        </tr>    
    </thead>

    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $sr++ }}</td>
                <td>{{ $product->product }}</td>
                <td>{{ $product->get_inventory_sum_quantity  }}</td>
                <td>{{ ($product->get_inventory_sum_quantity - $product->get_send_inventory_sum_send_qty) - $product->get_return_inventory_sum_return_qty }}</td>
            </tr>
        @endforeach
     
    </tbody>
    
</table>



            </div>

        </div>
        </div>

    </div>


<script>

$("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#stock_grand_report tr").each(function (index) {
    if (!index) return;
    $(this).find("td").each(function () {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});


$(document).on("click", ".get_data_of_stock", function() {

var data = $(this).data("id");

var url = "{{ url('get_stock_detail_using_item') }}" + "/" + data;

payNowModalBody(url);

})


</script>

