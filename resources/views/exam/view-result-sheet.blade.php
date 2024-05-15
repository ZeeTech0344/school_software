<div class="mb-3">
    <label>کلاس</label>
    <select name="exam_class_id" class="form-control" id="exam_class_id">
        <option value="">کلاس منتخب کریں</option>
        @foreach ($classes as $class)
            <option value="{{ $class->id }}"> {{ $class->class }} </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>سیکشن</label>
    <select name="exam_section" class="form-control" id="exam_section">
        <option value="">سیکشن منتخب کریں</option>
        @foreach ($sections as $section)
            <option value="{{ $section }}"> {{ $section }} </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>امتحان</label>
    <select name="exam_list_get" class="form-control" id="exam_list_get">
        <option value="">امتحان منتخب کریں</option>
    </select>
</div>
<div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary"  onclick="printResults(this)" id="print_result" >رزلٹ پرنٹ </button>
    <button type="submit" class="btn btn-primary" onclick="printResults(this)" id="print_result_sheet">رزلٹ شیٹ پرنٹ </button>
</div>





<script>



    var session = $("#session").val();


    $.ajax({
        url: "{{ url('get-exams') }}",
        type: "GET",
        data: {
            session: session,
        },
        success: function(data) {

            $.each(data, function(index, exam_list_fetch) {

                $('#exam_list_get').append('<option value=' + exam_list_fetch["id"] + '>' +
                    exam_list_fetch["exam_name"] + '</option>');

            });
        }
    });




    function printResults(e) {


        if(e.id == "print_result"){
            var url = "{{ url('print-result') }}";
        }else if(e.id == "print_result_sheet"){
            var url = "{{ url('print-result-sheet') }}";
        }

        var exam_class_id = $("#exam_class_id").val();
        var exam_section = $("#exam_section").val();
        var exam_list_get = $("#exam_list_get").val();
        var exam_name = $('#exam_list_get option:selected').text();

        

        if(exam_class_id !== "" && exam_section !== "" && exam_list_get !== "" ){

          $.ajax({
          headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            url: url,
            type: "POST",
            data: {
                session: session,
                exam_class_id: exam_class_id,
                exam_section: exam_section,
                exam_list_get: exam_list_get,
                exam_name:exam_name
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

        }

        

    }











    
</script>
