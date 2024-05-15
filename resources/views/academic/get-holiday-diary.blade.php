

@php
    if(!$holidayDiary){
        echo  "<h3 style='text-align:center;'>ڈائری نہیں بنائی</h3>";
    }
    return false;
@endphp


<style>
    #diary_table th, td, h2{
        border:1px solid rgb(230 230 230);
        padding: 5px;
        border-collapse: collapse;
        font-family: "Jameel Noori Nastaleeq Kasheeda";
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }
    #diary_table{
        width: 100%;
    }
    
    .background-color{
        background-color: #4e73df;
        color:white;
    }
    
    </style>

    <table id="diary_table" dir="rtl">
            <tr class="background-color">
                <td colspan="6">چھٹی وار</td>
            </tr>
            <tr class="background-color">
                <td>نام</td>
                <td>تاریخ</td>
                <td colspan="4">بروز</td>
            </tr>
            <tr>
                <td>{{$holidayDiary->getStudent->name}}</td>
                <td>{{date_format(date_create($holidayDiary->created_at), "d-m-Y")}}</td>
                <td colspan="4">{{date_format(date_create($holidayDiary->created_at), "l")}}</td>
            </tr>
            <tr>
                <td colspan="6" class="background-color">گھر میں معمولات</td>
            </tr>
            
            <tr>
                <td colspan="6">{{$holidayDiary->ghar_kay_mamoolat}}</td>
            </tr>

            <tr>
                <td colspan="6" class="background-color">سونے کا وقت تحریر کریں</td>
            </tr>
            <tr>
                <td colspan="6">{{$holidayDiary->sonay_ka_waqt}}</td>
            </tr>

            @php
                $questions_id = $holidayDiary->questions;
            @endphp
           
            @foreach ($get_questions as $question)
                <tr>
                    <td colspan="5">{{$question->question}}</td>
                    @php
                        if (stripos($questions_id, $question->id) !== false) {
                            echo "<td>ہاں</td>";
                        } else {
                            echo "<td>نہیں</td>";
                        }
                    @endphp
                </tr>
            @endforeach


            @php
                 $haystack = $holidayDiary->hazrinmaz;
            @endphp

            <tr class="background-color">
                <td colspan="6" class="background-color">حاضری نماز</td>
            </tr>
            <tr >
                <td>تہجد</td>
                <td>فجر</td>
                <td>ظہر</td>
                <td>عصر</td>
                <td>مغرب</td>
                <td>عشاء</td>
            </tr>
            <tr>
        @php
            $prayers = ["تہجد", "فجر", "ظہر", "عصر", "مغرب", "عشاء"];
            foreach ($prayers as $prayer) {
                echo "<td>" . (strpos($haystack, $prayer) !== false ? "ہاں" : "نہیں") . "</td>";
            }
        @endphp
    </tr>

    <tr>
        <td colspan="6" class="background-color">ہدایات معلم</td>
    </tr>
    <tr>
        <td colspan="6"> {{$holidayDiary->hidayat_mualam}} </td>
    </tr>
    <tr>
        <td colspan="6" class="background-color">ہدایات سرپرست</td>
    </tr>
    <tr>
        <td colspan="6">{{$holidayDiary->hidayat_sarparast}}</td>
    </tr>

    </table>