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
                           
                        </div>
                        <table class="table table-bordered get-list-of-connection-table" id="dataTable" width="100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th> ایکشن </th>
                                    <th> بک </th>
                                    <th> کلاس </th>
                                    <th> ٹیچر </th>
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
                    <h6 class="m-0 font-weight-bold text-primary">منسلک فارم</h6>
                </div>
                <div class="card-body">
                    <form id="connect-teacher-form">


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> استاد </label>
                                <select name="teacher_id" id="teacher_id" class="form-control toselect-tag"  onchange="removeBorder(this)">
                                    
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> درجہ </label>
                                <select name="class_id" id="class_id" class="form-control toselect-tag"  onchange="removeBorder(this)">
                                    
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> سیکشن </label>
                                <select name="section_id" id="section_id" class="form-control toselect-tag"  onchange="removeBorder(this)">
                                    
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col">
                                <label for="exampleFormControlInput1"> متعدد کتابیں منسلک کریں </label>
                                <select name="book_id[]" id="book_id" class="form-control multiple"  onchange="removeBorder(this)">
                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end pt-3">
                            <input type="submit" value="Add" class="btn btn-primary">
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





       

        function refreshTableAfterAdmissionYearLoad() {

            var list = $('.get-list-of-connection-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                // paging: false,
                // "info": false,
                "language": {
                    "infoFiltered": ""
                },

                ajax: {
                    url: "{{ url('connect-teacher-class-books-list') }}",
                    data: function(d) {
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
                        data: 'teacher',
                        name: 'teacher'
                    }
                ],

                success: function(data) {
                    console.log(data);
                }
            });

            // fine_list.draw();

            //search functions
            $("#session").on('change', function(e) {

                list.draw();

            });


            $("#search_value").on('keyup', function(e) {

                if (e.key === 'Enter' || e.keyCode === 13) {
                    list.draw();
                }
            });


            //form
            $('#connect-teacher-form').validate({
                errorPlacement: function(error, element) {
                    element[0].style.border = "1px solid red";
                },
                rules: {
                    teacher_id: "required",
                    class_id: "required",
                    book_id: "required",
                },

                submitHandler: function(form) {

                    var session_id = $("#session")[0].value;
                    var formData = new FormData(form);
                    formData.append('session_id', session_id);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('insert-connect-teacher-class-books') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                           

                           console.log(data);
                            $("#hidden_id").val("");
                            $('#connect-teacher-form')[0].reset();
                            
                            selectTwoMultiple(true)
                            $(".toselect-tag").val(null).trigger('change');
                            $('.multiple').val(null).trigger('change');


                            list.draw();
                            successAlert();
                            //    $("#image_name")[0].classList.add("d-none");
                        },
                        error: function(data) {
                          

                        }

                    })
                }
            });



            

        }



        $(document).on("click", ".edit_teaher_books", function() {

            var id = $(this).data("id");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-data-connect-teacher-class-books') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                  
                    getBooks(data["book_id"]);
                    getClassList(data["class_id"]);
                    getTeacherList(data["teacher_id"]);
                    getSectionList(data["section"]);
                    $("#hidden_id").val(data["id"]);
                    selectTwoMultiple(false)
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



function removeBorder(e) {
            e.style.border = "";
            if (e.id == "image") {
                $("#image_name").attr("src", e.value);
            }
}



function getBooks(book_id) {


$.ajax({
    url: "{{ url('get-list-of-books') }}",
    type: "get",
    success: function(data) {

        var books_parent = $("#book_id")[0];

        books_parent.innerHTML = "";

        
        $(data).each(function(index, element) {
            var create_option = document.createElement("option");
            create_option.innerText = element["book"];
            create_option.value = element["id"];
            if(element["id"] == book_id){
                create_option.selected  = true;
            }
            books_parent.appendChild(create_option);
        });



    }
})

}

getBooks();




function getClassList(class_id) {


$.ajax({
    url: "{{ url('get-class-list') }}",
    type: "get",
    success: function(data) {

        var class_parent = $("#class_id")[0];

        class_parent.innerHTML = "";

        var first_option = document.createElement("option");
        first_option.innerText="کلاس منتخب کریں";
        first_option.value="";
        class_parent.appendChild(first_option);
        $(data).each(function(index, element) {
            var create_option = document.createElement("option");
            create_option.innerText = element["class"];
            create_option.value = element["id"];
            if(element["id"] == class_id){
                create_option.selected=true;
            }
            class_parent.appendChild(create_option);
        });



    }
})

}

getClassList();






function getSectionList(section) {


$.ajax({
    url: "{{ url('get-sections-list') }}",
    type: "get",
    success: function(data) {

        var class_parent = $("#section_id")[0];

        class_parent.innerHTML = "";

        var first_option = document.createElement("option");
        first_option.innerText="سیکشن منتخب کریں";
        first_option.value="";
        class_parent.appendChild(first_option);
        $(data).each(function(index, element) {
            var create_option = document.createElement("option");
            create_option.innerText = element;
            create_option.value = element;
            if(element == section){
                create_option.selected=true;
            }
            class_parent.appendChild(create_option);
        });



    }
})

}

getSectionList();






function getTeacherList(teacher_id) {


$.ajax({
    url: "{{ url('get-teacher-list') }}",
    type: "get",
    success: function(data) {

        var teacher_parent = $("#teacher_id")[0];
        teacher_parent.innerHTML = "";

        var first_option = document.createElement("option");
        first_option.innerText="استاد منتخب کریں";
        first_option.value="";
        teacher_parent.appendChild(first_option);
        $(data).each(function(index, element) {
            var create_option = document.createElement("option");
            create_option.innerText = element["employee_name"];
            create_option.value = element["id"];
            if(element["id"] == teacher_id){
                create_option.selected = true;
            }
            teacher_parent.appendChild(create_option);
        });



    }
})

}

getTeacherList();



        $(".toselect-tag").select2();

        // Set to true if in edit mode, false otherwise

        // Set the multiple option based on the edit state
        
        function selectTwoMultiple(multiple = true){
           console.log(multiple);
            $('.multiple').select2({

           multiple: multiple,
           theme: "classic"
       });
        }

        selectTwoMultiple();
       


    </script>
@endsection
