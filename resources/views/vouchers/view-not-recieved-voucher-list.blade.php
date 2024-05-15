<div class="mb-3">
    <label>کلاس</label>
    <select name="defaulterclass_id" class="form-control" id="defaulter_class_id">
        <option value="">کلاس منتخب کریں</option>
        @foreach ($classes as $class)
            <option value="{{ $class->id }}"> {{ $class->class }} </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>سیکشن</label>
    <select name="defaulter_section" class="form-control" id="defaulter_section">
        <option value="">سیکشن منتخب کریں</option>
        @foreach ($sections as $section)
            <option value="{{ $section }}"> {{ $section }} </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>مہینہ</label>
    <input type="month" class="form-control" id="defaulter_month" name="defaulter_month">
</div>
<div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-success" onclick="getDefaulterPdf()">ویو</button>
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














    function getDefaulterPdf() {

        var defaulter_class_id = $("#defaulter_class_id").val();
        var defaulter_section = $("#defaulter_section").val();
        var defaulter_month = $("#defaulter_month").val()

        if (defaulter_month !== "") {
        $(".paynow-close")[0].click();
        var url = "{{ url('not-recieve-voucher-list') }}" + "/" + defaulter_month + "/" + session + "/" + defaulter_class_id + "/" + defaulter_section;

        viewModal(url);

        }


    }
</script>
