<style>
    #grandsheet_table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
        font-family: "Jameel Noori Nastaleeq Kasheeda";
        font-size: 20px;
    }

    #grandsheet_table tr,
    #grandsheet_table td,
    #grandsheet_table th {
        border: 1px solid black;
        padding: 5px;
    }

    .center_text {
        text-align: center;
    }
</style>


@php
  
    foreach ($results as &$student) {
        // Get the IDs of books already existing in the student's marks array
    $existingBookIds = array_column($student['marks'], 'create_paper_id');

    // Loop through each book
    foreach ($books as $book) {
        // Check if the book exists in the student's marks array
            if (!in_array($book['id'], $existingBookIds)) {
                // If the book doesn't exist, add it with marks zero
            $student['marks'][] = [
                'id' => null, // Assuming null for new entry
                'create_paper_id' => $book['id'],
                'student_id' => $student['id'],
                'marks' => 0,
                'session_id' => 1, // Assuming a default session ID
                'created_at' => date('Y-m-d H:i:s'), // Current timestamp
                'updated_at' => date('Y-m-d H:i:s'), // Current timestamp
            ];
        }
    }
}

unset($student);

foreach ($results as &$student) {
    foreach ($check_position as $positionData) {
        if ($student['id'] === $positionData['id']) {
            $student['position'] = $positionData['position'];
            break;
        }
    }
}


    $data = (object) $results;

@endphp


@php
    $class_all_book_id_array = [];
@endphp
{{-- <h3 style="text-align: center;padding:10px;  font-family:'Jameel Noori Nastaleeq Kasheeda';">جامعہ تذکیر القرآن واہ کینٹ</h3> --}}

<h3 style="display: flex; justify-content:center;"><img src="{{asset('images/header.jpg')}}" style="height: 70px;" alt=""></h3>

<table id="grandsheet_table" dir="rtl">
    <thead>
        <tr>
            <th style="text-align: right">Name</th>

            @php
                foreach ($books as $book_name) {
                    echo '<th class="center_text">' . $book_name['book'] . '</th>';
                }
            @endphp
            <th>ٹوٹل نمبر</th>
            <th>پوزیشن</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $marks_data)
            @php
                $total_marks = 0;
            @endphp
            <tr>
                <td>{{ $marks_data['name'] }}</td>

                @php
                    $paper_data = usort($marks_data['marks'], function ($a, $b) {
                        return $a['create_paper_id'] - $b['create_paper_id'];
                    });
                @endphp


                @foreach ($marks_data['marks'] as $get_book_data)
                    <td class="center_text">{{ $get_book_data['marks'] }}</td>

                    @php
                        $total_marks = $total_marks + $get_book_data['marks'];
                    @endphp
                @endforeach
                <td class="center_text">{{ $total_marks }}</td>
                
                <td  class="center_text" style="{{ 
                    $marks_data['position'] == 1 ? 'background-color: #b4ceb4' : 
                    ($marks_data['position'] == 2 ? 'background-color: #fff079' : 
                    ($marks_data['position'] == 3 ? 'background-color: #ffafaf' : '')) 
                }}">
                    {{ $marks_data['position'] }}
                </td>
                
                
            </tr>
        @endforeach
    </tbody>
</table>
