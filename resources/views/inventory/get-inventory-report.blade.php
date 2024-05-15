
<style>
    table{
        border:1px solid black;
        width: 100%;
        border-collapse: collapse;

    }

    th, td{
        border:1px solid black;
        padding: 3px;
        text-align: center;
    }
</style>

@php
    $sr = 1;
@endphp

<h4 style="text-align: center;">Inventory Report</h4>
<table>
<thead>
    <tr>
        <th>Sr</th>
        <th>Option</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>T_Amount</th>
        <th>Remarks</th>
    </tr>
</thead>
<tbody>


@foreach ($data as $get_data){

    <tr>
        <td>{{ $sr++ }}</td>
        <td>{{  $get_data->option }}</td>
        <td>{{  $get_data->getProducts->product }}</td>
        <td>{{  $get_data->quantity }}</td>
        <td>{{  $get_data->total_amount }}</td>
        <td>{{  $get_data->remarks }}</td>
    </tr>

}

@endforeach

</tbody>
</table>