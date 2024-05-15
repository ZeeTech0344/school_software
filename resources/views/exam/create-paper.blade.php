<form id="paper-marks-form" class="d-flex justify-content-center">
    <div class="form-row w-75">
        <input type="submit" class="btn btn-sm btn-danger" value="پرچہ  بنائیں">
        <div class="col">
            <input type="number" name="marks" id="marks" class="form-control" onclick="removeBorder(this)"
                placeholder="ٹوٹل  نمبر">
        </div>
        <div class="col">
            <select name="connect_teacher_id" id="connect_teacher_id" onchange="removeBorder(this)"
                class="form-control">
                <option value="">پرچہ منتخب کریں</option>
            </select>
        </div>
        <div class="col">
            <select name="exam_id" id="exam_id" onchange="removeBorder(this)" class="form-control">
                <option value="">امتحان منتخب کریں</option>
            </select>
        </div>

        <input type="hidden" id="paper_hidden_id" name="paper_hidden_id">

    </div>
</form>
<div class="table-responsive">
    <div class="mb-3">

    </div>
    <table class="table table-bordered get-list-of-paper-created" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th> ایکشن </th>
                <th> مارکس </th>
                <th>پرچہ</th>
                <th>امتحان</th>
                <th> ٹیچر </th>

            </tr>
        </thead>

        <tbody>
        </tbody>
    </table>
</div>
<script>
    var paper_list = $('.get-list-of-paper-created').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('get-paper-list') }}",
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
                data: 'marks',
                name: 'marks'
            },

            {
                data: 'paper',
                name: 'paper'
            },

            {
                data: 'exam',
                name: 'exam'
            },

            {
                data: 'connected_teacher_id',
                name: 'connected_teacher_id'
            }
        ],

        success: function(data) {
            console.log(data);
        }
    });

    // fine_list.draw();

    //search functions
    $("#session").on('change', function(e) {

        paper_list.draw();

    });


    $("#search_value").on('keyup', function(e) {

        if (e.key === 'Enter' || e.keyCode === 13) {
            paper_list.draw();
        }
    });




    $('#paper-marks-form').validate({
        errorPlacement: function(error, element) {
            element[0].style.border = "1px solid red";
        },
        rules: {
            connect_teacher_id: "required",
            marks: "required"
        },

        submitHandler: function(form) {

            var session_id = $("#session")[0].value;
            var formData = new FormData(form);
            formData.append('session_id', session_id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('insert-create-paper') }}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    console.log(data);
                    $("#paper_hidden_id").val("");
                    $('#paper-marks-form')[0].reset();

                    paper_list.draw();
                    successAlert();

                },
                error: function(data) {


                }

            })
        }
    });



    function getTeacherAttachData(teacher_connect_id) {

        var session = $("#session")[0].value;
        console.log(session);


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

                var paper = $("#connect_teacher_id")[0];
                paper.innerHTML = "";
                var create_first_option = document.createElement("option");
                create_first_option.innerText = "پرچہ  منتخب  کریں";
                create_first_option.value = "";
                paper.appendChild(create_first_option);

                $(data).each(function(index, element) {
                    var create_option = document.createElement("option");
                    create_option.innerText = element["get_books"]["book"] + " (" + element[
                        "get_classes"]["class"] + " - " + element["section"] + ")";
                    create_option.value = element["id"];
                    if (element["id"] == teacher_connect_id) {
                        create_option.selected = true;
                    }

                    paper.appendChild(create_option);
                });



            }
        })


    }

    getTeacherAttachData();








    function getExams(exam_id_get) {

        var session = $("#session")[0].value;



        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('get-exams') }}",
            type: "GET",
            data: {
                session: session
            },
            success: function(data) {

                console.log(data);

                var exam_parent = $("#exam_id")[0];
                exam_parent.innerHTML = "";
                var create_first_option = document.createElement("option");
                create_first_option.innerText="امتحان  منتخب  کریں";
                create_first_option.value="";
                exam_parent.appendChild(create_first_option);

                $(data).each(function(index, element) {
                    var create_option = document.createElement("option");
                    create_option.innerText = element["exam_name"];
                    create_option.value = element["id"];
                    if(element["id"] == exam_id_get){
                        create_option.selected=true;
                    }

                    exam_parent.appendChild(create_option);
                });



            }
        })


    }

    getExams();







    function removeBorder(e) {
        e.style.border = "";
        if (e.id == "image") {
            $("#image_name").attr("src", e.value);
        }
    }






    $(document).on("click", ".edit_paper_data", function() {

        var id = $(this).data("id");

       

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ url('edit-paper-data') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(data) {

                getTeacherAttachData(data["connect_teacher_id"]);
                // getTeacherAttachData(data["connect_teacher_id"]);
                $("#marks").val(data["marks"]);
                $("#paper_hidden_id").val(data["id"]);


            }
        })

    })



    $(document).on("click", ".add_student_marks", function() {

        var close = $("#close-view")[0].click();
        var data = $(this).data("id");
        var session_id = $("#session")[0].value;

         var url = "{{ url('add-student-marks') }}" + "/" + data + "/" + session_id;

         extraLargeModal(url);

    })
</script>
