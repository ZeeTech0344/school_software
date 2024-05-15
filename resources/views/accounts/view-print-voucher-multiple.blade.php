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
                                <label for="">کلاس</label>
                               <select name="class_id" id="class_id" class="form-control">
                                <option value="">کلاس منتخب کریں</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class." (".$class->getDepartments->department.")" }}</option>
                                @endforeach
                               </select>
                            </div>
                            <div class="col  mt-3">
                                <label for=""> مہینہ</label>
                                <input type="month" id="for_the_month" name="for_the_month" class="form-control">
                            </div>

                        
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <input type="button" value="ترتیب دیں" class="btn  btn-secondary mr-3" onclick="reset()">
                            <input type="button" value="پرنٹ" class="btn btn-danger mr-3" id="get_print">
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
            $("#for_the_month").val("");
        }


        $("#get_easypaisa_view").click(function() {

            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;
            // var type = $("#type")[0].value;
            var type = "";



            

        })




        $("#get_print").click(function() {

            var class_id = $("#class_id")[0].value;
            var for_the_month = $("#for_the_month")[0].value;
            var session = $("#session")[0].value;
            

            if (class_id !== "" && for_the_month !== "" && session  !=="" ) {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                    url: "{{ url('print-voucher-multiple') }}",
                    type: 'POST',
                    data:{
                        class_id:class_id,
                        for_the_month:for_the_month,
                        session_id:session
                    },
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


        function refreshTableAfterAdmissionYearLoad() {


        }
    </script>
@endsection
