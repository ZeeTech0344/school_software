@extends('layout.structure')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary"> داخلہ رپورٹ</h6>
                </div>
                <div class="card-body">
                    <div>


                        <div>

                            <div class="col  mt-3">
                                <label for=""> اس تاریخ سے </label>
                                <input type="date" id="from_date" name="from_date" class="form-control">
                            </div>


                            <div class="col  mt-3">
                                <label for=""> اس تاریخ تک </label>
                                <input type="date" id="to_date" name="to_date" class="form-control">
                            </div>

                            <div class="col">
                                <label for="">کلاس</label>
                                <select name="class_id" id="class_id" class="form-control toselect-tag">
                                    <option value="">کلاس منتخب کریں</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class . ' (' . $class->getDepartments->department . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="">سیکشن</label>
                                <select name="section" id="section" class="form-control">
                                    <option value="">سیکشن منتخب کریں</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section }}">{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="">رپورٹ</label>
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="1">آن</option>
                                    <option value="2">رخصت</option>
                                    <option value="0">آف</option>
                                </select>
                            </div>




                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <input type="button" value="ترتیب دیں" class="btn  btn-secondary mr-3" onclick="reset()">
                            <input type="button" value="پرنٹ" class="btn btn-danger mr-3" onclick="printAdmissions()">
                            <input type="button" value="ویو" class="btn btn-success mr-3" id="get_view">
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        function reset() {
            $("#class_id").val("");
            $("#section").val("");
            $("#from_date").val("");
            $("#to_date").val("");
            $("#report_type").val("");
        }


        $("#get_easypaisa_view").click(function() {

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            // var type = $("#type")[0].value;
            var type = "";





        })




        $("#get_view").click(function() {
            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            var class_id = $("#class_id")[0].value;
            var section = $("#section")[0].value;
            var report_type = $("#report_type")[0].value;
            var session = $("#session")[0].value;

            var data = {
                from_date: from_date,
                to_date: to_date,
                class_id: class_id,
                section: section,
                report_type: report_type,
                session: session
            };

            var url = "{{ url('get-admission-report') }}";
            newForListModalView(url, data);
        })






        function printAdmissions() {


            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            var class_id = $("#class_id")[0].value;
            var section = $("#section")[0].value;
            var report_type = $("#report_type")[0].value;
            var session = $("#session")[0].value;


            var data = {
                from_date: from_date,
                to_date: to_date,
                class_id: class_id,
                section: section,
                report_type: report_type,
                session: session
            };


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('print-admissions') }}",
                    type: "POST",
                    data: {data},
                    success: function(response) {

                        var printWindow = window.open('', '_blank');
                        printWindow.document.write(response);
                        setTimeout(function() {
                            printWindow.print();
                            printWindow.close();
                        }, 500);


                    }
                });

           



        }



        function refreshTableAfterAdmissionYearLoad() {


        }


        $(".toselect-tag").select2();
    </script>
@endsection
