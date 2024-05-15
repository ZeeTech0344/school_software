<style>
    table{
        border-collapse: collapse;
        width: 100%;
    }
    td,th{
       border:1px solid black;
       padding:5px;
       font-size: 12px;
       text-align: center;
    }

    caption{
        padding:5px;
        font-weight: bolder;
    }

</style>

<table>
    <caption>Return Stock Report (NFC Head Office)</caption>
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
        @endforeach
    </tbody>
   
    
</table>


<h5 style="margin-top: 70px; text-align:right;">Manager ______________________</h5>