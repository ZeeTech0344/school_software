<table style="width:100%;" class="bg-primary text-white">
    <tr>
        <td class="p-2 border">
            @php
                $today_date = date('Y-m-d');

                $currentDate = new DateTime($today_date);

                // Another date (replace with your desired date)
                $otherDate = new DateTime($joining);

                // Calculate the difference
                $dateInterval = $currentDate->diff($otherDate);

                if ($dateInterval->invert == 1 && $dateInterval->days < 45) {
                    echo "<b style='color:rgb(113, 18, 18)' class='horizontal-shake'>(" . $dateInterval->days . ') Days Alert!</b>';
                }
            @endphp
        </td>
        <td class="p-2 border">
            {{ ' (' . $employee_post . ')' . $name }} : نام
        </td>

    </tr>
    <tr>
        <td class="p-2 border">
            {{ date_format(date_create($joining), 'd-m-Y') }} : شمولیت

        </td>
        <td class="p-2 border">
            {{ $salary }}: سیلری
        </td>
    </tr>
</table>

@php

    $firstDay = $date;
    $lastDayOfMonth = date('Y-m-t', strtotime($date));

    if ($lastDayOfMonth <= date('Y-m-d')) {
        $firstDayOfMonth = strtotime($date);
        $month = date('m', $firstDayOfMonth);
        $year = date('Y', $firstDayOfMonth);

        // Calculate the first Friday of the month
        $firstFriday = strtotime("first friday of $year-$month");

        // Calculate the last Friday of the month
        $lastFriday = strtotime("last friday of $year-$month");

        // Calculate the number of Fridays
        $numFridays = ceil(($lastFriday - $firstFriday) / (7 * 24 * 60 * 60)) + 1;

    }else{

        $currentDate = new DateTime();

        // Get the first day of the current month
        $firstDayOfMonth = new DateTime(date('Y-m-01'));

        // Calculate the difference in days between the first day of the month and today
        $daysDifference = $firstDayOfMonth->diff($currentDate)->days;

        // Calculate the number of Fridays within this time period
        $numFridays = floor($daysDifference / 7) + 1;

    }

    // $startDate = new DateTime($firstDayOfMonth); // Replace with your start date
    // $endDate = new DateTime($lastDayOfMonth); // Replace with your end date

    // $interval = $startDate->diff($endDate);
    // $numberOfDays = $interval->days + 1;

    $today_days_of_attendance = $get_attendance + $numFridays;

@endphp

<form id="pay-now-salary-form" class="data-form">



    <div class="form-group">
        <label for="exampleFormControlInput1">ایڈوانس</label>
        <input type="input" class="form-control" name="advance" readonly id="advance">
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1">ٹوٹل پینڈنگ</label>
        <input type="input" class="form-control" name="pendings" readonly id="pendings">
    </div>

    {{-- <div class="form-group">
        <label for="exampleFormControlInput1">Fuel Amount</label>
        <input type="input" class="form-control" name="fuel_amount" readonly id="fuel_amount">

    </div> --}}



    <div class="row mt-2">
        <div class="col">
            <label for="exampleFormControlInput1"> منفی کریں</label>
            <input type="text" class="form-control" placeholder="Amount Deduction" name="day_of_work_deduction"
                value="0" id="day_of_work_deduction" onkeyup="calculateDeducation()">
        </div>
        <div class="col">
            <label for="exampleFormControlInput1">جمع کریں</label>
            <input type="input" class="form-control" name="addition" value="0" id="addition"
                onkeyup="calculateAddition()">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <label for="exampleFormControlInput1">کتنے دن کام کیا </label>
            <input type="number" class="form-control" name="day_of_work" onkeyup="CalculatePerDaySalary()"
                id="day_of_work" value="{{ $today_days_of_attendance }}">
        </div>
        <div class="col">
            <label for="exampleFormControlInput1">ایک دن کی سیلری</label>
            <input type="number" class="form-control" name="per_day_salary" id="per_day_salary"
                value="{{ round($salary / $today_days_of_attendance, 2) }}" disabled>

        </div>
    </div>

    <div class="form-group mt-2">
        <label for="exampleFormControlInput1">دن x ایک دن کی سیلری</label>
        <input type="number" class="form-control" name="calculate_salary_per_day" id="calculate_salary_per_day"
            disabled>
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1">پوری سیلری (تمام کٹوتی کے بعد)</label>
        <input type="input" class="form-control" name="salary" id="salary" readonly>
        <input type="hidden" class="form-control" name="salary_hidden" id="salary_hidden" readonly>
    </div>


    <div class="form-group">
        <label for="exampleFormControlInput1">ریمارکس</label>
        <input type="input" class="form-control" name="remarks" id="remarks" onkeyup="calculateAddition()">
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1">ادایٗیگی اکاونٹ </label>
        <select class="form-control" name="pay_through">
            <option value="Easypaisa">ایزی پیسہ</option>
            <option value="Locker">لاکر</option>
        </select>
    </div>


    <div class="form-group d-flex justify-content-end">
        <input type="submit" value="Pay" class="btn btn-primary">
    </div>
    <input type="hidden" name="employee_id" value="{{ $id }}">
    <input type="hidden" name="paid_for_month" value="{{ $date }}">
    {{-- <input type="hidden" name="branch" value="{{ $branch }}"> --}}
    <input type="hidden" name="basic_salary" value="{{ $salary }}">
    <input type="hidden" name="get_advance" id="get_advance">
