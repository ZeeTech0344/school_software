<style>
    table{
        border:1px solid #e3e6f0;
        border-collapse: collapse;
        width: 100%;
    }
    td, th{
        border:1px solid #e3e6f0;
        padding: 3px;
        text-align: right;
      
    }
</style>

@php
    $sr = 1;
    $total_salaries = 0;
    $total_advance_deducted = 0;
    $total_day_of_work_deducted = 0;
    $basic_salaries = 0;
    $grand_advance = 0;
    $grand_pending = 0;
    $grand_deduction = 0;
    $grand_addition = 0;


@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Employee......." onchange="checkValues(this)" class="form-control w-25" >

</div>

<table id="paid_salary_table">
  
    <thead>
        
      
       
        <th></th>
        <th>سیلری</th>
        <th>پوسٹ</th>
        <th>نام</th>
        <th>تاریخ</th>
        <th  style="text-align: center">سیریل نمبر</th>
    </thead>
    <tbody>
        @foreach ($data as $salary)
                
                <tr>
                  
                    
                    
                    
                  
                    <td style="text-align: center;">
                        <a href="#" class="btn btn-sm btn-danger unpaid-salary" data-id="{{ $salary->account_id.','.$salary->account_name }}">Unpaid</a>
                    </td>

                    <td><ul style="direction: rtl">
                        <li>
                           سیلری: {{ number_format($salary->employee->basic_sallary) }}
                        </li>
                        <li>
                            ایڈوانس: {{ $salary->advance }}
                            @php  $grand_advance = $grand_advance + $salary->advance;  @endphp
                        </li>
                        <li>
                            ادھار: {{$salary->pendings }}
                            @php  $grand_pending = $grand_pending + $salary->pendings;  @endphp
                        </li>
                        <li>
                            منفی کرہ: {{ $salary->day_of_work_deduction }}
                            @php  $grand_deduction = $grand_deduction + $salary->day_of_work_deduction;  @endphp
                        </li>
                        <li>
                            جمع کردہ: {{ $salary->addition }}
                            @php  $grand_addition = $grand_addition + $salary->addition;  @endphp
                        </li>
                        <li>
                            دن: {{ $salary->day_of_work }}
                        </li>
                        <li>
                            ریمارکس: {{ $salary->remarks }}
                        </li>
                        <li>
                            رقم: {{ $salary->amount }}
                            @php  $total_salaries = $total_salaries + $salary->amount;  @endphp
                        </li>
                    </ul>
                </td>

                    <td>{{ $salary->employee->employee_post }}</td>
                    <td>{{ $salary->employee->employee_name}}</td>
                    <td>{{ date_format(date_create($salary->created_at),"d-m-Y") }}</td>
                    <td style="text-align: center">{{ $sr++ }}</td>
                </tr>

               


        @endforeach
    <tr>
        <td colspan="6" style="color:#4e73df; font-weight:bolder;">
            ٹوٹل ادھار: {{ number_format($grand_pending) }}
        </td>
    </tr>
    <tr>
        <td colspan="6"  style="color:#4e73df; font-weight:bolder;"> 
            ٹوٹل ایڈوانس : {{ number_format($grand_advance) }}
        </td>
    </tr>
    <tr>
        <td colspan="6"  style="color:#4e73df; font-weight:bolder;">
            ٹوٹل جمع کردہ: {{ number_format($grand_addition) }}
        </td>
    </tr>
    <tr>
        <td colspan="6"  style="color:#4e73df; font-weight:bolder;">
            ٹوٹل منفی کردہ : {{ number_format($grand_deduction) }}
        </td>
    </tr>
    <tr>
        <td colspan="6"  style="color:#4e73df; font-weight:bolder;">
            ٹوٹل سیلری (ادا کردہ) : {{ number_format( $total_salaries ) }}
        </td>
    </tr>
      
    </tbody>
</table>


<script>
     $("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#paid_salary_table tr").each(function (index) {
    if (!index) return;
    $(this).find("td").each(function () {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});

$(".unpaid-salary").click(function(){

    var data = $(this).data("id").split(",");
    var element = this;

    var alert_delete = confirm("Are you sure you! Unpaid Salary");

    if(alert_delete){
        
        $.ajax({
        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
        url:"{{ url('delete-salary-record') }}",
        type:"POST",
        data:{data:data},
        success:function(data){
            $(element).parent().parent().fadeOut();
            employee_salary_table.draw();
        }
    })

}
            
})

</script>