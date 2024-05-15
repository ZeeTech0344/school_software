<style>
    #admission_table{
        width:100%;
        border-collapse: collapse;
        
    }

    #admission_table td,th{
        border:1px solid rgb(215, 215, 215, 1);
        padding:5px;
    }
</style>


<div class="pt-2 pb-2 d-flex justify-content-end">

    <input type="text" id="search" name="search" placeholder="طالب علم سرچ کریں۔۔۔۔۔۔۔۔۔۔۔۔"
        class="form-control w-25">

</div>
<h3 style="text-align: center;">داخلہ لسٹ</h3>
<table dir="rtl" id="admission_table">
    <thead>
        <tr>
            <th>سیریل نمبر</th>
            <th>رجسٹر نمبر</th>
            <th>رول نمبر</th>
            <th>نام</th>
            <th>کلاس</th>
            <th>والد کا نام</th>
            <th>تاریخ  پیدائش</th>
            <th>شفٹ</th>
            <th>کیٹیگری</th>
            <th>داخلہ کی تاریخ</th>
            <th> سٹیٹس</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sr=1;
        @endphp
        @foreach ($admission as $get_data)

            <tr>
                <td>{{ $sr++ }}</td>
                <td>{{ $get_data->register_no }}</td>
                <td>{{ $get_data->roll_no }}</td>
                <td>{{ $get_data->name }}</td>
                <td>{{ $get_data->getClass->class."(".$get_data->getClass->getDepartments->department.") ".$get_data->section }}</td>
                <td>{{ $get_data->father_name }}</td>
                <td>{{ $get_data->dob }}</td>
                <td>{{ $get_data->shift }}</td>
                <td>{{ $get_data->category }}</td>
                <td>{{ $get_data->admission_date }}</td>
                <td>@php
                    if($get_data->status == "1"){
                        echo "آن";
                    }elseif($get_data->status == "0"){
                        echo "آف";
                    }elseif($get_data->status == "2"){
                        echo "رخصت";
                    }
                @endphp</td>
               
            </tr>
        @endforeach
    </tbody>
</table>



<script>

$("#search").keyup(function() {

var value = this.value.toLowerCase().trim();

$("#admission_table tr").each(function(index) {
    if (!index) return;
    $(this).find("td").each(function() {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});

</script>