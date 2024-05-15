

<style>
  
    .amount_div{
        padding:5px;
        color:white;
        text-align: right;
    }
</style>

@php


    // echo "<pre>";
    // print_r($bankSums_array);
    // echo "</pre>";

    // echo "<pre>";
    // print_r($outsourceSums_array);
    // echo "</pre>";

    // echo "<pre>";
    // print_r($vouchers_array);
    // echo "</pre>";



        // Your given arrays
       

        // Extract unique bank names
        $bankNames = array_unique(array_column(array_merge($bankSums_array, $outsourceSums_array, $vouchers_array), 'bank_name'));

        // Initialize an associative array with zeros
        $initialValues = array_fill_keys($bankNames, array('bank_name' => null, 'total' => 0, 'outsource_sum' => 0, 'voucher_sum' => 0));

        // Merge arrays based on "bank_name" key with default values of 0
        $mergedArray = array_reduce(
            array_merge($bankSums_array, $outsourceSums_array, $vouchers_array),
            function ($carry, $item) {
                $bankName = $item['bank_name'];
                $carry[$bankName] = isset($carry[$bankName]) ? array_merge($carry[$bankName], $item) : array_merge(['bank_name' => $bankName], $item);
                return $carry;
            },
            $initialValues
        );

        // Convert associative array to indexed array
        $finalArray = array_values($mergedArray);

        




@endphp

<div>
{{-- <div class="bg-success amount_div">
    {{ number_format( $easypaisa_amount + $easypaisa_vouchers - $easypaisa_paid_amount )}}  : ایزی پیسہ
</div>

<div class="bg-danger amount_div">
   {{ number_format( $locker_amount + $locker_vouchers - $locker_paid_amount  ) }} : لاکر
</div> --}}

<div class="bg-primary amount_div">
    
    @foreach($finalArray as $get_bank_data)
        <div>
        {{ number_format($get_bank_data["outsource_sum"]+$get_bank_data["voucher_sum"]-$get_bank_data["total"])." : ".$get_bank_data["bank_name"] }}
        </div>
    @endforeach
</ul>  
</div>




</div>
