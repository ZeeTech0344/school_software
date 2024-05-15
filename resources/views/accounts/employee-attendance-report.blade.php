<style>
    #staff_attendance_table th,
    #staff_attendance_table td {
        border: 1px solid rgb(174, 174, 174);
        /* border-collapse: collapse; */
        padding: 10px;
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
        @for ($a = 1; $a <= $numberOfDays; $a++)
            @if ($a == 1)
                <th>سیریل نمبر</th>
                <th>نام</th>
            @endif
            <th>{{ $a }}</th>
        @endfor
        <td>حاضر</td>
        <td>غیر حاضر</td>
        <td>درخواست</td>
        <td>چھٹی</td>
    </tr>

    @foreach ($attendance_data as $data)

        @php
            $total_holiday = 0;
            $total_present = 0;
            $total_absent = 0;
            $total_leave = 0;
        @endphp

        <tr>
            <td>{{ $sr++ }}</td>
            <td>{{ $data->employee_name }}</td>

            @for ($b = 1; $b <= $numberOfDays; $b++)
                @php
                    $check = ''; // Reset $check for each day
                    $send_attendance_status = '';
                @endphp

                @foreach ($data->attendance as $get_data)
                    @php
                        $timestamp = strtotime($get_data->date);
                        $day = date('d', $timestamp);
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
                    <td> - </td>
                @endif
            @endfor
            <td>{{$total_present}}</td>
            <td>{{$total_absent}}</td>
            <td>{{$total_leave}}</td>
            <td>{{$total_holiday}}</td>
        </tr>
    @endforeach
</table>
