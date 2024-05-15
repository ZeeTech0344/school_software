
<style>
    table{
        border-collapse: collapse;
        width: 100%;
    }
    td,th{
       border:1px solid black;
       padding:5px;
       font-size: 12px;
    }

    caption{
        padding:5px;
        font-weight: bolder;
    }

</style>

@php
    $total_stock_send = 0;
    $total_stock_return = 0;
    $grand_total = 0;
@endphp

<table>
    <caption>Stock Grand Detail</caption>
    <thead>
        <tr>
            <th>Date</th>
            <th>Vendor</th>
            <th>Item</th>
            <th>Wgt/Qty</th>
            <th>Rate</th>
            <th>T_Amt</th>
            <!-- <th>Send</th>
            <th>Return</th>
            <th>Remaining</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach ($stock as $get_data)
        @php
        $total_stock_send = 0;
         $total_stock_return = 0;
         $grand_total = $grand_total + $get_data->total_amount;
        @endphp 
            <tr>
                <td>{{ date_format(date_create($get_data->date),"d-m-Y") }}</td>
                <td>{{ $get_data->getVendors->name }}</td>
                <td>{{ $get_data->getItems->item_name }}</td>
                <td>{{  $get_data->weight . " (".$get_data->weight_type.")"}}</td>
                <td>{{  $get_data->current_rate }}</td>
                <td>{{  $get_data->total_amount }}</td>
                <!-- <td>
                    {{-- {{ $get_data->getSendRecord }} --}}
                    @foreach ($get_data->getSendRecord as $send_stock_date)
                        <ul>
                        <li>Date: {{ date_format(date_create($send_stock_date->date),"d-m-Y") }} </li>  
                        <li>Branch: {{ $send_stock_date->branch }}</li>
                        <li>Send Stock: {{ $send_stock_date->stock_qty_send }}</li>  
                        @php
                             $total_stock_send =  $total_stock_send + $send_stock_date->stock_qty_send;
                        @endphp
                        </ul> 
                    @endforeach
                </td>
                <td>
                    @foreach ($get_data->getReturnRecord as $return_stock_data)
                    
                    <ul>
                    <li>Date: {{ date_format(date_create($return_stock_data->date),"d-m-Y") }} </li>  
                    <li>Branch: {{ $return_stock_data->branch }}</li>
                    <li>Return Stock: {{ $return_stock_data->stock_qty_return }}</li>  
                    @php
                          $total_stock_return =  $total_stock_return + $return_stock_data->stock_qty_return;
                    @endphp
                    </ul> 
                @endforeach
                </td> -->
                <!-- <td>
                    {{ $total_stock_return. " + " . $get_data->weight . " - " . $total_stock_send . " = " . $total_stock_return + ($get_data->weight - $total_stock_send) . " (".$get_data->weight_type.")" }} 
                </td> -->
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align:left; font-weight: bolder">Grand Total : Rs. {{number_format($grand_total)}}</td>
        </tr>
    </tbody>
</table>