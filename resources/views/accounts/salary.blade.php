@extends('layout.structure')

@section('content')
    <div>
        <div class="col-12 d-flex justify-content-center" style="padding:1.5rem;">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                        <h6 class="m-0 font-weight-bold text-primary" style="text-align: left;">سیلری</h6>
                        <div>
                            <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2" id="get-paid-pdf">
                                پرنٹ</a>
                            <a href="#" class="d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2"
                                id="get-unpaid-report"> بغیر اداکردہ سیلری</a>

                            <a href="#" class="d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"
                                id="get-paid-report">اداکردہ سیلری</a>

                            <a href="#" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"
                                id="get-salary-detail">سیلری رپورٹ</a>



                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
                            <div class="row p-2 d-flex justify-content-center">
                                <div class="col col-4">
                                    <input type="month" id="month" name="month" class="form-control">
                                </div>


                                <div>
                                    <input type="button" value="Generate" class="btn btn-primary" id="generate-salary">
                                    <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
                                </div>
                            </div>
                            {{-- </form> --}}

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="pt-0 pb-0 d-flex justify-content-end" style="padding:1.5rem;">

        <input type="text" id="search" name="search" placeholder="Search Employee......."
            onchange="checkValues(this)" class="form-control w-25">

    </div>
    {{-- <div> --}}

    <div class="table-responsive" style="padding:22px;">
        <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ایکشن</th>
                    <th>سیلری</th>
                    <th>پوسٹ</th>
                    <th>نام</th>
                    <th>ایمپلائی نمبر</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
@endsection


@section('script')
    <script>
        function refreshTableAfterAdmissionYearLoad() {

            //this function is only define because its code in footer and error is occured due to not defined

        }

        var employee_salary_table = $('.table_employee_other').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            order: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-data-of-employee-salary') }}",
                data: function(d) {
                    d.month = $("#month").val()
                }
            },
            columns: [

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'salary',
                    name: 'salary'
                },
                {
                    data: 'post',
                    name: 'post'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'employee_no',
                    name: 'employee_no'
                }
            ],
            success: function(data) {
                console.log(data);
            }
        });

       


        $("#generate-salary").click(function() {

            employee_salary_table.draw();

        })

        function reset() {
            $("#month").val('');
            employee_salary_table.draw();
        }


        $("#get-paid-report").click(function() {

            var month = $("#month")[0].value;
            if (month !== "") {
                console.log("yes");
                var url = "{{ url('get-paid-salary') }}" + "/" + month;
                viewModal(url);
            }

        })


        $("#get-unpaid-report").click(function() {

            var month = $("#month")[0].value;
            if (month !== "") {
                var url = "{{ url('get-salary-upaid-detail') }}" + "/" + month;
                viewModal(url);
            }

        })



        $("#get-salary-detail").click(function() {
            var month = $("#month")[0].value;
            if (month !== "") {
                console.log("yes");
                var url = "{{ url('get-salary-detail') }}" + "/" + month;
                viewModal(url);
            }
        })

        $(document).on("click", "#get-paid-pdf", function() {
            var salary_month = $("#month")[0].value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-salary-pdf') }}",
                type: "POST",
                data: {
                    month: salary_month,
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

        })


        // $("#get-paid-pdf").click(function(){


        // var salary_month = $("#month")[0].value;

        // if (salary_month !== "") {

        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('get-salary-pdf') }}",
        //         type: "POST",
        //         data: {
        //             month: salary_month,
        //         },
        //         success: function(data) {
        //             const pdfData = data[0];
        //             // Create a blob object from the base64-encoded data
        //             const byteCharacters = atob(pdfData);
        //             const byteNumbers = new Array(byteCharacters.length);
        //             for (let i = 0; i < byteCharacters.length; i++) {
        //                 byteNumbers[i] = byteCharacters.charCodeAt(i);
        //             }
        //             const byteArray = new Uint8Array(byteNumbers);
        //             const blob = new Blob([byteArray], {
        //                 type: 'application/pdf'
        //             });


        //             // Create a URL for the blob object
        //             const url = URL.createObjectURL(blob);

        //             // Create a link element with the URL and click on it to download the PDF file
        //             const link = document.createElement('a');
        //             link.href = url;
        //             link.download = 'easypaisa_paid_detail_list.pdf';
        //             document.body.appendChild(link);
        //             link.click();
        //         }
        //     })

        // }


        // })






        $(document).on("click", ".pay_now_salary", function() {

            var get_data = $(this).data("id").split(",");
            if (get_data[6] !== "") {
                var url = "{{ url('pay-now-salary') }}" + "/" + get_data[0] + "/" + get_data[1] + "/" + get_data[
                    2] + "/" + get_data[3] + "/" + get_data[4] + "/" + get_data[5];
                payNowModalBody(url);
            } else {
                alert("Please updated joining date of this employee!");
            }


        })




        $("#search").keyup(function() {

            var value = this.value.toLowerCase().trim();

            $(".table_employee_other tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });
    </script>
@endsection
