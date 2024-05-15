
<style>
    table{
        width: 100%;
        border:1px solid rgb(146, 146, 146);
        border-collapse: collapse;
    }
    th,td, tr{
        border:1px solid rgb(146, 146, 146);
        text-align: center;
        padding: 5px;
    }
</style>
<h3 style="text-align: center">ناموصول ہونے والی لسٹ  (فیس ووچر)</h3>
<table>

   

    @php
     
    @endphp

    <thead>
        <tr>
            <th>فیس</th>
            <th>سیکشن</th>
            <th>کلاس</th>
            <th>والدکانام</th>
            <th>نام</th>
            <th>سیریل نمبر</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sr = 1;
            $total = 0;
        @endphp
        @foreach ($vouchers as $voucher)
        
        @php
            $total = $total + $voucher["vouchers"][0]["after_due_date"];
        @endphp
            <tr>
                <td>{{ number_format($voucher["vouchers"][0]["after_due_date"]) }}</td>
                <td>{{ $voucher["section"] }}</td>
                <td>{{ $voucher["get_class"]["class"] }}</td>
                <td>{{ $voucher["father_name"] }}</td>
                <td>{{ $voucher["name"] }}</td>
                <td>{{ $sr++ }}</td>
            </tr>
           
        @endforeach
        <tr>
            <td> 
              <b>  ٹوٹل: {{ $total  }}</b>
            </td>
        </tr>
    </tbody>

</table>