<style>
    td,
    th {
        text-align: right;
        padding:10px;
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
    h4,h5{
        text-align: center;
        margin: 0;
    }

</style>


@php

// echo "<pre>";
// print_r($results);
// echo "</pre>";

// echo "<pre>";
// print_r($check_position);
// echo "</pre>";

$student_id = array_column($check_position, "id");
$marks_list = array_column($check_position, "marks_sum_marks");

$marks = array_combine($student_id, $marks_list);
$get_position = arsort($marks);



$i = 0;

$positioned_array = [];
$last_position = null;

foreach ($marks as $student_id => $value) {
    // Assign the same position if the value is the same as the previous student
    $position = ($value !== $last_position) ? ++$i : $i;

    // Get the position label (first, second, third, etc.)
    $position_label = ($position === 1) ? 'پہلی' : (($position === 2) ? 'دوسری' : 'تیسری');

    // Store the position, value, and student_id
    $positioned_array[$student_id] = ['position' => $position_label, 'value' => $value];

    $last_position = $value;
}


@endphp

@foreach ($results as $student_result)
    <section style="display:flex; justify-content:center;  align-items: center; margin-bottom:10px;">


            <table>
            
               </tr>
                    <tr> 
                    <th  colspan="4">
                        <div style="display:flex; justify-content:center;"><img style="width:180px;" src="{{ asset("images/header.jpg") }}"></div>
                        {{-- <h1 style="text-align: center;  padding:10px; margin:0;">جامع تذکیرالقرآن </h1> --}}
                     {{-- <h4  style="text-align: center; padding:0; margin:0;"> تھری ۔ ایف  پی  او  ایف  واہ  کینٹ</h4> --}}
                     <h2  style="text-align: center; padding:0; margin:0;">  رزلٹ شیٹ </h2>
                     <h4  style="text-align: center; padding:0; margin:0;"> {{ $exam_name }} </h4>
               </th></tr>  
                <tr>
                    <th colspan="2">تاریخ پیدائش: {{ $student_result["dob"] }}</th>
                    <th  colspan="2">  نام: {{ $student_result["name"] }}</th>
                </tr>
                <tr>
                    <th colspan="2">کلاس:{{ $student_result["get_class"]["class"] . " (".$student_result["section"].")" }}  </th>
                    <th colspan="2">والد کا نام: {{ $student_result["father_name"] }} </th>
                </tr>
                <tr>
                    <th>فیصد</th>
                    <th>حاصل کردہ</th>
                    <th>ٹوٹل</th>
                    <th>پرچہ</th>
                </tr>
                @php
                    $total_obtained = 0;
                    $total_marks = 0;

                @endphp
                @foreach ($student_result["marks"] as $getMarks)
                    @php
                        $total_obtained = $total_obtained +  $getMarks["marks"];
                        $total_marks = $total_marks +  $getMarks["create_paper"]["marks"];
                    @endphp
                <tr>
                    <td> {{  number_format($getMarks["marks"] /  $getMarks["create_paper"]["marks"]  * 100, 2) }}</td>
                    <td> {{ $getMarks["marks"] }} </td>
                    <td> {{ $getMarks["create_paper"]["marks"] }} </td>
                    <td>{{ $getMarks["create_paper"]["get_teacher_connected_data"]["get_books"]["book"] }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>{{ number_format( $total_obtained / $total_marks * 100 , 2)}}</td>
                    <td>{{ $total_obtained }}</td>
                    <td> {{  $total_marks  }}</td>
                    <td> </td>
                </tr>
                <tr>
                <td colspan="4" style="text-align: center;">
                   <b> پوزیشن: {{ $positioned_array[$student_result["id"]]["position"] }} </b>
                </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div>................................................................................................</div>
                        <div>................................................................................................</div>
                        <div>................................................................................................</div>
                    </td>
                </tr>
                <tr>
                    <td  style="padding-top: 80px; text-align:center;" colspan="4"> دستخط پرنسپل ۔۔۔۔۔۔۔۔۔۔۔۔۔۔۔</td> 
                 </tr>
            </table>
            
        </section>
@endforeach
