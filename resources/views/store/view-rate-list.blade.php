
<style>
    table{
        border:1px solid #e3e6f0;
        border-collapse: collapse;
        width: 100%;
    }
    td, th{
        border:1px solid #e3e6f0;
        padding: 3px;
        text-align: left;
      
    }
</style>
@php
    $total_stock_send = 0;
    $total_stock_return = 0;
@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>
 {{-- ({{ date_format(date_create($month),"M-y"}})   --}}
<table id="rate_list_table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Vendor</th>
            <th>Item</th>
            <th>Wgt/Qty</th>
            <th>Rate</th>
            <th>T_Amt</th>
            {{-- <th>Send</th>
            <th>Return</th> --}}
            <th>Remaining</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stock as $get_data)
            <tr>
                <td>{{ date_format(date_create($get_data->date),"d-m-Y") }}</td>
                <td>{{ $get_data->getVendors->name }}</td>
                <td>{{ $get_data->getItems->item_name }}</td>
                <td>{{  $get_data->weight . " (".$get_data->weight_type.")"}}</td>
                <td>{{  $get_data->current_rate }}</td>
                <td>{{  $get_data->total_amount }}</td>
                {{-- <td> --}}
                    {{-- {{ $get_data->getSendRecord }} --}}
                    {{-- @foreach ($get_data->getSendRecord as $send_stock_date) --}}
                        {{-- <ul>
                        <li>Date: {{ date_format(date_create($send_stock_date->date),"d-m-Y") }} </li>  
                        <li>Branch: {{ $send_stock_date->branch }}</li>
                        <li>Send Stock: {{ $send_stock_date->stock_qty_send }}</li>  
                        @php
                             $total_stock_send =  $total_stock_send + $send_stock_date->stock_qty_send;
                        @endphp
                        </ul>  --}}
                    {{-- @endforeach --}}
                {{-- </td> --}}
                {{-- <td> --}}
                    {{-- @foreach ($get_data->getReturnRecord as $return_stock_date)
                    <ul>
                    <li>Date: {{ date_format(date_create($return_stock_date->date),"d-m-Y") }} </li>  
                    <li>Branch: {{ $return_stock_date->branch }}</li>
                    <li>Return Stock: {{ $return_stock_date->stock_qty_return }}</li>  
                    @php
                         $total_stock_return =  $total_stock_return + $return_stock_date->stock_qty_send;
                    @endphp
                    </ul> 
                @endforeach --}}
                {{-- </td> --}}
                {{-- <td>
                    {{ $total_stock_return. " + " . $get_data->weight . " - " . $total_stock_send . " = " . $total_stock_return + ($get_data->weight - $total_stock_send) . " (".$get_data->weight_type.")" }} 
                </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>


{{-- <style>
    table{
        border:1px solid #e3e6f0;
        border-collapse: collapse;
        width: 100%;
    }
    td, th{
        border:1px solid #e3e6f0;
        padding: 3px;
        text-align: left;
      
    }
</style> --}}

@php
    $sr = 1;
    $total_salaries = 0;
    $total_advance_deducted = 0;
    $total_day_of_work_deducted = 0;
    $basic_salaries = 0;

@endphp
{{-- <div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div> --}}
{{-- ({{ date_format(date_create($month),"M-y")  --}}
{{-- <h5 style="text-align: center;">Salary Sheet</h5>
<table id="paid_salary_table">
  
    <thead>
        <th>Vendor</th>
        <th>Item</th>
        <th>Rate</th>
        <th>Weight/Qty</th>
    </thead>
    <tbody>
       
    <tr>
       
    </tr>
       
        
   
    
    </tbody>
</table> --}}


<script>

     $("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#rate_list_table tr").each(function (index) {
    if (!index) return;
    $(this).find("td").each(function () {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});



// $(document).on("click", ".unpaid-salary", function() {

// var data = $(this).data("id").split(",");
// var element = this;
// $.ajax({
//     headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//     url:"{{ url('delete-salary-record') }}",
//     type:"POST",
//     data:{data:data},
//     success:function(data){
//         $(element).parent().parent().fadeOut();
//         employee_salary_table.draw();
//     }
// })


// })





</script>