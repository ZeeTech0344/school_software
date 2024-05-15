

@php
    if(count($diary)<=0){
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

@media print {
    #diary_table td, #diary_table th{
    font-family: "Jameel Noori Nastaleeq Kasheeda";
}
}







</style>

<table id="diary_table" dir="rtl">
    @foreach ($diary as $data_get_from_diary)
        <h2 style="font-size: 30px;" class="background-color">یوم کیفیت</h2>

        <tr class="background-color">
            <td>نام</td>
            <td>تاریخ</td>
            <td colspan="4">دن</td>
        </tr>


        <tr>
            <td>{{$data_get_from_diary->getStudent->name}}</td>
            <td>{{date_format(date_create($data_get_from_diary->created_at),"d-m-y")}}</td>
            <td colspan="4">{{date_format(date_create($data_get_from_diary->created_at),"l")}}</td>
        </tr>
        <tr class="background-color">
            <td>وقت آمد مدرسہ</td>
            <td>سبق سنایا</td>
            <td colspan="4">وقت رخصت</td>
        </tr>
        <tr>
            <td> {{$data_get_from_diary->getAttendances[0]->time_in}} </td>
            <td> {{$data_get_from_diary->sabaq_sunaya}} </td>
            <td colspan="4"></td>
        </tr>
        <tr class="background-color">
            <td>سبق سنائی</td>
            <td>سامع</td>
            <td>منزل پارہ</td>
            <td>سنایا</td>
            <td colspan="2">سامع</td>
        </tr>
        <tr>
            <td>{{$data_get_from_diary->sabaq_sunai}}</td>
            <td>{{$data_get_from_diary->samay_one}}</td>
            <td>{{$data_get_from_diary->manzil_para}}</td>
            <td>{{$data_get_from_diary->sunaya}}</td>
            <td colspan="2">{{$data_get_from_diary->samay_two}}</td>
        </tr>
        <tr class="background-color">
            <td>امتحانی پارہ یا تین سبق</td>
            <td>دیا</td>
            <td>سامع</td>
            <td>کچا سبق سنایا</td>
            <td colspan="2">سامع</td>
        </tr>
        <tr>
            <td>{{$data_get_from_diary->para_ya_teen_sabaq}}</td>
            <td>{{$data_get_from_diary->dia}}</td>
            <td>{{$data_get_from_diary->samay_three}}</td>
            <td>{{$data_get_from_diary->kacha_sabaq_sunaya}}</td>
            <td colspan="2">{{$data_get_from_diary->samay_four}}</td>
        </tr>
        <tr class="background-color">
            <td>صبح حاضر ہوا</td>
            <td>بعد ظہر حاضرہوا</td>
            <td colspan="4">بعد از مغرب حاضر  ہوا</td>
        </tr>
        <tr>
        
            <td>{{$data_get_from_diary->subha_hazir_hua}}</td>
            <td>{{$data_get_from_diary->bad_zuhr_hazir_hua}}</td>
            <td  colspan="4">{{$data_get_from_diary->bad_maghrib_hazir_hua}}</td>
           </tr>

        <tr class="background-color">
            <td>سورہ نمبر</td>
            <td>  پارہ نمبر</td>
                <td>  آیت نمبر</td>
                    <td> تا</td>
                        <td colspan="2">  تک سطریں</td>
        </tr>

        <tr>
            <td>{{$data_get_from_diary->surah}}</td>
            <td>   {{$data_get_from_diary->para_no}}</td>
            <td> {{$data_get_from_diary->ayat_no}}</td>
            <td> {{$data_get_from_diary->taa}}</td>
            <td colspan="2">{{$data_get_from_diary->tak_satrain}}</td>
        </tr>
        @php
            $haystack = $data_get_from_diary->hazrinmaz;
        @endphp
        <tr class="background-color">
            <td colspan="6">حاضری نماز</td>
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
    <td colspan="6" class="background-color">کیفیت اور ہدایت</td>
</tr>
<tr>
    <td colspan="6">{!!$data_get_from_diary->description!!}</td>
</tr>


    @endforeach

      
    
</table>


