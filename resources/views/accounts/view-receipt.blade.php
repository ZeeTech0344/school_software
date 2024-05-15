
<style>
    .expense_bill {
        border-collapse: collapse;
        width: 100%;
        font-family: "Jameel Noori Nastaleeq Kasheeda" !important;
    }

    .expense_bill td,
    .expense_bill th {
        padding: 10px;
        text-align: center;
        border:1px solid black;
    }

    .heading {
        text-align: center;
        font-family: "Jameel Noori Nastaleeq Kasheeda" !important;
    }

    .expense_span {
        border-bottom: 1px solid black;
        display: inline-block;
        text-align: right;
    }

    .sub_heading {
        /* width: auto; */ /* Removed width setting */
        /* white-space: nowrap; */ /* Removed nowrap */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dashes{
        border-style: dashed;
    border-width: 1px; /* Adjust the size of dashes */
    border-color: black; /* Adjust the color if needed */
    border-radius: 0; /* Optional: adjust if you want sharp edges */
    width: 100%; /* Adjust the width of the dashes */
    margin: 10px 0; /* Adjust margin as needed */
    }
</style>



<h4 class="heading">جامع تذکیر القرآن</h4>
<h5 class="heading"> {{ $acocunts->bank_name }} رسید</h5>
<table dir="rtl" class="expense_bill">


    <tr>
        <th class="sub_heading">رسید نمبر</th>
        <td><span class="expense_span">{{ $acocunts->invoice_no }}</span></td>
        <th class="sub_heading">تاریخ</th>
        <td><span class="expense_span">{{ date_format(date_create($acocunts->created_at), 'd-m-Y') }}</span></td>
        <th class="sub_heading">وقت</th>
        <td><span class="expense_span">{{ date_format(date_create($acocunts->created_at), 'h:i:s') }}</span></td>
    </tr>

    <tr>
        <th class="sub_heading">رقم</th>
        <td><span class="expense_span">{{ number_format($acocunts->amount) }}</span></td>
        <th class="sub_heading">ریمارکس</th>
        <td><span class="expense_span">{{ $acocunts->remarks }}</span></td>
        <th class="sub_heading">رسید دینے والا </th>
        <td><span class="expense_span">{{ $acocunts->getEmployee->name }}</span></td>
    </tr>

</table>

<div>
    <div style="padding-top:50px;">................................ دستخظ <div>
</div>

<div class="dashes">
</div>


<h4 class="heading">جامع تذکیر القرآن</h4>
<h5 class="heading"> {{ $acocunts->bank_name }} رسید</h5>
<table dir="rtl" class="expense_bill">



    <tr>
        <th class="sub_heading">رسید نمبر</th>
        <td><span class="expense_span">{{ $acocunts->invoice_no }}</span></td>
        <th class="sub_heading">تاریخ</th>
        <td><span class="expense_span">{{ date_format(date_create($acocunts->created_at), 'd-m-Y') }}</span></td>
        <th class="sub_heading">وقت</th>
        <td><span class="expense_span">{{ date_format(date_create($acocunts->created_at), 'h:i:s') }}</span></td>
    </tr>

    <tr>
        <th class="sub_heading">رقم</th>
        <td><span class="expense_span">{{ number_format($acocunts->amount) }}</span></td>
        <th class="sub_heading">ریمارکس</th>
        <td><span class="expense_span">{{ $acocunts->remarks }}</span></td>
        <th class="sub_heading">رسید دینے والا </th>
        <td><span class="expense_span">{{ $acocunts->getEmployee->name }}</span></td>
    </tr>

</table>
<div>
    <div style="padding-top:50px;">................................ دستخظ <div>
</div>
