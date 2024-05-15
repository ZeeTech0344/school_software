@php


// Chunk size for two cards per row
$chunkSize = 2;

// Chunk the array into groups of $chunkSize
$chunks = array_chunk($admissions, $chunkSize);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .id_card {
            /* border: 1px solid rgb(0, 0, 0); */
            width: 35%;
            page-break-inside: avoid; /* Prevent page breaks inside the card */
            text-align: right;
            box-shadow: 1px 1px 5px black;
            margin-right: 20px;
            
        }
        .row {
            display: flex;
            justify-content: center;
            padding:10px;

            border-bottom:1px dotted black;
            
        }
        p{
            /* padding:3px; */
            padding-right: 20px;
            font-size: 15px;
        }

        #img_div{
            /* border:3px solid black; */
        }

        #img_div img{
            height:120px;
            box-shadow: 2px 2px 5px black;
        }
    </style>
</head>
<body>
    
    @php
    // Iterate through the chunks and print the cards
    $totalChunks = count($chunks);
    foreach ($chunks as $index => $chunk) {
        echo '<div class="row">';
        foreach ($chunk as $student) {
            // $student_id = $student['id'];
            $student_detail = QrCode::size(70)->generate($student["id"].",".$student["admission_year"]);
            echo '<div class="id_card" style="position:relative;">
                <div style="text-align:right;padding-right:10px; margin-top:10px;">
                    <img style="width:130px; position: absolute; left:10px;  top:40px;" src="'.asset("images/header.jpg").'">
                    <img style="width:80px;" src="'.asset("images/logo.jpg").'"></div>
                <div id="img_div" style="position:absolute;left:20px; top:90px;" ><img style="width:100px;" src="'.asset("images/".$student["image"]).'"></div>
            <div>';
            // Print the student details in the card
            echo '<b><p>نام :' . $student['name'] . ' </p></b>';
            echo '<b><p> رول نمبر :' . $student['roll_no'] . ' </p></b>';
            echo '<b><p> کلاس: ' . $student['get_class']['class'] .' </p></b>';
            echo '<b><p> والد کا نام: ' . $student['father_name'] . ' </p></b>';
            echo '<b><p>  بتہ: ' . $student['address']." - ".$student['mobile_no']. ' </p></b>';
            echo '<div style="text-align:center;">'.$student_detail.'</div>';
            // Add more details as needed
            echo '</div></div>'; // Close the card
        }
        
        // If it's the last chunk and the number of cards is odd, add an empty card for centering
        if ($index === $totalChunks - 1 && count($chunk) % $chunkSize === 1) {
            echo '<div class="card"></div>'; // Empty card
        }

        echo '</div>'; // Close the row
    }
    @endphp
</body>
</html>

