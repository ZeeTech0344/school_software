@extends('layout.structure') 

@section('content')



<div class="col-12 d-flex justify-content-center">

    {{-- <div class="col-lg-6 col-sm-12"> --}}

  

    <div class="col-lg-6 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                <h6 class="m-0 font-weight-bold text-primary">بینک اخراجات لسٹ</h6>
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
                    <div class="mb-3 d-flex">
                        <input type="text" class="form-control" id="search_value" name="search_value" placeholder="سرچ کریں">
                        <select name="search_bank_name" id="search_bank_name" autofocus  class="form-control ml-2"  onkeyup="validateError(this)">
                            <option value="">منتخب کریں</option>
                            @foreach($bank_name as $bank)
                            <option value="{{ $bank }}">{{$bank}}</option>
                            @endforeach
                          </select>
                       
                    </div>
                    
                    <table class="table table-bordered employee_front_table" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>ایکشن</th>
                                
                                <th>ریمارکس</th>
                                <th>رقم</th>
                                {{-- <th>مقصد</th> --}}
                                <th>سٹاف/ہیڈ</th>
                                 <th>تاریخ</th>
                                <th>بینک</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                <h6 class="m-0 font-weight-bold text-primary text-right"> بینک فارم</h6>
            </div>
            <div class="card-body">
                <form id="paid_amount_form" class="data-form">


                    <div class="form-group">
                        <label for="exampleFormControlInput1">بینک</label>
                          <select name="bank_name" id="bank_name" autofocus  class="form-control"  onkeyup="validateError(this)">
                            <option value="">منتخب کریں</option>
                            @foreach($bank_name as $bank)
                            <option value="{{ $bank }}">{{$bank}}</option>
                            @endforeach
                          </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">سٹاف /اخراجات</label>
                          <select name="employee_type" id="employee_type" autofocus  class="form-control" onchange="chooseOption(this)" onkeyup="validateError(this)">
                            <option value="">منتخب کریں</option>
                            <option value="Employee">سٹاف</option>
                            <option value="Others">ہیڈاخراجات</option>
                          </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">سٹاف منتخب کریں</label>
                          <select name="employee_id" id="employee_id"  class="form-control toselect-tag-employee" onchange="removeBorder(this)">
                            
                          </select>
                    </div>

                    

                    <div class="form-group">
                        <label for="exampleFormControlInput1" >مقصد</label>
                          <select class="form-control" disabled id="purpose_type">
                                <option id="advance" value="Advance">ایڈوانس</option>
                                <option id="others" value="Others">کسی اور مقصد کے لیے</option>
                          </select>
                    </div>
                    <input type="hidden" name="purpose" id="purpose">

                    <div class="form-group">
                        <label for="exampleFormControlInput1">ایڈوانس مہینہ</label>
                        <input type="month" class="form-control" id="advance_payment_month" name="advance_payment_month" onchange="removeBorder(this)" onkeyup="validateError(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">رقم</label>
                        {{-- <input type="input" class="form-control" id="paid_amount" name="paid_amount" onkeyup="checkAmount(this)"> --}}
                        <input type="number" class="form-control" id="amount" name="amount" onkeyup="removeBorder(this)">
                    </div>
                    <div class="form-group" id="convert_to_number">
                         
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">ریمارکس</label>
                        <input type="input" class="form-control" id="remarks" name="remarks" onkeyup="removeBorder(this)">
                    </div>

                    {{-- <div class="form-group">
                        <label for="exampleFormControlInput1">Amount</label>
                        <input type="input" class="form-control" id="amount" name="amount" onkeyup="validate(this)">
                    </div> --}}

                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="Add" class="btn btn-primary" >
                    </div>
                    <input type="hidden" name="hidden_id" id="hidden_id">
                </form>

            </div>

        </div>
    </div>

    {{-- </div> --}}
</div>

@endsection





@section('script')
<script>

   
$(document).on("click", ".print-expense", function() {
    var id = $(this).data("id");
    $.ajax({
        url: "{{ url('view-receipt') }}",
        type: "GET",
        data: {
            expense_id: id
        },
        success: function(response) {
            if (response) {
                var printWindow = window.open('', '_blank');
                if (printWindow) {
                    printWindow.document.write(response);
                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 500);
                } else {
                    alert('Popup window blocked. Please enable popups and try again.');
                }
            } else {
                alert('Empty or invalid response received from the server.');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert('An error occurred while processing your request. Please try again.');
        }
    });
});







    
   var easypaisa_paid_list;
   
   function refreshTableAfterAdmissionYearLoad(){

    var easypaisa_paid_list = $('.employee_front_table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        // paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('get-bank-expense-list') }}",
            data: function(d) {
                d.search_value = $("#search_value").val();
                d.search_bank_name = $("#search_bank_name").val();
                
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
                 name: 'remarks',
             },

             {
                 data: 'amount',
                 name: 'amount',
             },


            //  {
            //      data: 'purpose',
            //      name: 'purpose'
            //  },

             {
                 data: 'employee',
                 name: 'employee'
             },

             
             {
                 data: 'paid_date',
                 name: 'paid_date'
             },

             {
                 data: 'bank_name',
                 name: 'bank_name'
             }

        ],

        success: function(data) {
           
        }
    });

       
    $("#search_value").on('keyup', function(e) {
       
       if (e.key === 'Enter' || e.keyCode === 13) {
        easypaisa_paid_list.draw();
       }
    });

    
    //form

    
