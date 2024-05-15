@extends('layout.structure')

@section('content')
    <div class="col-12 d-flex justify-content-center">

        {{-- <div class="col-lg-6 col-sm-12"> --}}



        <div class="col-lg-6 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between flex-row-reverse">
                    <h6 class="m-0 font-weight-bold text-primary">لسٹ</h6>
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
                        <table class="table table-bordered get-list-of-department" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> کتاب </th>
                                    <th> درجہ </th>
                                    <th> طالب علم </th>
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
                    <h6 class="m-0 font-weight-bold text-primary">یوم کیفیت</h6>
                </div>
                <div class="card-body">
                    <form id="diary-form">

                       

                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1"> بک کا نام </label>
                                <select name="book_id" id="book_id" class="form-control toselect-tag"
                                    onchange="getStudents()">
                                    <option value=""> کتاب منتخب کریں</option>
                                    @foreach ($books_and_classes as $book)
                                        <option value="{{ $book->id . ',' . $book->getClasses->id . ',' . $book->section }}">
                                            {{ $book->getBooks->book . '(' . $book->getClasses->class . '-' . $book->section . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1"> طالب علم </label>
                                <select name="student_id" id="student_id" class="form-control toselect-tag">
                                    <option value="">طالب علم منتخب کریں</option>
                                </select>
                            </div>
                        </div>


                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1">سبق سنایا</label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" value="نہیں" name="sabaq_sunaya"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" value="ہاں" name="sabaq_sunaya"> 
                                </div>
                            </div>
                        </div>


                        <div class="row p-2">
                           
                            <div class="col">
                                <label for="exampleFormControlInput1">سامع</label>
                                <input type="text" name="samay_two" id="samay_two" class="form-control">
                            </div>     
                       
                            
                            <div class="col">
                                <label for="exampleFormControlInput1"> سنایا </label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" value="نہیں" name="sunaya" id="sunaya"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" value="ہاں" name="sunaya" id="sunaya"> 
                                </div>
                            </div>
                            <div class="col">
                                <label for="exampleFormControlInput1">منزل پارہ</label>
                                <input type="text" name="manzil_para" id="manzil_para" class="form-control">
                            </div>      
                            <div class="col">
                                <label for="exampleFormControlInput1">سامع</label>
                                <input type="text" name="samay_one" id="samay_one" class="form-control">
                            </div>     
                            
                            <div class="col">
                                <label for="exampleFormControlInput1">سبق سنائی</label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" value="نہیں" name="sabaq_sunai" id="sabaq_sunai"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" value="ہاں" name="sabaq_sunai" id="sabaq_sunai"> 
                                </div>
                            </div>
                            
                        </div>



                        <div class="row p-2">

                            <div class="col">
                                <label for="exampleFormControlInput1">سامع</label>
                                <input type="text" name="samay_four" id="samay_four" class="form-control">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1"> کچا سبق سنایا </label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" value="نہیں"   name="kacha_sabaq_sunaya" id="kacha_sabaq_sunaya"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" value="ہاں" name="kacha_sabaq_sunaya" id="kacha_sabaq_sunaya"> 
                                </div>
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1">سامع</label>
                                <input type="text" name="samay_three" id="samay_three" class="form-control">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1"> دیا </label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" name="dia" id="dia" value="نہیں"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" name="dia" id="dia" value="ہاں"> 
                                </div>
                            </div>
                            <div class="col">
                                <label for="exampleFormControlInput1"> پارہ یا تین سبق</label>
                                <input type="text" name="para_ya_teen_sabaq" id="para_ya_teen_sabaq" class="form-control">
                            </div>
                            
                        </div>


                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1"> بعد ازمغرب حاضر ہوا</label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" name="bad_maghrib_hazir_hua" value="نہیں"  id="bad_maghrib_hazir_hua"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" name="bad_maghrib_hazir_hua" value="ہاں" id="bad_maghrib_hazir_hua"> 
                                </div>
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1"> بعد ظہر حاضر ہوا </label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" name="bad_zuhr_hazir_hua" value="نہیں" id="bad_zuhr_hazir_hua"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" name="bad_zuhr_hazir_hua" value="ہاں"  id="bad_zuhr_hazir_hua"> 
                                </div>
                            </div>
                            <div class="col">
                                <label for="exampleFormControlInput1"> صبح حاضر ہوا</label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    نہیں &nbsp;<input type="radio" class="mr-3" name="subah_hazir_hua" value="نہیں" id="subah_hazir_hua"> 
                                    ہاں &nbsp;<input type="radio" class="mr-3" name="subah_hazir_hua" value="ہاں" id="subah_hazir_hua"> 
                                </div>
                            </div>      
                        </div>


                        <div class="row p-2">
                        
                            <div class="col">
                                <label for="exampleFormControlInput1">تک سطریں </label>
                                <input type="text" name="tak_satrain" id="tak_satrain" class="form-control">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1">تا </label>
                                <input type="text" name="taa" id="taa" class="form-control">
                            </div>


                            <div class="col">
                                <label for="exampleFormControlInput1">آیت نمبر</label>
                                <input type="text" name="ayat_no" id="ayat_no" class="form-control">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1">پارہ نمبر</label>
                                <input type="text" name="para_no" id="para_no" class="form-control">
                            </div>

                            <div class="col">
                                <label for="exampleFormControlInput1">سورہ</label>
                                <input type="text" name="surah" id="surah" class="form-control">
                            </div>

                        </div>



                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1">حاضری نماز</label>
                                <div style="display: flex; justify-content:flex-end" class="border">
                                    عشاء &nbsp;<input type="checkbox" class="mr-3" value="عشاء"  name="hazrinmaz[]">
                                    مغرب &nbsp;<input type="checkbox" class="mr-3" value="مغرب" name="hazrinmaz[]"> 
                                    عصر &nbsp;<input type="checkbox" class="mr-3" value="عصر" name="hazrinmaz[]">
                                    ظہر &nbsp;<input type="checkbox" class="mr-3" value="ظہر" name="hazrinmaz[]"> 
                                    فجر &nbsp;<input type="checkbox" class="mr-3" value="فجر" name="hazrinmaz[]">
                                تہجد &nbsp;<input type="checkbox" class="mr-3" value="تہجد" name="hazrinmaz[]"> 
                                </div>
                            </div>

                        </div>


                      

                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1"> کیفیت /ہدایت </label>
                                <textarea name="description" class="form-control" id="description" cols="30" rows="5" style="resize:none;"></textarea>
                                
                                
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </div>
                        <input type="hidden" name="student_diary_hidden_id" id="student_diary_hidden_id">
                    </form>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection




@section('script')
    <script>
      

          

    //   $('#description').summernote({

    //     toolbar: [
    //             ['style', ['style']],
    //             ['font', ['bold', 'italic', 'underline', 'clear']],
    //             ['para', ['ul', 'ol']],
    //             // ['align', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']],
    //             // ['insert', ['picture', 'video']],
    //         ],
            
    //   });




        

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



        // var fine_list;

        function refreshTableAfterAdmissionYearLoad() {

            var diary_list = $('.get-list-of-department').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('get-list-of-diary') }}",
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
                        data: 'book',
                        name: 'book'
                    },
                    {
                        data: 'class',
                        name: 'class'
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    }
                ],

                success: function(data) {
                    
                }
            });


            // fine_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                department_list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    department_list.draw();
                }
            });


            //form
            $('#diary-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    book_id: "required",
                    student_id: "required",
                    description: "required",
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-diary') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                          

                            $('#diary-form')[0].reset();
                            $("#student_id").val(null).trigger('change');
                            $("#book_id").val(null).trigger('change');
                            $("#student_diary_hidden_id").val("");
                            $("#description").text("");
                            diary_list.draw();
                            successAlert();


                        },
                        error: function(data) {


                        }

                    })
                }
            });



            getTeacherAttachData();

        }




        function getTeacherAttachData() {
            var session = $("#session")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-teacher-attach-data') }}",
                type: "GET",
                data: {
                    session: session
                },
                success: function(data) {


                    // var paper = $("#connect_teacher_id")[0];
                    // paper.innerHTML = "";
                    // var create_first_option = document.createElement("option");
                    // create_first_option.innerText = "پرچہ  منتخب  کریں";
                    // create_first_option.value = "";
                    // paper.appendChild(create_first_option);

                    // $(data).each(function(index, element) {
                    //     var create_option = document.createElement("option");
                    //     create_option.innerText = element["get_books"]["book"] + " (" + element[
                    //         "get_classes"]["class"] + " - " + element["section"] + ")";
                    //     create_option.value = element["id"];
                    //     if (element["id"] == teacher_connect_id) {
                    //         create_option.selected = true;
                    //     }

                    //     paper.appendChild(create_option);
                    // });



                }
            })


        }







        $(document).on("click", ".edit-student-diary", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-student-diary') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                   
                    var book_value = data["attach_book_id"]+","+data["get_student"]["class_id"]+","+data["get_student"]["section"];                    

                   $("#book_id").val(book_value).trigger('change');

                   $('input[name="sabaq_sunaya"][value='+data["sabaq_sunaya"]+']').prop('checked', true);
                   $('input[name="sabaq_sunai"][value='+data["sabaq_sunai"]+']').prop('checked', true);
                   $('#samay_one').val(data["samay_one"]);
                   $('#manzil_para').val(data["manzil_para"]);
                   $('#sunaya').val(data["sunaya"]);
                   $('#samay_two').val(data["samay_two"]);
                   $('#para_ya_teen_sabaq').val(data["para_ya_teen_sabaq"]);
                   $('#dia').val(data["dia"]);
                   $('#samay_three').val(data["samay_three"]);
                   $('#samay_four').val(data["samay_four"]);
                   $('input[name="kacha_sabaq_sunaya"][value='+data["kacha_sabaq_sunaya"]+']').prop('checked', true);
                   $('input[name="subah_hazir_hua_one"][value='+data["kacha_sabaq_sunaya"]+']').prop('checked', true);

                   $('input[name="subah_hazir_hua"][value='+data["subah_hazir_hua"]+']').prop('checked', true);

                   $('input[name="bad_zuhr_hazir_hua"][value='+data["bad_zuhr_hazir_hua"]+']').prop('checked', true);

                   $('input[name="bad_maghrib_hazir_hua"][value='+data["bad_maghrib_hazir_hua"]+']').prop('checked', true);

                   $('#surah').val(data["surah"]);
                   $('#para_no').val(data["para_no"]);
                   $('#ayat_no').val(data["ayat_no"]);
                   $('#ayat_no').val(data["ayat_no"]);
                   $('#taa').val(data["taa"]);
                   $('#tak_satrain').val(data["tak_satrain"]);


                    // nmaz
                    var myStringNmaz = data["hazrinmaz"];

                    var checkboxesNmaz= $('input[name="hazrinmaz[]"]');

                    checkboxesNmaz.each(function () {

                        searchTerm = $(this).val();
                        if (myStringNmaz.search(searchTerm) !== -1) {
                            $(this).prop('checked', true); 
                        }else{
                            $(this).prop('checked', false); 
                        }
                        

                    });

                    $('#description').text(data["description"]);
                    // $('.note-editable p').html(data["description"]);
                    $('#student_diary_hidden_id').val(data["id"]);
                   
                }
            })

        })







        $(document).on("click", ".delete-student-diary", function() {

            var id = $(this).data("id");
            var element = this;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-student-diary') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    $(element).parent().parent().parent().parent().fadeOut();
                }
            })

        })



        function removeBorder(e) {
            e.style.border = "";
            if (e.id == "image") {
                $("#image_name").attr("src", e.value);
            }
        }



        function getStudents(student_id_get) {

            //Fetch id from book id
            var get_class_id = $("#book_id").val();

            var array_create = get_class_id.split(',');

            var class_id = array_create[1];
            var section = array_create[2];

            var session_id = $("#session")[0].value;

            $.ajax({
                url: "{{ url('get-student-classwise-sectionwise') }}",
                type: "get",
                data: {
                    class_id: class_id,
                    section: section,
                    session_id: session_id
                },
                success: function(data) {
                    var class_parent = $("#student_id")[0];
                    class_parent.innerHTML = "";
                    $(data).each(function(index, element) {
                        var create_option = document.createElement("option");
                        create_option.innerText = element["name"] + "(" + element["roll_no"] + ")";
                        create_option.value = element["id"];
                        if (student_id_get == element["id"]) {
                            create_option.selected = true;
                        }
                        class_parent.appendChild(create_option);
                    });



                }
            })

        }

        $(".toselect-tag").select2();




$(document).ready(function(){

    var allNames = {};

// Iterate through each input element in the form
$('#diary-form input').each(function() {
    var name = $(this).attr('name');
    var value = $(this).val();
    allNames[name] = value;
});



})




    </script>
@endsection
