@extends('layout.structure')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-lg-4 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">ایزی پیسہ اداکردہ لسٹ</h6>
                </div>
                <div class="card-body">
                    <div>


                        <div>

                            <div class="col">
                                <label for="">تاریخ سے</label>
                                <input type="date" id="from_date" name="from_date" class="form-control">
                            </div>
                            <div class="col  mt-3">
                                <label for="">تاریخ تک</label>
                                <input type="date" id="to_date" name="to_date" class="form-control">
                            </div>

                            {{-- <div class="col mt-2">
                            <select class="form-control" name="type" id="type">
                                <option value="">Select Type</option>
                                <option>Advance</option>
                                <option>Salary</option>
                                <option>Pending</option>
                                <option>Others</option>
                            </select>
                        </div> --}}


                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <input type="button" value="ترتیب دیں" class="btn  btn-secondary mr-3" onclick="reset()">
                            <input type="button" value="پرنٹ" class="btn btn-danger mr-3" id="get_print">
                            <input type="button" value="رپورٹ دیکھائیں" class="btn btn-primary mr-3"
                                id="get_easypaisa_view">
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


        $("#get_easypaisa_view").click(function() {

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            // var type = $("#type")[0].value;
            

            if (from_date !== "" && to_date !== "") {
                var url = "{{ url('view-report-easypaisa-amount') }}" + "/" + from_date + "/" + to_date;
                viewModal(url);
            }

        })




        $("#get_print").click(function() {

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            // var type = $("#type")[0].value;
            var type = "";

            if (from_date !== "" && to_date !== "") {
                $.ajax({
                    url: "{{ url('print-view-report-easypaisa-amount') }}" + "/" + from_date + "/" + to_date + "/" + type,
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
