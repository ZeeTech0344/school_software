
<style>
    #send_stock_table{
        border-collapse: collapse;
        width: 100%;
    }
   #send_stock_table td,th{
       border:1px solid rgb(180, 175, 175);
       padding:5px;
       font-size: 12px;
       /* text-align: center; */
    }

    caption{
        padding:5px;
        font-weight: bolder;
    }

</style>


@php

 

    $grand_total = 0;

    $total_weight = 0;


    $create_grand_array = array_merge($send_data, $return_data);


    usort($create_grand_array, function ($a, $b) {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    });

    


@endphp
<h4 style="text-align:center">Send Report</h4>
<table id="send_stock_table">
    <thead>
        <tr>
            <th>
                Send Date
            </th>
            <th>
                Stock Date
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
            <th>
               Status
            </th>
        </tr>
    </thead>
    
    <tbody>

        @foreach ($create_grand_array as $get_data)
                <tr>
                    <td>
                        {{ date_format(date_create($get_data["created_at"]),"d-m-Y") }}
                    </td>
                    <td>
                        {{ date_format(date_create($get_data["get_stock"]["created_at"]),"d-m-Y")  }}
                    </td>
                    
                    <td>
                        {{$get_data["branch"] }}
                    </td>
                    <td>
                        {{$get_data["get_vendors"]["name"] }}
                    </td>
                    <td>
                        {{$get_data["get_items"]["item_name"] }}
                    </td>
                    <td>
                        {{ isset($get_data["stock_qty_send"]) ? $get_data["stock_qty_send"] . " " . $get_data["get_stock"]["weight_type"] . " x ".  $get_data["get_stock"]["current_rate"]  : $get_data["stock_qty_return"] . " " . $get_data["get_stock"]["weight_type"]. " x ".  $get_data["get_stock"]["current_rate"]}}
                    </td>
                    <td>
                        {{$get_data["total_amount"] }}
                    </td>
                    <td>
                        <?php
                        if (isset($get_data["stock_qty_send"])) {
                            echo "<b style='color:green;'>Send</b>";
                        } else {
                            echo "<b style='color:red;' >Return</b>";
                        }
                        ?>
                    </td>

                    @php
                     $grand_total = ($grand_total + ( isset($get_data["stock_qty_send"]) ? $get_data["stock_qty_send"] * $get_data["get_stock"]["current_rate"] : 0) ) - (isset($get_data["stock_qty_return"]) ? $get_data["stock_qty_return"] * $get_data["get_stock"]["current_rate"] : 0);
                     $total_weight = ($total_weight + (isset($get_data["stock_qty_send"])  ? $get_data["stock_qty_send"] : 0) ) - (isset($get_data["stock_qty_return"]) ? $get_data["stock_qty_return"] : 0) ;
                    @endphp
                    
                </tr>

        @endforeach

        <tr>
            <td colspan="8" style="text-align: left;">
                @php
               
                echo "<b>Grand Total Rs. ". number_format($grand_total)."</b>";
              
                @endphp

            </td>
        </tr>

        <tr>
            <td colspan="8" style="text-align: left;">
                @php
               
                echo "<b>Total Weight. ". number_format($total_weight)."</b>";
              
                @endphp

            </td>
        </tr>

    </tbody>

</table>



