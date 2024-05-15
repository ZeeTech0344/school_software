@extends('layout.structure')
@section("content")
<div>
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}

        <div class="col-lg-8 col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">ادھار لسٹ</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="get_pdf"><i
                    class="fas fa-download fa-sm text-white-50"></i> PDF</a> --}}
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            id="generate_pending_report"><i class="fas fa-download fa-sm text-white-50"></i> Generate
                            Full Report</a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <div class="mb-3 d-flex">
                            
                            <input type="date" class="form-control mr-3" id="to_date_pending" name="to_date_pending" onchange="checkVal(this)">
                            <input type="date" class="form-control" id="from_date_pending" name="from_date_pending" onchange="checkVal(this)">
                        </div>


                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_value" name="search_value" placeholder="سٹاف سرچ کریں">
                        </div>
                        <table class="table table-bordered datatable_pending" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ایکشن</th>
                                    <th>ریمارکس</th>
                                    <th>سٹیٹس</th>
                                    <th>رقم</th>
                                    <th>سٹاف</th>
                                    <th>تاریخ</th>
                                </tr>
                            </thead>

                            <tbody>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">ادھار فارم(سٹاف)</h6>
                    <div>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            id="add-employee-others-forms">Employee/Others</a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <form id="pending-form" class="data-form">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">تاریخ</label>
                            <input type="date" class="form-control" name="date" id="date"
                            onchange="removeBorder(this)">
                        </div>

                       
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">سٹاف</label>
                            <select class="form-control toselect-tag" id="employee_id" style="width:100%;"
                                name="employee_id"  onchange="removeBorder(this)">
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">رقم</label>
                            <input type="input" class="form-control" id="amount" name="amount"
                            onchange="removeBorder(this)">
                        </div>
                        <div class="form-group" id="convert_to_number">
                         
                        </div>

                        
                        <div class="form-group">
                            <label for="exampleFormControlInput1">ریمارکس</label>
                            <input type="input" class="form-control" id="remarks" name="remarks"
                                onchange="removeBorder(this)">
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="hidden_id" id="hidden_id">
                    </form>

                </div>

            </div>
        </div>


        {{-- </div> --}}
    </div>
</div>


@endsection
@section("script")
<script>

function removeBorder(e) {
            e.style.border = "";
            if (e.id == "image") {
                $("#image_name").attr("src", e.value);
            }
}


    function getEmployees() {

        var parent = $("#employee_id")[0];
        parent.innerHTML = "";

      
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('get-employee') }}",
            type: "get",
            success: function(data) {

                $.each(data, function(key, value) {
              
                    var create_option = document.createElement("option");
                    create_option.value = value["id"];
                    create_option.innerText = value["employee_post"] + "-" +value["employee_name"] ;
                    parent.appendChild(create_option);
                });

            }
        })
    }

    getEmployees();
    

    var pending_table = $('.datatable_pending').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        // paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('get-pending-list') }}",
            data: function(d) {
                d.search_value = $("#search_value").val();
                d.from_date_pending = $("#from_date_pending").val();
                d.to_date_pending = $("#to_date_pending").val();
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
            data: 'remarks',
            name: 'remarks'
        },
       
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'amount',
            name: 'amount'
        },
        {
            data: 'employee_id',
            name: 'employee_id'
        },
        {
            data: 'date',
            name: 'date'
        }

        ],

        success: function(data) {

        }
    });



    $("#search_value").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            pending_table.draw();
        }
    });


    function checkVal(e){
   var from_date = $("#from_date_pending")[0].value;
   var to_date = $("#to_date_pending")[0].value;

   if(from_date!=="" && to_date!==""){
    pending_table.draw();
   }
 }



    // $(document).on("click", "#create-closing", function() {

    //     table.draw();

    //     var value = $("#date")[0].value;

    //     $("#get_pdf").attr("data-date",value);


    // })



    $(document).on("click", ".edit-pending-amount", function() {
        var id = $(this).data("id");
        $.ajax({
            url: "{{ url('edit-pending-amount') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(data) {

                $("#date")[0].value = data["date"];
                $("#location").val(data["branch_id"]).trigger("change");
                $("#employee_id")[0].value = data["employee_id"];
                $("#amount")[0].value = data["amount"];
                $("#remarks")[0].value = data["remarks"];
                $("#hidden_id").val(data["id"]);

            }
        })


    })



    $(document).on("click", ".delete-pending-amount", function() {
        var id = $(this).data("id");

        var element = this;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('delete-pending') }}",
            type: "get",
            data: {
                id: id
            },
            success: function(data) {
                $(element).parent().parent().parent().parent().fadeOut();

            }
        })

    })




    $(".toselect-tag").select2();
    var heads = $("#heads");
    var locations = $("#locations");


    $('#pending-form').validate({
        errorPlacement: function(error, element) {
            element[0].style.border = "1px solid red";
        },
        rules: {
            date: "required",
            employee_id: "required",
            amount: "required",
            remarks: "required",
        },

        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('insert-pending') }}",
                type: "POST",
                data: formData,

                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    pending_table.draw();

                    $('#pending-form')[0].reset();
                    $("#hidden_id").val("");
                    successAlert();

                },
                error: function(data) {
                    console.log(data);
                    

                }

            })
        }
    });

    
    function checkAmt(e) {
        
        $("#convert_to_number")[0].innerText=numberToWords(e.value);
    }

    // $(douc)

    // add-easypaisa-form

    $(document).on("click", "#add-easypaisa-form", function() {

        var url = "{{ url('add-easypaisa-form') }}";
        viewModal(url);

    })


    $(document).on("click", "#add-employee-others-forms", function() {

        var url = "{{ url('add-employee-others-form') }}";
        viewModal(url);

    })


    $(document).on("click", "#get-saqah-form", function() {

        var url = "{{ url('add-sadqah') }}";
        viewModal(url);

    })



    $(document).on("click", "#generate_pending_report", function() {

        var url = "{{ url('/generate-full-pending-report') }}";
        viewModal(url);

    })



    $(document).on("click", ".pay_now_pending", function() {

        var data = $(this).data("id").split(",");
        // data[0] and data[1] we split array through data-id
        var url = "{{ url('pay-now') }}" + "/" + data[0] + "/" + data[1] + "/" + data[2] +  "/" + data[3];
        payNowModalBody(url);
       
        
    })

    //         $(document).ready(function() {
    //   // Get the current date
    //   var today = new Date().toISOString().split('T')[0];

    //   console.log(new Date().toISOString());
    //   // Set the min and max attributes of the input field
    //   $('#date').attr('min', today);
    //   $('#date').attr('max', today);
    // });

    $(document).ready(function() {
        var currentDate = new Date();
        //   currentDate.setDate(currentDate.getDate() - 1);
        currentDate.setDate(currentDate.getDate());
        var pastDay = currentDate.toISOString().split('T')[0];
        $('#date').attr('min', pastDay);
        $('#date').attr('max', pastDay);

        console.log(currentDate);
    });


</script>
@endsection


