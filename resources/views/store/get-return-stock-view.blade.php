
<style>
    table{
        border-collapse: collapse;
        width: 100%;
    }
    td,th{
       border:1px solid rgb(180, 175, 175);
       padding:5px;
       font-size: 12px;
       text-align: center;
    }

    caption{
        padding:5px;
        font-weight: bolder;
    }

</style>


@php
    $grand_total = 0;

    $total_weight = 0;

@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<table id="send_stock_table">
    <thead>
        <tr>
            <th>
                Stock Date
            </th>
            <th>
                Send Date
            </th>
            <th>
                Branch
            </th>

            <th>
                Vendor
            </th> 
            <th>
                Item
            </th>
            <th>
               Wgt/Qty
            </th>
            <th>
                Amount
            </th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $get_data)
               
                <tr>
                    <td>{{ date_format(date_create($get_data->getStock->date),"d-m-Y") }}</td>
                    <td>{{ date_format(date_create($get_data->date),"d-m-Y") }}</td>
                    <td>{{ $get_data->branch }}</td>
                    <td>{{ $get_data->getVendors->name }}</td>
                    <td>{{ $get_data->getItems->item_name }}</td>
                    <td>{{ $get_data->stock_qty_return ." (".$get_data->getStock->weight_type.") x ".  $get_data->getStock->current_rate }}</td>
                    <td>{{ $get_data->stock_qty_return * $get_data->getStock->current_rate }}</td>
                </tr>

                @php
                $grand_total = $grand_total + ($get_data->stock_qty_return * $get_data->getStock->current_rate);
                $total_weight = $total_weight + $get_data->stock_qty_return;
                @endphp

        @endforeach
        <tr>
            <td colspan="7" style="text-align: left;">
                @php
               
                echo "<b>Grand Total Rs. ". number_format($grand_total)."</b>";
              
                @endphp

            </td>
        </tr>

        <tr>
            <td colspan="7" style="text-align: left;">
                @php
               
                echo "<b>Total Weight. ". number_format($total_weight)."</b>";
              
                @endphp

            </td>
        </tr>

    </tbody>
   
    
</table>


<script>
    $("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#send_stock_table tr").each(function (index) {
if (!index) return;
$(this).find("td").each(function () {
   var id = $(this).text().toLowerCase().trim();
   var not_found = (id.indexOf(value) == -1);
   $(this).closest('tr').toggle(!not_found);
   return not_found;
});
});
});
</script>

