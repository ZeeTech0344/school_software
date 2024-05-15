 @extends('layout.structure')

 @section('content')
     <div class="col-12 d-flex justify-content-center">

         {{-- <div class="col-lg-6 col-sm-12"> --}}



         <div class="col-lg-6 col-sm-12">
             <div class="card shadow mb-4">
                 <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                     <h6 class="m-0 font-weight-bold text-primary">داخلوں کی لسٹ</h6>
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
                             <input type="text" class="form-control" id="search_value" name="search_value"
                                 placeholder="سرچ کریں">
                         </div>
                         <table class="table table-bordered get-list-of-admissions" id="dataTable" width="100%"
                             cellspacing="0">
                             <thead>
                                 <tr>
                                     <th> ایکشن </th>
                                     <th> سٹیٹس </th>
                                     <th> کلاس </th>
                                     <th>والد کا نام</th>
                                     <th>نام</th>
                                     <th>رجسٹر نمبر</th>

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
                     <h6 class="m-0 font-weight-bold text-primary">داخلہ فارم</h6>
                 </div>
                 <div class="card-body">
                     <form id="add-admission-form">

                         <div class="row">
                             <div class="col">
                                 <label for="exampleFormControlInput1">رجسٹر نمبر</label>
                                 <input type="text" class="form-control" name="register_no" id="register_no"
                                     onkeydown="removeBorder(this)">
                             </div>


                             <div class="col">
                                <label for="exampleFormControlInput1">  تاریخ داخلہ </label>
                                <input type="date" class="form-control" onchange="removeBorder(this)"
                                    id="admission_date" name="admission_date">
                            </div>

                
                         </div>

                         <div class="row">

                           

                            <div class="col">
                                <label for="exampleFormControlInput1">ولدیت</label>
                                <input type="text" class="form-control" id="father_name" name="father_name"
                                    onkeydown="removeBorder(this)">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1"> نام طالب علم /طالبہ </label>
                                <input type="text" class="form-control" id="fname" name="fname"
                                    onkeydown="removeBorder(this)">
                            </div>
                           
                         </div>

                        <div class="row">

                           

                                <div class="col">
                                    <label for="exampleFormControlInput1">سر پرست کا بچے / بچی سے رشتہ</label>
                                    <input type="text" class="form-control" id="guardian_relation" name="guardian_relation"
                                        onkeydown="removeBorder(this)">
                                </div>

                                <div class="col">
                                    <label for="exampleFormControlInput1">سر پرست کا نام</label>
                                    <input type="text" class="form-control" id="guardian" name="guardian"
                                        onkeydown="removeBorder(this)">
                                </div>

                        </div>



                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1">والد / سرپرست کا شناختی کارڈ نمبر </label>
                                <input type="text" name="father_cnic" id="father_cnic" class="form-control">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1"> تاریخ پیدائش </label>
                                <input type="date" class="form-control" id="dob" name="dob"
                                    onchange="removeBorder(this)">
                            </div>
                    </div>



                    <div class="row">

                        <div class="col">
                            <label for="exampleFormControlInput1"> والد کا پیشہ </label>
                            <input type="text" class="form-control" id="father_occupation" name="father_occupation"
                                onkeydown="removeBorder(this)">
                        </div>

                        <div class="col">
                            <label for="exampleFormControlInput1"> ایڈریس </label>
                            <input type="text" class="form-control" id="address" name="address"
                                onkeydown="removeBorder(this)">
                        </div>


                        
                            
                       
                    </div>


                    
                    <div class="row">

                        <div class="col">
                            <label for="exampleFormControlInput1"> موبائل نمبر</label>
                            <input type="text" placeholder="+923441207218  نمبرکااندراج اس طرح کریں"
                                class="form-control" id="mobile_no" name="mobile_no"
                                onkeydown="removeBorder(this)">
                        </div>

                        <div class="col">
                            <label for="exampleFormControlInput1"> فون نمبر</label>
                            <input type="text" placeholder="+923441207218  نمبرکااندراج اس طرح کریں"
                                class="form-control" id="phone_no" name="phone_no"
                                onkeydown="removeBorder(this)">
                        </div>

                    </div>


                    <div class="row">

                        <div class="col">
                            <label for="exampleFormControlInput1"> سابقہ مدرسے کا نام </label>
                            <input type="text" class="form-control" id="previous_madrissa" name="previous_madrissa"
                                onkeydown="removeBorder(this)">
                        </div>

                        <div class="col">
                            <label for="exampleFormControlInput1">سابقہ دینی تعلیم اگر ہو تو</label>
                            <input type="text" class="form-control" id="previous_madrissa_education" name="previous_madrissa_education"
                                onkeydown="removeBorder(this)">
                        </div>

                     </div>



                     <div class="row">
                       
                        <div class="col">
                            <label for="exampleFormControlInput1">  سکول کا نام </label>
                            <input type="text" class="form-control" id="previous_school" name="previous_school"
                                onkeydown="removeBorder(this)">
                        </div>

                        <div class="col">
                            <label for="exampleFormControlInput1">سابقہ عصری تعلیم اگر ہو تو</label>
                            <input type="text" class="form-control" id="previous_school_education" name="previous_school_education"
                                onkeydown="removeBorder(this)">
                        </div>

                     </div>

                    <div class="row">
                        <div class="col">
                            <label for="exampleFormControlInput1">شعبہ </label>
                            <select name="department_id" id="department_id" class="form-control"
                                onchange="getClasses()">

                            </select>
                        </div>
                    </div>



                
                         <div class="row">
                             <div class="col">
                                 <label for="exampleFormControlInput1"> سیکشن </label>
                                 <select name="section" id="section" class="form-control" onchange="removeBorder(this)">
                                     <option value="">Select Section</option>
                                     @foreach ($sections as $section)
                                         <option>{{ $section }}</option>
                                     @endforeach
                                 </select>
                             </div>
                             <div class="col">
                                 <label for="exampleFormControlInput1">درجہ</label>
                                 <select name="class" id="class" class="form-control toselect-tag" onchange="removeBorder(this)">

                                 </select>
                             </div>
                         </div>

                         <div class="row">
                             <div class="col">
                                 <label for="exampleFormControlInput1">قسم</label>
                                 <select name="category" id="category" class="form-control" onchange="removeBorder(this)">
                                     <option>صحت مند</option>
                                     <option>معزور</option>
                                 </select>
                             </div>
                             <div class="col">
                                 <label for="exampleFormControlInput1"> شفٹ </label>
                                 <select name="shift" id="shift" class="form-control" onchange="removeBorder(this)">
                                     <option value="">منتخب شفٹ</option>
                                     <option>صبح تا عصر</option>
                                     <option>صبح تا دوپہر</option>
                                     <option>ظہر تا عصر</option>
                                     <option>مغرب تا عشاء</option>
                                 </select>
                             </div>

                         </div>


                       



                         


                         <div class="row">
                             <div class="col">
                                 <label for="exampleFormControlInput1"> فوٹو </label>
                                 <div class="d-flex">
                                     {{-- <label id="image_name"></label> --}}
                                     <img id="image_name" class="d-none" width="70px" height="70px"
                                         style="margin-right: 10px;">
                                     <input type="file" class="form-control " id="image" name="image"
                                         onchange="displayImage(this)">
                                 </div>

                             </div>
                         </div>


                         <div class="row">
                             <div class="col">
                                 <label for="exampleFormControlInput1"> سٹیٹس </label>
                                 <select name="status" id="status" class="form-control"
                                     onchange="removeBorder(this)">
                                     <option value="1">آن</option>
                                     <option value="2">رخصت</option>
                                     <option value="0">آف</option>
                                     
                                 </select>

                             </div>
                         </div>


                         <div class="form-group d-flex justify-content-end pt-3">
                             <input type="submit" value="Add" class="btn btn-primary">
                         </div>
                         <input type="hidden" name="admission_hidden_id" id="admission_hidden_id">
                     </form>

                 </div>

             </div>
         </div>

         {{-- </div> --}}
     </div>
 @endsection




 @section('script')
     <script>
        
         function getClasses(edit_class_id) {

             var id = $("#department_id")[0].value;

             $.ajax({
                 url: "{{ url('get-classes-department-wise') }}",
                 type: "get",
                 data: {
                     id: id
                 },
                 success: function(data) {

                     var class_parent = $("#class")[0];
                     class_parent.innerHTML = "";
                     var create_first_option = document.createElement("option");
                     create_first_option.innerText = "کلاس منتخب کریں";
                     create_first_option.value="";
                     class_parent.appendChild(create_first_option);

                     $(data).each(function(index, element) {
                         var create_option = document.createElement("option");
                         create_option.innerText = element["class"];
                         create_option.value = element["id"];
                         if (element["id"] == edit_class_id) {
                             create_option.selected = true;
                         }
                         class_parent.appendChild(create_option);
                     });

                 }
             })

         }

         function getDepartmentList() {

             $.ajax({
                 url: "{{ url('get-department-list') }}",
                 type: "get",

                 success: function(data) {


                     var departments = $("#department_id")[0];
                     departments.innerHTML = "";
                     var create_first_option = document.createElement("option");
                     create_first_option.innerText = "شعبہ منتخب کریں";
                     departments.appendChild(create_first_option);

                     $(data).each(function(index, element) {
                         var create_option = document.createElement("option");
                         create_option.innerText = element["department"];
                         create_option.value = element["id"];
                         departments.appendChild(create_option);
                     });

                 }
             })

         }

         getDepartmentList();

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

         function refreshTableAfterAdmissionYearLoad() {

             var admission_list = $('.get-list-of-admissions').DataTable({
                 processing: true,
                 serverSide: true,
                 searching: false,
                 // paging: false,
                 // "info": false,
                 "language": {
                     "infoFiltered": ""
                 },

                 ajax: {
                    headers: {
                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                         },
                     url: "{{ url('get-list-of-admissions') }}",
                     type:"POST",
                     data: function(d) {
                         d.search_value = $("#search_value").val();
                         d.session = $("#session").val();
                     }
                 },

                 columns: [{
                         data: 'action',
                         name: 'action',
                         orderable: false,
                         searchable: false
                     },
                     {
                         data: 'status',
                         name: 'status'
                     },
                     {
                         data: 'class',
                         name: 'class'
                     },
                     {
                         data: 'father_name',
                         name: 'father_name'
                     },
                     {
                         data: 'name',
                         name: 'name'
                     },
                     {
                         data: 'roll_no',
                         name: 'roll_no'
                     }
                 ],

                 success: function(data) {
                     console.log(data);
                 }
             });


              

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
             $('#add-admission-form').validate({
                 errorPlacement: function(error, element) {
                     element[0].style.border = "1px solid red";
                 },
                 rules: {
                     year: "required",
                     register_no: "required",
                     roll_no: "required",
                     admission_date: "required",
                     class: "required",
                     section: "required",
                     shift: "required",
                     category: "required",
                     fname: "required",
                     father_name: "required",
                     dob: "required",
                     mobile_no: {
                         required: true,
                         pattern: /^\+923\d{9}$/ // Pakistani phone number pattern
                     },
                     address: "required",
                     status: "required"
                 },

                 submitHandler: function(form) {

                     var session_id = $("#session").val();
                     var formData = new FormData(form);
                     formData.append('session_id', session_id);

                     $.ajax({
                         headers: {
                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                         },
                         url: "{{ url('insert-admission') }}",
                         type: "POST",
                         data: formData,
                         contentType: false,
                         cache: false,
                         processData: false,
                         success: function(data) {
                             console.log(data);
                             $('#add-admission-form')[0].reset();
                             $("#admission_hidden_id").val("");
                             admission_list.draw();
                             $("#image_name")[0].classList.add("d-none");
                             successAlert();
                         },
                         error: function(data) {


                         }

                     })
                 }
             });




         }



         $(document).on("click", ".edit_admission", function() {

             var id = $(this).data("id");
             $.ajax({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 url: "{{ url('edit-admission') }}",
                 type: "GET",
                 data: {
                     id: id
                 },
                 success: function(data) {

                     $("#admission_hidden_id").val(data["id"]);
                     $("#year").val(data["admission_year"]);
                     $("#register_no").val(data["register_no"]);
                     $("#roll_no").val(data["roll_no"]);
                     $("#admission_date").val(data["admission_date"]);
                     $("#class").val(data["class"]);
                     $("#section").val(data["section"]);
                     $("#shift").val(data["shift"]);
                     $("#category").val(data["category"]);
                     $("#fname").val(data["name"]);
                     $("#father_name").val(data["father_name"]);
                     $("#dob").val(data["dob"]);
                     $("#mobile_no").val(data["mobile_no"]);
                     $("#address").val(data["address"]);
                     $("#department_id").val(data["get_class"]["get_departments"]["id"]);
                     getClasses(data["class_id"]);
                     // $("#image_name")[0].innerText = data["image"];
                     $("#image_name").attr("src", "{{ asset('images') }}" + "/" + data["image"]);
                     $("#image_name")[0].classList.remove("d-none");
                     $("#status").val(data["status"]);

                    $("#guardian").val(data["guardian"]);
                    $("#guardian_relation").val(data["guardian_relation"]);
                    $("#father_cnic").val(data["father_cnic"]);
                    $("#father_occupation").val(data["father_occupation"]);
                    $("#phone_no").val(data["phone_no"]);
                    $("#previous_madrissa").val(data["previous_madrissa"]);
                    $("#previous_madrissa_education").val(data["previous_madrissa_education"]);
                    $("#previous_school").val(data["previous_school"]);
                    $("#previous_school_education").val(data["previous_school_education"]);


                 }
             })

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





         $(document).on("click", ".view_admission", function() {
            
             var id = $(this).data("id");
             var url = "{{ url('view-admission') }}" + "/" + id;
             viewModal(url);


         })



         function removeBorder(e) {
             e.style.border = "";
             if (e.id == "image") {

                 $("#image_name").attr("src", e.value);

             }
         }

         
        $(".toselect-tag").select2();


     </script>
 @endsection
