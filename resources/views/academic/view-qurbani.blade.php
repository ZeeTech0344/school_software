@extends('layout.structure')

@section('content')
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}



        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">قربانی دیکھیں</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_employee_other_report"><i
               class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a> --}}

                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                   id="employee_other_reports"><i class="fas fa-download fa-sm text-white-50"></i>Generate Full
                   Report</a> --}}
                    </div>
                </div>
                <div class="card-body">


                    <div class="mb-3">
                        <label for="exampleFormControlInput1"> قربانی کا نام </label>
                        <select name="qurbani_id" id="qurbani_id" class="form-control toselect-tag">
                            <option value=""> قربانی منتخب کریں</option>
                            @foreach ($get_qurbani as $qurbani)
                                <option value="{{ $qurbani->id }}">{{"(".$qurbani->amount.") ".$qurbani->qurbani_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{-- <button type="submit" class="btn btn-primary" onclick="printResults()">پرنٹ کریں</button> --}}
                        <button type="submit" class="btn btn-primary" onclick="getQurbani()">دیکھائیں</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function refreshTableAfterAdmissionYearLoad() {

        }

        function getQurbani() {

            var qurbani_id = $("#qurbani_id").val();
            var qurbani_name = $("#qurbani_id").find(":selected").text();
            var url = "{{ url('view-qurbani-data') }}" + "/" + qurbani_id + "/" + qurbani_name;
            newForListModalView(url);


            // var diary_of_class = $("#diary_of_class").val();
            // var get_student_names = $("#get_student_names").val();
            // var diary_date = $("#diary_date").val();
            // var url = "{{ url('get-diary') }}"+"/"+diary_of_class+"/"+get_student_names+"/"+diary_date;
          
        }

        var session = $("#session").val();

        function students() {
            if ($("#diary_of_class").val() !== "") {


                var diary_of_class = $("#diary_of_class").val();
                var session_id = $("#session")[0].value;
                var create_array = diary_of_class.split(',');
                var class_id = create_array[1];
                var section = create_array[2];

                $.ajax({
                    url: "{{ url('get-student-classwise-sectionwise') }}",
                    type: "get",
                    data: {
                        class_id: class_id,
                        section:section,
                        session_id: session_id
                    },
                    success: function(data) {
                        console.log(data);
                        var class_parent = $("#get_student_names")[0];
                        class_parent.innerHTML = "";
                        $(".find-student").select2();
                        $(data).each(function(index, element) {
                            var create_option = document.createElement("option");
                            create_option.innerText = element["name"] + "(" + element["roll_no"] + ")";
                            create_option.value = element["id"];
                            class_parent.appendChild(create_option);
                        });



                    }
                })
            }
        }






        // $.ajax({
        //     url: "{{ url('get-exams') }}",
        //     type: "GET",
        //     data: {
        //         session: session,
        //     },
        //     success: function(data) {

        //         $.each(data, function(index, exam_list_fetch) {
        //             $('#exam_list_get').append('<option value=' + exam_list_fetch["id"] + '>' +
        //                 exam_list_fetch["exam_name"] + '</option>');

        //         });
        //     }
        // });




        // function printResults() {

        //     var exam_class_id = $("#exam_class_id").val();
        //     var exam_section = $("#exam_section").val();
        //     var exam_list_get = $("#exam_list_get").val();
        //     var exam_name = $('#exam_list_get option:selected').text();



        //     if(exam_class_id !== "" && exam_section !== "" && exam_list_get !== "" ){

        //       $.ajax({
        //       headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //         url: "{{ url('print-result-sheets') }}",
        //         type: "POST",
        //         data: {
        //             session: session,
        //             exam_class_id: exam_class_id,
        //             exam_section: exam_section,
        //             exam_list_get: exam_list_get,
        //             exam_name:exam_name
        //         },
        //         success: function(response) {

        //           var printWindow = window.open('', '_blank');
        //                 printWindow.document.write(response);
        //                 setTimeout(function() {
        //                     printWindow.print();
        //                     printWindow.close();
        //                 }, 500);


        //         }
        //     });

        //     }
        // }

        $(".toselect-tag").select2();
    </script>
@endsection
