
<style>

    #stock_pdf_report td{
        text-align: center;
        border:1px solid black;
    }
    #stock_pdf_report th{
        text-align: center;
        border:1px solid black;
    }

     table{
        text-align: center;
        border:1px solid black;
        border-collapse: collapse;
    }

</style>
@php
    $sr = 1;
    
@endphp

<h4 style="text-align: center">Stock Grand Detail Report</h4>
<table class="table table-bordered no-footer" id="stock_pdf_report">
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
                Send Stock
            </th>
            <th>
                Return Stock
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
                
                    {{ $get_date->total_stock_sum_weight ? $get_date->total_stock_sum_weight  : "-"}}
                   
                </td>
                <td>
                    {{ $get_date->send_stock_sum_stock_qty_send ? $get_date->send_stock_sum_stock_qty_send : "-" }}
                   
                </td>
                 <td>
                    {{ $get_date->return_stock_sum_stock_qty_return ?  $get_date->return_stock_sum_stock_qty_return : "-"}}
                
                </td>
                <td>
                    {{ ($get_date->total_stock_sum_weight- $get_date->send_stock_sum_stock_qty_send)  + $get_date->return_stock_sum_stock_qty_return }}
                </td>
                
            </tr>

           

       @endforeach
    </tbody>
    
</table>



            </div>

        </div>
        </div>

    </div>
