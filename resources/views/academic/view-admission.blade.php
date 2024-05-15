<style>
    #admission_table td,
    #admission_table th {
        border: 1px solid rgb(215 215 215);
        border-collapse: collapse;
        padding: 10px;
        font-family: "Jameel Noori Nastaleeq Kasheeda";
       
    }



    #admission_table img {
        border: 1px solid rgb(215 215 215);
        padding: 5px;
    }

    #admission_table {
        border: 1px solid rgb(215 215 215);
        border-collapse: collapse;
        width: 100%;
    }

    #background_image_set{
        background-image: url('{{ url('images/logo.jpg') }}');
         background-repeat:no-repeat;
         background-position: center;
         opacity: 0.2; /* Set opacity to 50% */
         position:absolute;
         left:0;
         bottom:100px;
         top:0;
         right:0;
         margin:auto;
    }

    @media print {
        #admission_table{
            margin-top: 20px;
        }
        #admission_table ,td,th {
        font-size: 12px;
        /* color:#b77b3d; */
        text-align: right;
        background-position: center;
        padding:15px;
        
    }

    

  /* Additional print-specific styles can be added here */
}


   

   
</style>
@foreach ($admission as $get_data)
<div class="p-2" style="display: flex; justify-content:center;">
    <img src="{{ asset('images/header.jpg') }}" alt="" style="width:250px;">
 </div>
        <h2 style="text-align: center;padding:10px;">داخلہ فارم</h2>
   <div id="background_image_set">
</div>
    <table dir="rtl" id="admission_table" style="z-index: 100;">
        <tr>
            <th>رجسٹر نمبر</th>
            <td>{{ $get_data->register_no }}</td>
            <th>تاریخ داخلہ</th>
            <td>{{ date_format(date_create($get_data->admission_date), 'd-m-Y') }}</td>
        </tr>
        <tr>
            <th>رولنمبر</th>
            <td>{{ $get_data->roll_no }}</td>
            <th>نام طالب علم /طالبہ</th>
            <td>{{ $get_data->name }}</td>
        </tr>
        <tr>
            <th>ولدیت</th>
            <td>{{ $get_data->father_name }}</td>
            <th>والد کا پیشہ</th>
            <td>{{ $get_data->father_occupation }}</td>
        </tr>
        <tr>
            <th>سر پرست کا نام</th>
            <td>{{ $get_data->guardian}}</td>
            <th>سر پرست کا بچے / بچی سے رشتہ</th>
            <td>{{ $get_data->guardian_relation}}</td>
        </tr>
     
        <tr>
            <th>والد / سرپرست کا شناختی کارڈ نمبر</th>
            <td>{{ $get_data->father_cnic }}</td>
            <th>تاریخ پیدائش</th>
            <td>{{ date_format(date_create($get_data->dob), 'd-m-Y') }}</td>
        </tr>
        <tr>
            <th>فون نمبر</th>
            <td>{{ $get_data->phone_no }}</td>
            <th>موبائل نمبر</th>
            <td>{{ $get_data->mobile_no }}</td>
        </tr>
        <tr>
           
            <th>ایڈریس</th>
            <td colspan="3">{{ $get_data->address }}</td>
        </tr>
        <tr>
            <th>سابقہ مدرسے کا نام</th>
            <td>{{ $get_data->previous_madrissa_education }}</td>
            <th>سابقہ دینی تعلیم اگر ہو تو</th>
            <td>{{ $get_data->previous_madrissa }}</td>
        </tr>
        <tr>
            <th>سکول کا نام</th>
            <td>{{ $get_data->previous_school }}</td>
            <th>سابقہ عصری تعلیم اگر ہو تو</th>
            <td>{{ $get_data->previous_school_education }}</td>
        </tr>

        <tr>
            <th>کلاس (شعبہ)</th>
            <td>{{ $get_data->getClass->class . '(' . $get_data->getClass->getDepartments->department . ')' }}</td>
        </tr>
        <tr>
            <th>کیٹیگری</th>
            <td>{{ $get_data->category }}</td>
            <th>شفٹ</th>
            <td>{{ $get_data->shift }}</td>
        </tr>
        <tr>
            <th>سٹیٹس</th>
            <td colspan="3">{{ $get_data->status == 1 ? 'آن' : 'آف' }}</td>
        </tr>


    </table>

@endforeach

<script>
    $("#print-admission").click(function() {

        var idValue = $(this).data("id");
        $.ajax({
            type: 'GET',
            url: '{{ url('print-admission') }}' + "/" + idValue,
            contentType: 'application/json',
            success: function(response) {
                var printWindow = window.open('', '_blank');
                printWindow.document.write(response);
                setTimeout(function() {
                    printWindow.print();
                    printWindow.close();
                }, 500);

            },
            error: function() {
                console.error('AJAX request failed.');
            }
        });
    })
</script>
