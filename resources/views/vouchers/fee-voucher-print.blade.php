<style>
    td,
    th {
        text-align: right;
        padding:5px;
    }

    table,
    td,
    th {
        border: 1px solid black;
        font-family: 'Jameel Noori Nastaleeq';
    }

    table{
        border-collapse: collapse;
    }

    section {
        page-break-after: always;
       
    }

</style>


@php

$heads_name = array_column($head_detail_array,"head","id");

@endphp




@foreach ($fee_vouchers as $fee_voucher)
    <section style="display:flex; justify-content:center;  align-items: center; margin-bottom:10px;">
        @for ($a = 0; $a < 2; $a++)
            <table>
                <thead>
                     <tr> <th  colspan="3">  <h1 style="text-align: center;  padding:10px; margin:0; margin-top:10px;">جامعہ تذکیرالقرآن </h1>
                      <h4  style="text-align: center; padding:0; margin:0;"> تھری ۔ ایف  پی  او  ایف  واہ  کینٹ</h4>
                </th></tr>  

                    <tr>
                        <th colspan="3"> سیریل نمبر :{{ $fee_voucher['voucher_no'] }}</th>
                    </tr>
                    <tr>
                        <th  colspan="3"> مہینہ : {{ $fee_voucher['for_the_month'] }} </th>
                    </tr>
                    <tr>
                        <th  colspan="3"> طالب علم کا نام/داخلہ نمبر :
                            {{ $fee_voucher['admissions'][0]['name'] . '(' . $fee_voucher['admissions'][0]['register_no'] . ') (' . $fee_voucher['admissions'][0]['get_class']['class'] . ')' }}
                        </th>
                    </tr>
                    <tr>
                        <th  colspan="3"> والد کا نام :{{ $fee_voucher['admissions'][0]['name'] }} </th>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td>
                            واجبات
                        </td>
                        <td>
                            ہیڈ
                        </td>
                        <td>
                            سیریل نمبر
                        </td>
                    </tr>
                    @php
                           

                           $string_without_spaces_and_quotes = str_replace([' ', '"'], '', $fee_voucher['voucher_heads']);

                          // Convert the modified string to an array
                           $heads_array = explode(',', $string_without_spaces_and_quotes);

                           
                           $heads_with_amount = array_chunk($heads_array, 2);

                           $secondElements = array_column($heads_with_amount, 1);

                               // Use the original array's first elements as keys
                            $keys = array_column($heads_with_amount, 0);

                                //Combine the keys and second elements to form the new array
                           $newArray = array_combine($keys, $secondElements);


                           $sr=1;
                        //    $total = 0;
                    @endphp
                    @foreach ($heads_with_amount as $head)
                               <tr>
                                <td style="width:45%"> {{ $head[1]   }}</td>
                                <td style="width:45%" >{{ array_key_exists($head[0], $heads_name) ? $heads_name[$head[0]] : "-" }}</td>
                                <td style="width:10%">{{ $sr++ }}</td>
                               </tr>    
                    @endforeach
                    <tr><td>{{ $fee_voucher['fine'] }}</td><td>جرمانہ(قدردانہ)</td><td>{{ $sr++ }}</td></tr>
                    <tr><td>  {{ $fee_voucher['before_due_date'] }} </td><td><b> کل واجب الادا </b></td> <td> </td> </tr>
                    <tr><td>  {{ $fee_voucher['after_due_date'] }} </td><td><b> (تاریخ کے بعد) کل واجب الادا </b></td> <td> </td> </tr>
                    <tr>
                        <td colspan="3"></td>
                     </tr>
                    <tr>
                        <td colspan="3">  ({{ $fee_voucher['last_date'] }})  آخری تاریخ </td>
                     </tr>
                     <tr>
                        <td  style="padding-top: 80px;"> دستخط  ۔۔۔۔۔۔۔۔۔۔۔۔۔۔۔۔</td> <td colspan="2" style="padding-top: 80px;">دستخط کلرک۔۔۔۔۔۔۔۔۔۔۔۔۔۔۔</td>
                     </tr>

                </tbody>

            </table>
            
        @endfor
        </section>
@endforeach
