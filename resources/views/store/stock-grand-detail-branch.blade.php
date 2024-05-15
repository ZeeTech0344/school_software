@extends('layout.structure')

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
                    <h6 class="m-0 font-weight-bold text-primary">Stock Grand Report</h6>
                </div>
                 <div class="card-body">
@php
    $sr = 1;
    
@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<table class="table table-bordered no-footer" id="stock_grand_report">
    <thead>
        <tr>
            <th>
                Sr#
            </th>
            <th>
                Items
            </th>
            <th>
                Total Stock
            </th>
            <th>
                Remaining
            </th>
           
        </tr>    
    </thead>

    <tbody>
       @foreach ($items as $get_date)

            <tr>
                <td>
                    {{ $sr++ }}
                </td>
                <td style="text-align: left;">
                    {{ $get_date->item_name." (".$get_date->getVendors->name.") " }}
                </td>
                <td>
                
                    {{ $get_date->total_stock_sum_weight ? $get_date->total_stock_sum_weight : "-"}}
                   
                </td>
                
                <td>
                    {{  $get_date->total_stock_sum_weight - $get_date->return_stock_sum_stock_qty_return  }}
                </td>
                
            </tr>

           

       @endforeach
    </tbody>
    
</table>



            </div>

        </div>
        </div>

    </div>

@endsection




@section('script')
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


@endsection