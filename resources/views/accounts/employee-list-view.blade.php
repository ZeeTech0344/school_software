




@extends('layout.structure')

@section('content')
   

<div class="col-lg-12 col-sm-12">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
            <h6 class="m-0 font-weight-bold text-primary">سٹاف لسٹ</h6>
            <div>
                {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_employee_other_report"><i
           class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a> --}}

                {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
               id="employee_other_reports"><i class="fas fa-download fa-sm text-white-50"></i>Generate Full
               Report</a> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="mb-3">
                    <input type="text" class="form-control" id="search" name="search"
                        placeholder="سٹاف سرچ کریں۔۔۔۔۔۔۔۔۔">
                </div>
                
                <div class="d-flex justify-content-end">
                
                    <button onclick="sendData()" class="btn btn-success mb-2 mr-2">کارڈ بنائیں </button>
                
                </div>
                <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                       
                            <tr>
                                <th>ایکشن</th>
                                <th>سٹیٹس</th>
                                <th>لاگ ان</th>
                                <th>سیلری</th>
                                <th>شناختی کارڈ نمبر</th>
                                <th>فون نمبر</th>
                                <th>نام</th>
                                <th>منتخب</th>
                            </tr>
                        
                    </thead>

                    <tbody>
                    </tbody>
                </table>
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
                url: "{{ url('employee-list') }}",
                data: function(d) {
                    // d.month = $("#month").val()
                }
            },
            columns: [

                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
                {
                    data: 'view_only',
                    name: 'view_only'
                },
                {
                    data: 'employee_status',
                    name: 'employee_status'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'salary',
                    name: 'salary'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'employee_name',
                    name: 'employee_name'
                },
                {
                    data: 'check_box',
                    name: 'check_box'
                }
            ],
            success: function(data) {
                console.log(data);
            }
        });





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


        $(document).on("click", ".view_profile", function(){

            var id = $(this).data("id");
            var url = "{{ url('view-employee-profile') }}" + "/" + id;
            payNowModalBody(url);

        })








        

        function sendData() {

var checkboxes = $('input[type="checkbox"]:checked');
var checkboxValues = checkboxes.map(function() {
    return this.value;
}).get();

var data = {
    checkboxValues: checkboxValues,
}

sendAjax(data);
}

function sendAjax(data) {
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: 'POST',
    url: '{{ url("employee-id-card") }}', // Replace with your server endpoint
    data: JSON.stringify(data),
    contentType: 'application/json',
    success: function(response) {

        //console.log(response);

        var printWindow = window.open('', '_blank');
        printWindow.document.write(response);
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500); 

    },
    error: function() {
        console.error('AJAX request failed.');
    }
});

}






    </script>
@endsection

