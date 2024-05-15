


<style>
    td{
        text-align: right;
        padding:10px;
    }
</style>

<div>
    {{-- <input type="button" class="btn btn-sm btn-danger" data-id="{{ $create_paper_id }}" value="ترمیم کریں" id="edit_marks"> --}}
</div>
<table style="width:100%;">
    <thead>
        <tr>
            <th>حاصل کردہ نمبر</th>
            <th>طالب علم کا نام</th>
            <th>سیریل نمبر</th>
        </tr>
    </thead>
    @php
        $sr = 0;
    @endphp
    @foreach ($all_students as $student)
    @php
    $sr++;
    @endphp 
                <tr>
                    <td style="display:flex; justify-content:flex-end;" >
                         <input type="number" class="form-control obtained_marks w-25" value="{{ isset($student["marks"]) ? $student["marks"] : "" }}" >
                         <input type="hidden" class="form-control student_id" >
                        </td>
                    <td> {{ $student["admission"]["name"] }} </td>
                    <td>{{ $sr }}</td>
                </tr>
    @endforeach
    <tr><td colspan="3"><input type="button" class="btn btn-sm btn-success" id="save_obtained_marks" value="تمام نمبرسیو کریں"></td></tr>
</table>

<script>

var get_student_number = $(".obtained_marks");
var get_student_ids = $(".student_id");



    $("#save_obtained_marks").click(function(){
        var marks = [];

        $('.obtained_marks').each(function() {
            marks.push($(this).val());
        });

        var student_ids = [];
        $('.student_id').each(function() {
            student_ids.push($(this).val());
        });

        var session_id = $("#session").val();
        var create_paper_id = "<?php echo $create_paper_id ?>";

        function getCustomTimestampInPakistan() {
        const now = new Date(Date.now() + 5 * 60 * 60 * 1000); // Adding 5.5 hours for Pakistan Standard Time (PKT)
        return now.toISOString().replace('T', ' ').slice(0, 23);
        }

        const customTimestampPakistan = getCustomTimestampInPakistan();
       


        const resultArray = student_ids.map((student_id, index) => ({
        student_id: parseInt(student_id),
        marks: parseInt(marks[index]),
        session_id : session_id,
        create_paper_id : create_paper_id,
        created_at : customTimestampPakistan,
        updated_at : customTimestampPakistan
        
        }));

        

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('insert-obtained-marks') }}",
                type: "POST",
                contentType: 'application/json',
                data: JSON.stringify({ myArray: resultArray }),
                success: function(data) {

                    console.log(data);

                },
                error: function(data) {


                }

        })




    })





  

 
    




</script>
