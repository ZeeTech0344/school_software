<style>
    table {
        width: 100%;
        border: 1px solid rgb(214, 212, 212);
        border-collapse: collapse;
    }

    td,
    th {
        border: 1px solid rgb(214, 212, 212);
        padding: 5px;
        text-align: right;
    }
</style>


@php
    
    $grand_advance_total = 0;
    $total_salary = 0;
    $total_deduction_day_of_work = 0; 

     $total_addition_in_salary = 0; 

     $total_paid_salary = 0;
    
@endphp

<table class="salary_grand_table">
    <tr>
        <th>اداکردہ سیلری</th>
        <th>شمولیت</th>
        <th>ایڈوانس</th>
        <th>سیلری</th>
        <th>پوسٹ</th>
        <th>نام</th>
        <th>نمبر</th>
    </tr>

    @foreach ($salary_detail as $salary)
        @php
            $total_advance = 0;
        @endphp
        <tr>
           
          
         

            @php
                foreach ($salary->easypaisa as $get_amount_easypaisa) {
                    $total_advance = $total_advance + $get_amount_easypaisa->amount;
                }
             
                
                foreach ($salary->locker as $get_amount_locker) {
                    $total_advance = $total_advance + $get_amount_locker->amount;
                }
                
               
                
            @endphp

           
           

            @php
              
                $total_salary = $total_salary + $salary->basic_sallary;
            @endphp
            <td>
                @foreach ($salary->salary as $get_salary_detail)
                    <ul style="direction: rtl;">
                         
                        <li>
                            {{ 'بنیادی سیلری: ' . $get_salary_detail->basic_salary }}
                        </li>
                        <li>
                            {{ 'ایڈوانس : ' . $get_salary_detail->advance }}
                        </li>
                        <li>
                            {{ 'ادھار : ' . $get_salary_detail->pendings }}
                        </li>
                        <li>
                            {{ 'منفی کردہ رقم: ' . $get_salary_detail->day_of_work_deduction}}

                            @php

                            $total_deduction_day_of_work = $total_deduction_day_of_work + $get_salary_detail->day_of_work_deduction;

                            @endphp

                        </li>
                        <li>
                            {{ 'جمع کردہ رقم : ' . $get_salary_detail->addition }}

                            @php

                            $total_addition_in_salary = $total_addition_in_salary + $get_salary_detail->addition;

                            @endphp

                        </li>
                        <li>
                            {{ ' ٹوٹل کام کے دن: ' . $get_salary_detail->day_of_work }}
                        </li>
                        <li>
                            {{ 'ریمارکس : ' . $get_salary_detail->remarks }}
                        </li>
                        <li>
                            {{ 'رقم : '.$get_salary_detail->amount }}
                            @php
                                $total_paid_salary = $total_paid_salary + $get_salary_detail->amount;
                            @endphp
                        </li>
                        
                    </ul>
                @endforeach
            </td>
            <td>{{ date_format(date_create($salary->joining),"d-m-Y") }}</td>
            <td>
                {{ $total_advance }}
                @php
                     $grand_advance_total = $grand_advance_total + $total_advance;
                @endphp
            </td>
            <td>{{ $salary->basic_sallary }}</td>
            <td>{{ $salary->employee_post }}</td>
            <td>{{ $salary->employee_name }}</td>
            <td>{{ $salary->employee_no }}</td>

        </tr>
    @endforeach

   
    <tr>
        <td colspan="8">
            <b>ٹوٹل سیلری (بنیادی): {{ number_format($total_salary) }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <b> ٹوٹل سیلری (ادا کردہ): {{ number_format($total_paid_salary) }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <b>ٹوٹل سیلری (نہ اداکردہ): {{ number_format($total_salary -  $total_paid_salary) }}</b>
        </td>
    </tr>
    
    

</table>