</form>


<script>
    var number_of_days = "<?php echo $today_days_of_attendance; ?>";

    function calculateDeducation() {
        var day_of_work_deduction = $("#day_of_work_deduction")[0].value;
        var salary_hidden = $("#salary_hidden")[0].value;
        // var salary = $("#salary").val(salary_hidden - day_of_work_deduction);


        var day_of_work = $("#day_of_work")[0].value;
        var per_day_salary = $("#per_day_salary")[0].value;

        var remaining_days = number_of_days - day_of_work;

        var total_amount_after_remaining_days = remaining_days * per_day_salary;

        var calculate_salary_per_day = $("#calculate_salary_per_day").val(Math.round(day_of_work * per_day_salary));
        var salary_hidden = $("#salary_hidden")[0].value;

        $("#salary").val(Math.round(salary_hidden - total_amount_after_remaining_days) - parseInt(
            day_of_work_deduction));





    }

    function CalculatePerDaySalary() {

        var day_of_work = $("#day_of_work")[0].value;
        var per_day_salary = $("#per_day_salary")[0].value;

        var remaining_days = number_of_days - day_of_work;

        var total_amount_after_remaining_days = remaining_days * per_day_salary;

        var calculate_salary_per_day = $("#calculate_salary_per_day").val(Math.round(day_of_work * per_day_salary));
        var salary_hidden = $("#salary_hidden")[0].value;
        $("#salary").val(Math.round(salary_hidden - total_amount_after_remaining_days));

    }


    function calculateAddition() {
        var addition = $("#addition")[0].value;
        var salary_hidden = $("#salary_hidden")[0].value;

        var day_of_work = $("#day_of_work")[0].value;
        var per_day_salary = $("#per_day_salary")[0].value;

        var remaining_days = number_of_days - day_of_work;

        var total_amount_after_remaining_days = remaining_days * per_day_salary;

        var calculate_salary_per_day = $("#calculate_salary_per_day").val(Math.round(day_of_work * per_day_salary));
        var salary_hidden = $("#salary_hidden")[0].value;

        $("#salary").val(Math.round(salary_hidden - total_amount_after_remaining_days) + parseInt(addition));


        // var salary = $("#salary").val((parseInt(salary_hidden) + parseInt(addition)));

    }

    $('#pay-now-salary-form').validate({
        errorPlacement: function(error, element) {
            // element[0].style.border = "1px solid red";
        },
        rules: {
            pay_through: "required",
        },

        submitHandler: function(form) {
            var check_salary = $("#salary")[0].value;
            if (check_salary < 0) {
                alert("Invalid Salary Amount");
                return false;
            } else {
                if (confirm('Paid salary! Are you sure')) {
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('final-salary-insert') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            $(".paynow-close")[0].click();
                            employee_salary_table.draw();
                            successAlert();

                        },
                        error: function(data) {



                        }

                    })
                }

            }





        }

    });






    //for getting advance and deduct from salary


    $(document).ready(function() {

        var id = '<?php echo $id; ?>';

        var date = '<?php echo $date; ?>';

        var salary = '<?php echo $salary; ?>';


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('check-advance-salary') }}",
            type: "POST",
            data: {
                id: id,
                date: date
            },
            success: function(data) {

                //console.log(data);
                $("#advance").val(data[0]);
                $("#get_advance").val(data[0]);
                // $("#salary").val(salary - data[0]);
                // $("#salary_hidden").val(salary - data[0]);

                getPending(salary - data[0]);
            }

        })


        function getPending(get_total_advance_plus_salary) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('check-pendings') }}",
                type: "POST",
                data: {
                    id: id,
                    date: date
                },
                success: function(data) {

                    console.log(data)

                    var pending_discount = data[0] - (data[0] / 100 * 10);

                    $("#pendings").val(pending_discount);

                    //console.log(get_total_advance_plus_salary);

                    //checkRiderAmount(get_total_advance_plus_salary - pending_discount)

                    // checkRiderAmount()

                    // console.log(get_total_advance_plus_salary);
                    $("#salary").val(get_total_advance_plus_salary - pending_discount);
                    $("#salary_hidden").val(get_total_advance_plus_salary - pending_discount);

                }

            })


        }

        getPending();


        // function checkRiderAmount(get_fina_amount_upper){

        //     $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     url: "{{ url('check-riders-amounts') }}",
        //     type: "POST",
        //     data: {
        //         id: id,
        //         date: date
        //     },
        //     success: function(data) {

        //         var final_sarlary = get_fina_amount_upper-(data[0] == null ? 0 : data[0] );

        //         console.log(final_sarlary);

        //         $("#fuel_amount").val(data[0] == null ? 0 : data[0]);

        //         $("#salary").val(final_sarlary);
        //         $("#salary_hidden").val(final_sarlary);

        //     }

        // })
        // }




    });
</script>
