

<style>
    tr,td{
        border:1px solid #dbdbdb;
        padding: 10px;
    }
    table{
        border:1px solid #dbdbdb;
        border-collapse: collapse;
        width: 100%;
    }
    img{
        border:1px solid #dbdbdb;
        padding: 3px;
    }
</style>

<table dir="rtl">
    <tr>
        <td colspan="2" style="text-align: center;">
            <img src="{{ asset('images/'.$employees->image) }}" style="width: 150px;">
        </td>
    </tr>
    <tr>
        <td>نام</td>
        <td>{{ $employees->employee_name }}</td>
    </tr>

    <tr>
        <td>تاریخ پیدائش</td>
        <td>{{ $employees->dob }}</td>
    </tr>

    <tr>
        <td> فون نمبر</td>
        <td>{{ $employees->phone_no }}</td>
    </tr>

    <tr>
        <td>فون نمبر (والد)</td>
        <td>{{ $employees->father_name }}</td>
    </tr>

    <tr>
        <td>شناختی کارڈ نمبر</td>
        <td>{{ $employees->cnic }}</td>
    </tr>

    <tr>
        <td>والد شناختی کارڈ نمبر</td>
        <td>{{ $employees->father_cnic }}</td>
    </tr>

    <tr>
        <td>سیلری</td>
        <td>{{ $employees->basic_sallary }}</td>
    </tr>

    <tr>
        <td>شمولیت کی تاریخ</td>
        <td>{{ $employees->joining }}</td>
    </tr>

    <tr>
        <td>رخصت کی تاریخ</td>
        <td>{{ $employees->leaving }}</td>
    </tr>
    <tr>
        <td> سٹیٹس</td>
        <td>{{ $employees->employee_status }}</td>
    </tr>

</table>
