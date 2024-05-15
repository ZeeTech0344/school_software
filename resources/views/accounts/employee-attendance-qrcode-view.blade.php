@extends('layout.structure')

@section('content')
    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                <h6 class="m-0 font-weight-bold text-primary">سٹاف حاضری بذریعہ کیو آر کوڈ</h6>
                <div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <div style="width: 500px" id="reader"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        function refreshTableAfterAdmissionYearLoad() {

            //this function is only define because its code in footer and error is occured due to not defined

        }



        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 100,
                qrbox: 350
            });


        var sentEmployee = [];

        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            // console.log(`Scan result: ${decodedText}`, decodedResult);

            var dataForAttendance = [];

            var result = decodedText;

            var date_get_for_time = new Date(); // Current date and time

            var options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                fractionalSecondDigits: 3,
            };

            var formattedTime = new Intl.DateTimeFormat('en-US', options).format(date_get_for_time);

            var year = date_get_for_time.getFullYear();
            var month = (date_get_for_time.getMonth() + 1).toString().padStart(2,
            '0'); // Adding 1 because months are zero-based
            var day = date_get_for_time.getDate().toString().padStart(2, '0');

            var formattedDate = `${year}-${month}-${day}`;

            var employee_id = result;

            dataForAttendance.push({
                employee_id: employee_id,
                date: formattedDate,
                time_in: formattedTime,
                attendance_type: "present"
            })


            

            const insert_update_attendance = {
                insert_attendance: dataForAttendance,
                update_attendance: [],
                qr_attendance: true,
            }

            
            var index = sentEmployee.indexOf(employee_id);

            if (index == -1){
                sentEmployee.push(employee_id);
            }else{
               return false;
            }

            console.log(insert_update_attendance);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ url('employee-list-attendence-insert') }}",
                data: JSON.stringify(insert_update_attendance),
                contentType: 'application/json',
                success: function(response) {

                    successAlert();
                    current_value = response;
                },
                error: function(error) {

                    errorAlert();
                    current_value = error.responseJSON[0].student_id;

                }
            });

        }



        html5QrcodeScanner.render(onScanSuccess);



        // var html5QrcodeScanner = new Html5QrcodeScanner(
        //     "reader", {
        //         fps: 10,
        //         qrbox: 250
        //     });

        // function onScanSuccess(decodedText, decodedResult) {
        //     var dataForAttendance = [];

        //     var result = decodedText;
        //     var result_array = result.split(',');

        //     var date_get_for_time = new Date();

        //     var options = {
        //         hour: '2-digit',
        //         minute: '2-digit',
        //         second: '2-digit',
        //         fractionalSecondDigits: 3,
        //     };

        //     var formattedTime = new Intl.DateTimeFormat('en-US', options).format(date_get_for_time);

        //     var year = date_get_for_time.getFullYear();
        //     var month = (date_get_for_time.getMonth() + 1).toString().padStart(2, '0');
        //     var day = date_get_for_time.getDate().toString().padStart(2, '0');

        //     var formattedDate = `${year}-${month}-${day}`;

        //     var student_id = result_array[0];
        //     var session_id = result_array[1];

        //     dataForAttendance.push({
        //         student_id: student_id,
        //         date: formattedDate,
        //         time_in: formattedTime,
        //         attendance_type: "present"
        //     });

        //     const insert_update_attendance = {
        //         insert_attendance: dataForAttendance, // Use dataForAttendance instead of checkedValues
        //         update_attendance: []
        //     };

        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: 'POST',
        //         url: "{{ url('student-attendence-insert') }}",
        //         data: JSON.stringify(insert_update_attendance),
        //         contentType: 'application/json',
        //         success: function(response) {
        //             getStudents();
        //             successAlert();
        //         },
        //         error: function() {
        //             console.error('AJAX request failed.');
        //         }
        //     });
        // }

        // html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
