<style>
    table{
        width: 100%;
        border-collapse: collapse;
    }

    td,th{
        border:1px solid rgb(227, 227, 227);
        padding:5px;
    }
</style>

@php
    
    $total = 0;

@endphp

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Item</th>
            <th>Weight/Qty</th>
            <th>Rate</th>
            <th>T_Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bill as $get_data)
                <tr>
                    <td>{{ date_format(date_create($get_data->date),"d-m-Y") }}</td>
                    <td>{{ $get_data->getItems->item_name. " - ".$get_data->getVendors->name }}</td>
                 
                    <td>{{ $get_data->weight." (".$get_data->weight_type.")"}}</td>
                    <td>{{ $get_data->current_rate}}</td>
                    <td>{{ $get_data->total_amount }}</td>
                </tr>
                @php
                    
                    $total = $total + $get_data->total_amount;

                @endphp
        @endforeach
        <tr>
            <td colspan="6">
               <b> Grand Total: Rs. {{ $total }}</b>
            </td>
        </tr>
    </tbody>
</table>