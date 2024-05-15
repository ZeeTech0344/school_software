@extends('layout.structure') 

@section('content')



<div class="col-12 d-flex justify-content-center">

    {{-- <div class="col-lg-6 col-sm-12"> --}}

  

    <div class="col-lg-6 col-sm-12">
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
                        <input type="text" class="form-control" id="search_value" name="search_value" placeholder="سرچ کریں">
                    </div>
                    <table class="table table-bordered employee_front_table" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>ایکشن</th>
                                <th>سٹیٹس</th>
                                <th>سیلری</th>
                                <th>فون نمبر</th>
                                <th>نام</th>
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
                <h6 class="m-0 font-weight-bold text-primary text-right">سٹاف فارم</h6>
            </div>
            <div class="card-body">
                <form id="employee-form"  autocomplete="off">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">نام</label>
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">تاریخ پیدائش</label>
                        <input type="date" class="form-control" id="dob" name="dob"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">پوسٹ</label>
                        <select name="employee_post" id="employee_post" class="form-control"  onkeyup="removeBorder(this)">
                            <option value="">Select Post</option>
                            <option>استاد</option>
                            <option>آفس بوائے</option>
                            <option>مؤزن </option>
                            <option>خادم</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">سیلری</small></label>
                        <input type="number" class="form-control" id="basic_sallary"  name="basic_sallary"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">شناختی کارڈ نمبر</label>
                        <input type="text" class="form-control" id="cnic"  name="cnic"
                            onkeyup="removeBorder(this)">
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlInput1">فون نمبر</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no"
                            onkeyup="removeBorder(this)">
                    </div>
                
                    <div class="form-group">
                        <label for="exampleFormControlInput1">شمولیت کی تاریخ</label>
                        <input type="date" class="form-control" id="joining"
                            name="joining" onchange="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">جانے کی تاریخ</label>
                        <input type="date" class="form-control" id="leaving"
                            name="leaving" onchange="removeBorder(this)">
                    </div>

                    <div class="row">
                        <div class="col">
                           <label for="exampleFormControlInput1"> فوٹو </label>
                          <div class="d-flex">
                            {{-- <label id="image_name"></label> --}}
                            <img id="image_name" class="d-none" width="70px" height="70px" style="margin-right: 10px;">
                            <input type="file" class="form-control "  id="image" name="image"   onchange="displayImage(this)"></div>
                       
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">سٹیٹس</label>
                        <select name="employee_status" id="employee_status" onchange="validate(this)"
                            class="form-control">
                            <option>On</option>
                            <option>Off</option>
                        </select>
                    </div>
                    

                    <div class="bg-primary">
                        <h5 style="text-align: right;" class="text-white p-2 text-center mt-2">ٹیچرز لاگ ان</h5>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleFormControlInput1">لاگ ان نام</label>
                        <input type="text" class="form-control" id="name" autocomplete="off"
                            name="name" onchange="removeBorder(this)">
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlInput1">پاسورڈ</label>
                        <input type="password" class="form-control" id="password" autocomplete="off"
                            name="password" onchange="removeBorder(this)">
                    </div>

                  

                 

                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                    <input type="hidden" name="employee_hidden_id" id="employee_hidden_id">
                </form>

            </div>

        </div>
    </div>

    {{-- </div> --}}
</div>

@endsection





@section('script')
<script>



function validateNumericInput(input) {
  // Remove non-numeric characters
  input.value = input.value.replace(/[^0-9]/g, '');

}





function displayImage(input) {
  const imgElement = document.getElementById('image_name');
  const file = input.files[0]; // Get the selected file

  if (file) {
    const reader = new FileReader();

    reader.onload = function(e) {
      // Set the src attribute of the <img> element to the data URL
      imgElement.src = e.target.result;
    };

    // Read the selected file as a data URL
    reader.readAsDataURL(file);
    $("#image_name")[0].classList.remove("d-none");
  } else {
    // If no file is selected, clear the <img> element
    imgElement.src = '';
  }
}




    

    // admission_list.draw();

   
    
   var admission_list;
   
   function refreshTableAfterAdmissionYearLoad(){

    var admission_list = $('.employee_front_table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        // paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('employee-list') }}",
            data: function(d) {
                d.search_value = $("#search_value").val();
                d.session = $("#session").val();
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
                    data: 'employee_status',
                    name: 'employee_status'
                },
                
                {
                    data: 'salary',
                    name: 'salary'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'employee_name',
                    name: 'employee_name'
                }
        ],

        success: function(data) {
            console.log(data);
        }
    });


    // admission_list.draw();

    //search functions
    $("#session").on('change', function(e) {
       
       admission_list.draw();
   
       });
   
       
    $("#search_value").on('keyup', function(e) {
       
       if (e.key === 'Enter' || e.keyCode === 13) {
           admission_list.draw();
       }
    });

    
    //form

    
$('#employee-form').validate({
            errorPlacement: function(error, element) {
                 element[0].style.border = "1px solid red";
            },
            rules: {
                employee_name: "required",
                employee_post: "required",
                cnic: "required",
                basic_sallary: "required",
                phone_no: "required",
                joining: "required",
                // leaving: "required",
            },

            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-employee-others') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        $('#employee-form')[0].reset();
                        admission_list.draw();
                        $("#image_name")[0].classList.add("d-none");
                        $("#employee_hidden_id").val("");
                        successAlert();
                    },
                    error: function(data) {
                        

                       

                    }

                })
            }
});
}



   
   
    
    






    


   



    $(document).on("click", ".edit_employee", function() {

        var id = $(this).data("id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('edit-employee') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(data) {
                
              
                $("#employee_name").val(data[0]["employee_name"]);
                $("#employee_post").val(data[0]["employee_post"]);
                $("#basic_sallary").val(data[0]["basic_sallary"]);
                $("#cnic").val(data[0]["cnic"]);
                $("#dob").val(data[0]["dob"]);
                $("#phone_no").val(data[0]["phone_no"]);
                $("#joining").val(data[0]["joining"]);
                $("#leaving").val(data[0]["leaving"]);
                $("#name").val(data[0]["name"]);
                $("#password").val(data[0]["password"]);
                $("#image_name").attr("src", "{{ asset('images') }}" + "/" + data[0]["image"]);
                $("#image_name")[0].classList.remove("d-none");
                $("#employee_status").val(data[0]["employee_status"]);
                $("#employee_hidden_id").val(data[0]["id"]);

            }
        })

    })

   

    $(document).on("click", ".view_profile", function(){

var id = $(this).data("id");
var url = "{{ url('view-employee-profile') }}" + "/" + id;
payNowModalBody(url);

})
    
        
    


    $(document).on("click", ".delete_add_item", function() {

        var id = $(this).data("id");

        var element = this;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('delete-add-item') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(data) {

                $(element).parent().parent().parent().parent().fadeOut();


            }
        })

    })



function removeBorder(e){
   e.style.border="";
   if(e.id=="image"){

    $("#image_name").attr("src", e.value);

   }
}

</script>
@endsection
