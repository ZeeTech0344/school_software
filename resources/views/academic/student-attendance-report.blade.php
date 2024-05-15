<style>
    #staff_attendance_table th,
    #staff_attendance_table td {
        border: 1px solid rgb(186, 186, 186);
        /* border-collapse: collapse; */
        padding: 10px;
        text-align: center;

    }

    #staff_attendance_table {
        width: 100%;
    }
</style>

@php
    $sr = 1;
 
@endphp

<table id="staff_attendance_table" dir="rtl">
    <tr>
        @for ($a = $startAttendanceThisDate; $a <= $endAttendanceThisDate; $a++)
            @if ($a == $startAttendanceThisDate)
                <th>سیریل نمبر</th>
                <th>نام</th>
            @endif

           
          
            @php
                if($a<10){
                    $a = "0".$a;
                }else{
                    $a = $a;
                }

             
            $mark_today_date = date("Y-m")."-".$a;
            $today_date = date("Y-m-d");
            @endphp

            @if($mark_today_date ==  $today_date)
            <th style="background: #4e73df">{{$a}}</th>
            @else
            <th>{{ $a }}</th>
            @endif

        @endfor
            <th>ٹوٹل حاضری</th>
            <th>ٹوٹل غیر حاضر </th>
            <th>ٹوٹل چھٹی</th>
            <th>ٹوٹل درخواست</th>
    </tr>

    @foreach ($attendance_data as $data)

        {{-- count record --}}

        @php
             $total_present = 0;
             $total_absent = 0;
             $total_holiday = 0;
             $total_leave = 0;
        @endphp 

        <tr>
            <td>{{ $sr++ }}</td>
            <td> {{ $data->name }}</td>

            @for ($b = $startAttendanceThisDate; $b <= $endAttendanceThisDate; $b++)
                @php
                    $check = ''; // Reset $check for each day
                    $send_attendance_status = '';
                @endphp

                @foreach ($data->attendance as $get_data)
                    @php
                        $timestamp = strtotime($get_data->date);
                        $day = date('d', $timestamp);
                        $day_name = date('l', $timestamp);
                    @endphp

                    @if ($day == $b)
                        @php
                            $check = 'match';
                            $send_attendance_status = $get_data->attendance_type;
                        @endphp
                    @endif
                @endforeach

                @if ($check == 'match')
                    <td>
                        @if ($send_attendance_status == 'present')
                            حاضر
                        @php
                            $total_present = $total_present + 1;
                        @endphp
                        @elseif($send_attendance_status == 'absent')
                            غیر حاضر

                            @php
                            $total_absent = $total_absent + 1;
                            @endphp

                        @elseif($send_attendance_status == 'holiday')
                            چھٹی
                            @php
                            $total_holiday = $total_holiday + 1;
                            @endphp
                            @elseif($send_attendance_status == 'leave')
                            درخواست
                            @php
                            $total_leave = $total_leave + 1;
                            @endphp
                        @endif
                    </td>
                @else

                    {{-- //if attendance is not mark the it also consider absent --}}
                    @php
                        $total_absent = $total_absent + 1
                    @endphp
                    <td style="color:red">  غیر حاضر </td>
                @endif
            @endfor
            <td>{{$total_present}}</td>
            <td>{{$total_absent}}</td>
            <td>{{$total_holiday}}</td>
            <td>{{$total_leave}}</td>
        </tr>
    @endforeach
</table>



