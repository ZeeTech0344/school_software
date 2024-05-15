@extends('layout.structure')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary"> سٹاف حاضری رپورٹ</h6>
                </div>
                <div class="card-body">
                    <div>


                        <div>

                            <div class="col">
                                <label for="">مہینہ</label>
                                <input type="month" id="for_the_month" name="for_the_month" class="form-control">
                            </div>

                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <input type="button" value="ترتیب دیں" class="btn  btn-secondary mr-3" onclick="reset()">

                            <input type="button" value="رپورٹ دیکھائیں" class="btn btn-primary mr-3" id="get_report">
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
            $("#from_date").val("");
            $("#to_date").val("");
        }


        $("#get_report").click(function() {

            var for_the_month = $("#for_the_month").val();

            var url = "{{ url('employee-attendence-report') }}" + "/" + for_the_month;
            extraLargeModal(url);
        })




        $("#get_print").click(function() {

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            // var type = $("#type")[0].value;
            var type = "";

            if (from_date !== "" && to_date !== "") {
                $.ajax({
                    url: "{{ url('print-view-report-locker-amount') }}" + "/" + from_date + "/" + to_date +
                        "/" + type,
                    type: 'GET',
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

        })





        $(".toselect-tag").select2();

        function refreshTableAfterAdmissionYearLoad() {


        }
    </script>
@endsection
