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
                    <h6 class="m-0 font-weight-bold text-primary">ہفتہ وار چھٹی</h6>
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
                                <label for="exampleFormControlInput1"> گھر میں معمولات </label>
                                <input type="text" name="ghar_kay_mamoolat" id="ghar_kay_mamoolat" class="form-control">
                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1"> سونے کا وقت تحریر کریں </label>
                                <input type="time" name="sonay_ka_waqt" id="sonay_ka_waqt" class="form-control">
                            </div>
                        </div>


                            <ul dir="rtl" id="checked_value">

                            @foreach ($questions as $question)
                            
                            <li style="text-align: right;">
                                
                            <input type="checkbox" name="questions[]" value="{{$question->id}}" id="question{{$question->id}}" class="ml-3">
                            {{$question->question}}
                            
                            </li>
                            
                            @endforeach
                        </ul>
                       

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
                                <label for="exampleFormControlInput1"> ہدایات معلمہ </label>
                                <textarea name="hidayat_mualam" class="form-control" id="hidayat_mualam" cols="30" rows="5" style="resize:none;"></textarea>
                                
                            </div>
                        </div>


                        <div class="row p-2">
                            <div class="col">
                                <label for="exampleFormControlInput1"> ہدایات سرپرست </label>
                                <textarea name="hidayat_sarparat" class="form-control" id="hidayat_sarparat" cols="30" rows="5" style="resize:none;"></textarea>
                                
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

      $('#description').summernote({

        toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                // ['align', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']],
                // ['insert', ['picture', 'video']],
            ],
            
      });



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
                    url: "{{ url('get-list-of-holiday-diary') }}",
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
                    console.log(data);
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
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-holiday-diary') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);

                            $('#diary-form')[0].reset();
                            $("#student_id").val(null).trigger('change');
                            $("#book_id").val(null).trigger('change');
                            $("#student_diary_hidden_id").val("");
                            $("#hidayat_mualam").text("");
                            $("#hidayat_sarparat").text("");
                            diary_list.draw();
                            successAlert();


                        },
                        error: function(data) {


                        }

                    })
                }
            });



           

        }



        $(document).on("click", ".edit-student-diary", function() {

            var id = $(this).data("id");

            $.ajax({
                url: "{{ url('edit-student-holiday-diary') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {

                    var book_value = data["attach_book_id"]+","+data["get_student"]["class_id"]+","+data["get_student"]["section"];
                    
                    $("#book_id").val(book_value).trigger("change");
                   
                    getStudents(data["student_id"]);

                    $("#ghar_kay_mamoolat").val(data["ghar_kay_mamoolat"]);

                    $("#sonay_ka_waqt").val(data["sonay_ka_waqt"]);


                    //questions, searching in a string
                    var myString = data["questions"];

                    var checkboxes = $('input[name="questions[]"]');

                    checkboxes.each(function () {

                        searchTerm = $(this).val();
                        if (myString.search(searchTerm) !== -1) {
                            $(this).prop('checked', true); 
                        }else{
                            $(this).prop('checked', false); 
                        }
                        

                    });


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

                    var hidayat_mualam = data["hidayat_mualam"];
                    var hidayat_sarparast = data["hidayat_sarparast"];

                    $("#hidayat_mualam").text(hidayat_mualam);
                    $("#hidayat_sarparat").text(hidayat_sarparast);


                    $("#student_diary_hidden_id").val(data["id"]);





                  
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
                url: "{{ url('delete-student-holiday-diary') }}",
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

            console.log(get_class_id);

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

console.log(allNames);

})




    </script>
@endsection