$('#paid_amount_form').validate({
         errorPlacement: function(error, element) {
                  element[0].style.border = "1px solid red";
         },
         rules: {
           
            employee_id : "required",
            purpose : "required",
            amount:  'required',
            bank_name : "required",

         },

         submitHandler: function(form) {

             var amount = $("#amount")[0].value;
            
              var name = $("#employee_id")[0].firstChild.innerText;
             if (confirm('Are you sure! you paid amount of Rs.'+amount+'!This amount will not return if it paid')) {
             var formData = new FormData(form);
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: "{{ url('insert-bank-amount') }}",
                 type: "POST",
                 data: formData,
                 contentType: false,
                 cache: false,
                 processData: false,
                 success: function(data) {

                    easypaisa_paid_list.draw();
                     $('#paid_amount_form')[0].reset();
                    
                     $("#hidden_id").val("");
                     successAlert();

                 },
                 error: function(data) {
                    
                    

                 }

             })
         }
     }
     });
 

}




$(document).on("click", ".edit-bank-amount", function() {

var id = $(this).data("id");

$.ajax({
   
    url:"{{ url('edit-bank-amount') }}",
    type:"get",
    data:{id:id},
    success:function(data){

        

        var employee_type = $("#employee_type").val(data[1]["account_for"]);
        var employee_branch = $("#easypaisa_detail_locations").val(data[1]["employee_branch"]);
        getEmployees(data[0]["employee_id"], data[1]["account_for"]);
         
        var purpose_type = $("#purpose").val(data[0]["purpose"]);

      
        //this code is for month input tag
        const date = new Date(data[0]["paid_for_month_date"]); // Current date
        const month = date.toLocaleString('default', { month: 'numeric' }); // 'long' gives the full month name
        const year = date.toLocaleString('default', { year: 'numeric' });

        var create_month = (month < 10 ? "0"+month :  month);
        var create_month_date = year + "-" + create_month;
        var advance_payment_month = $("#advance_payment_month").val(create_month_date);

        var amount = $("#amount").val(data[0]["amount"]);
        var remarks = $("#remarks").val(data[0]["remarks"]);
        var bank_name = $("#bank_name").val(data[0]["bank_name"]);
        $("#hidden_id").val(data[0]["id"]);
       
    }
})

})





   
   
    
function chooseOption(e){

$("#employee_type")[0].style.border="";
if(e.value == "Employee"){
     $("#advance")[0].selected=true;
     $("#advance_payment_month")[0].disabled=false;
 }else if(e.value == "Patty"){
     $("#patty_cash")[0].selected=true;
     $("#advance_payment_month")[0].disabled=true;
 }else if(e.value == "Others"){
    $("#others")[0].selected=true;
    $("#advance_payment_month")[0].disabled=true;
 }else if(e.value == "Fuel"){
    $("#fuel")[0].selected=true;
     $("#advance_payment_month")[0].disabled=true;
 }
 

    getEmployees();
 
 

 var check_value = $("#purpose_type")[0].value;
 
 $("#purpose").val(check_value);

}



function getEmployees(get_employee=null, employee_type=null){
   

 var parent = $("#employee_id")[0];
 parent.innerHTML="";

 var get_employee_value = $("#employee_type")[0].value;

 if(get_employee_value == "" ){
    get_employee_value = employee_type;
 }

 console.log(employee_type);

 $.ajax({
     headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
     url:"{{ url('get-employees') }}",
     type:"POST",
     data:{employee_type:get_employee_value},
     success:function(data){

         
    
         $.each(data, function(key, value) {
                     var create_option = document.createElement("option");
                     create_option.value =  value["id"];
                     create_option.innerText =  value["employee_name"]+(value["employee_post"] ? "("+value["employee_post"]+")" : "");

                     if(value["id"] == get_employee){
                        create_option.selected = true;
                     }

                     parent.appendChild(create_option);
         });

     }
 })
}
   
    
        
    






function removeBorder(e){
   e.style.border="";
   if(e.id=="image"){

    $("#image_name").attr("src", e.value);

   }
}

</script>
@endsection
