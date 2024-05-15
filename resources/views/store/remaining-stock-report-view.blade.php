<style>
    table{
        border:1px solid rgb(231, 231, 231);
        width: 100%;
        border-collapse: collapse;
    }
    td, th{
        border:1px solid rgb(231, 231, 231);
        padding: 6px;
    }
 
    .center_text{
        text-align: center;
    }

    .green{
        background-color: rgb(43, 2, 248); 
        font-weight:bolder; color:white;
    }
    .red{
        background-color: rgb(192, 1, 30); 
        font-weight:bolder; color:white;
        animation: stockLessAnimation 2s linear infinite;
    }
    .alert{
        background-color: rgb(10, 1, 72); 
        font-weight:bolder; color:white;
    }
    .remain{
        background-color: rgb(59, 62, 216); 
        font-weight:bolder; color:white;
    }


    @keyframes stockLessAnimation {
    0%, 100% {
        color:  rgb(200, 10, 10);  /* Starting and ending color */
    }
    50% {
        color: rgb(255, 255, 255);  ; /* Color during animation */
    }
    }

</style>

@php
    $sr=1;

    $total_stock_weight_count = 0;
    $total_stock_amount = 0;
    $send_stock_weight_count = 0;
    $send_stock_amount = 0;
    $return_stock_weight_count = 0;
    $return_stock_amount = 0;

@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_stock" name="search" placeholder="Search Stock......." class="form-control w-25" >

</div>
<table class="grand_report_view_table">

    <thead>
        <tr>
            <th>
                Sr#
            </th>
            <th>
                Item
            </th>
            <th class=""> 
                T_Stock
            </th>
            <th class="">
                S_Stock
            </th>
            <th class="">
                Return_Stock
            </th>
            <th class="remain">
                Remain_Stock
            </th>
            <th>
                T_Amt
            </th>
            <th>
                S_Amt
            </th>
            <th>
                Return_Amt
            </th>
            <th class="remain">
                Remain_Amt
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach ( $items as $get_data)
        
                <tr>
                    <td class="center_text">{{ $sr++ }}</td>
                    <td style="text-align: left;">{{ $get_data->item_name . " ( " . $get_data->getVendors->name . " ) " }}</td>
                    <td class="center_text">{{ number_format($get_data->total_stock_sum_weight)  }}</td>
                    <td class="center_text" >{{ number_format($get_data->send_stock_sum_stock_qty_send)  }}</td>
                    <td class="center_text">{{ number_format($get_data->return_stock_sum_stock_qty_return)  }}</td>

                    <td class="center_text {{ ($get_data->total_stock_sum_weight - $get_data->send_stock_sum_stock_qty_send) + $get_data->return_stock_sum_stock_qty_return <30 ? 'red' : 'remain' }}" >{{ number_format( ($get_data->total_stock_sum_weight - $get_data->send_stock_sum_stock_qty_send) + $get_data->return_stock_sum_stock_qty_return )  }}</td>

                    <td class="center_text">{{ number_format($get_data->total_stock_sum_total_amount)  }}</td>
                    <td class="center_text">{{ number_format($get_data->send_stock_sum_total_amount)  }}</td>
                    <td class="center_text">{{ number_format($get_data->return_stock_sum_total_amount)  }}</td>
                    <td class="center_text remain">{{ number_format( ($get_data->total_stock_sum_total_amount - $get_data->send_stock_sum_total_amount) + $get_data->return_stock_sum_total_amount )  }}</td>
                </tr>

                @php
                    $total_stock_weight_count =  $total_stock_weight_count + $get_data->total_stock_sum_weight ;
                    $send_stock_weight_count =  $send_stock_weight_count + $get_data->send_stock_sum_stock_qty_send ;
                    $return_stock_weight_count =  $return_stock_weight_count + $get_data->return_stock_sum_stock_qty_return ;

                    $total_stock_amount =  $total_stock_amount + $get_data->total_stock_sum_total_amount ;
                    $send_stock_amount =  $send_stock_amount + $get_data->send_stock_sum_total_amount ;
                    $return_stock_amount= $return_stock_amount + $get_data->return_stock_sum_total_amount ;
                @endphp
        @endforeach
        
        <tr>
            <td colspan="10"  style="font-weight: bolder; text-align:left">Total Amount: Rs. {{ number_format($total_stock_amount) }}</td>
        </tr>
        <tr >
            <td colspan="10" style="font-weight: bolder;text-align:left">Send Amount: Rs. {{ number_format($send_stock_amount) }}</td>
        </tr>
        <tr >
            <td colspan="10" style="font-weight: bolder; text-align:left">Return Amount: Rs. {{ number_format($return_stock_amount) }}</td>
        </tr>
        <tr >
            <td colspan="10" style="font-weight: bolder; text-align:left">Remaining Amount: Rs. {{ number_format(($total_stock_amount - $send_stock_amount) + $return_stock_amount) }}</td>
        </tr>
       
    </tbody>

</table>
       

<script>
    $("#search_stock").keyup(function () {

var value = this.value.toLowerCase().trim();

$(".grand_report_view_table tr").each(function (index) {
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
