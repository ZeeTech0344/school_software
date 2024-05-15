

<style>
    th{
        text-align: right;
        border:1px solid rgb(203, 200, 200);
        padding:3px;
    }
    td{ 
        border:1px solid rgb(203, 200, 200);
        padding:3px;
        text-align: right;
    }
    table{
        border-collapse: collapse;
        width: 100%;
    }
    caption{
        padding:5px;
        border:1px solid rgb(203, 200, 200);
    }

</style>




@php

$total_in_amount = 0;
$total_out_amount = 0;
$get_difference = 0;


$create_grand_array = array_merge($vouchers_amount,$sum_of_easypaisa_datewise, $data);


usort($create_grand_array, function ($a, $b) {
    return strtotime($a['created_at']) - strtotime($b['created_at']);
});



@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......."  class="form-control w-25" >

</div>

<table id="locker_view_get" dir="rtl">
    <thead>
        <tr>
            <th>تاریخ</th>
            <th>ہیڈ</th>
            <th>جمع</th>
            <th>خرچ</th>
            <th>فرق</th>
            <th>ریمارکس</th>
        </tr>
    </thead>
    <tbody>


        @foreach ($create_grand_array as $get_date)

        <tr>
            <td>{{ date_format(date_create($get_date["created_at"]), "d-m-Y") }}</td>
            <td>{{ isset($get_date["head"]) ? $get_date["head"] : "جمع کردہ رقم"}}</td>
            <td class="amount_in">{{ $get_date["amount_status"] == "In" ? $get_date["amount"] : "-" }}
            </td>
            <td>
                {{ $get_date["amount_status"] == "Out" ? $get_date["amount"] : "-" }}
            </td>
        
            @php

                    if($get_date["amount_status"] == "In"){
                        $get_difference = $get_difference + $get_date["amount"];
                        $total_in_amount =  $total_in_amount + $get_date["amount"];
                    }elseif($get_date["amount_status"] == "Out"){
                        $get_difference = $get_difference - $get_date["amount"];
                        $total_out_amount =  $total_out_amount + $get_date["amount"];
                    }
                    
            @endphp

            {{-- this is value that get old amount (Grand final old amount) --}}
            <td>{{ $get_difference + $grand_final_old_amount}}</td>
            <td>{{ isset($get_date["remarks"]) ? $get_date["remarks"] : "-" }}</td>
               
        </tr>
       
        @endforeach
        
    {{-- <tr>
        <td colspan="6">
            <b>Amount (Start) : {{ $grand_final_old_amount }} </b>
        </td>
    </tr> --}}



    <tr>
        <td colspan="6" id="set_in_amount">
            <b>{{ $total_in_amount }} : ٹوٹل جمع  </b>
        </td>
    </tr> 
    <tr>
        <td colspan="6" id="set_out_amount">
            <b>{{ $total_out_amount }} : ٹوٹل خرچ </b>
        </td>
    </tr> 
    
    </tbody>

    

</table>


<script>



    $("#search_employee").keyup(function () {
    
    var value = this.value.toLowerCase().trim();
    
    $("#locker_view_get tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });

    });

    // if (typeof rows === 'undefined') {
    //     // If it's not defined, declare it and apply the logic
    //     const rows = document.querySelectorAll("#locker_view_get tbody tr");
    //     rows.forEach((row) => {
    //         const cells = Array.from(row.children);
    //         cells.reverse();
    //         row.innerHTML = '';
    //         cells.forEach((cell) => {
    //             row.appendChild(cell);
    //         });
    //     });
    // } else {
    //     // If the variable is already defined, you can update it or apply any other necessary logic.
    //     console.log("Rows variable is already defined.");
    // }

    
    
    </script>