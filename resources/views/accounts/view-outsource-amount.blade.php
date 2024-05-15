
<style>
    .expense_bill {
        border-collapse: collapse;
        width: 100%;
        font-family: "Jameel Noori Nastaleeq Kasheeda" !important;
    }

    .expense_bill td,
    .expense_bill th {
        padding: 10px;
        text-align: center;
        border:1px solid black;
    }

    .heading {
        text-align: center;
        font-family: "Jameel Noori Nastaleeq Kasheeda" !important;
    }

    .expense_span {
        border-bottom: 1px solid black;
        display: inline-block;
        text-align: right;
    }

    .sub_heading {
        /* width: auto; */ /* Removed width setting */
        /* white-space: nowrap; */ /* Removed nowrap */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dashes{
        border-style: dashed;
    border-width: 1px; /* Adjust the size of dashes */
    border-color: black; /* Adjust the color if needed */
    border-radius: 0; /* Optional: adjust if you want sharp edges */
    width: 100%; /* Adjust the width of the dashes */
    margin: 10px 0; /* Adjust margin as needed */
    }

    
</style>



<h4 class="heading">جامع تذکیر القرآن</h4>

<h5 class="heading"> {{ $bank_amount_outsource[0]->bank_name }} رسید</h5>
<table dir="rtl" class="expense_bill">


    <tr>
        <th class="sub_heading">رسید نمبر</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->invoice_no }}</span></td>
        <th class="sub_heading">تاریخ</th>
        <td><span class="expense_span">{{ date_format(date_create($bank_amount_outsource[0]->created_at), 'd-m-Y') }}</span></td>
        <th class="sub_heading">وقت</th>
        <td><span class="expense_span">{{ date_format(date_create($bank_amount_outsource[0]->created_at), 'h:i:s') }}</span></td>
    </tr>

    <tr>
        <th class="sub_heading">معطی کا نام</th>
        <td><span class="expense_span">{{$bank_amount_outsource[0]->given_by }}</span></td>
        <th class="sub_heading">رابطہ نمبر</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->phone_no }}</span></td>
        <th class="sub_heading">پتہ</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->address }}</span></td>
    </tr>

    <tr>
        <th class="sub_heading">رقم ہندسوں میں</th>
        <td><span class="expense_span">{{$bank_amount_outsource[0]->amount }}</span></td>
        <th class="sub_heading">رقم لفظوں میں</th>
        <td><span class="expense_span" id="amount_in_word_two"></span></td>
        <th class="sub_heading">پتہ</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->address }}</span></td>
    </tr>


    <tr>
        <th class="sub_heading">بمد</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->fund_type }}</span></td>
        <th class="sub_heading">رقم بصورت</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->payment_type }}</span></td>
        <th class="sub_heading">رسید دینے والا </th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->getEmployee->name }}</span></td>
    </tr>

    <tr>
        <th>ریمارکس</th>
        <td colspan="5" style="text-align: right;">{{$bank_amount_outsource[0]->remarks}}</td>
    </tr>

</table>

<div >
    <div style="padding-top:50px; display:flex; justify-content:space-between;">
        <label style="margin-right:auto; margin-top:30px;" for="">................................دستخط ناظم </label>
        <label style="margin-left:auto; margin-top:30px;" for="">دستخط ناظم................................</label>
    </div>
    
</div>


<div class="dashes">
</div>


<h4 class="heading">جامع تذکیر القرآن</h4>

<h5 class="heading"> {{ $bank_amount_outsource[0]->bank_name }} رسید</h5>
<table dir="rtl" class="expense_bill">


    <tr>
        <th class="sub_heading">رسید نمبر</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->invoice_no }}</span></td>
        <th class="sub_heading">تاریخ</th>
        <td><span class="expense_span">{{ date_format(date_create($bank_amount_outsource[0]->created_at), 'd-m-Y') }}</span></td>
        <th class="sub_heading">وقت</th>
        <td><span class="expense_span">{{ date_format(date_create($bank_amount_outsource[0]->created_at), 'h:i:s') }}</span></td>
    </tr>

    <tr>
        <th class="sub_heading">معطی کا نام</th>
        <td><span class="expense_span">{{$bank_amount_outsource[0]->given_by }}</span></td>
        <th class="sub_heading">رابطہ نمبر</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->phone_no }}</span></td>
        <th class="sub_heading">پتہ</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->address }}</span></td>
    </tr>

    <tr>
        <th class="sub_heading">رقم ہندسوں میں</th>
        <td><span class="expense_span">{{$bank_amount_outsource[0]->amount }}</span></td>
        <th class="sub_heading">رقم لفظوں میں</th>
        <td><span class="expense_span" id="amount_in_word"></span></td>
        <th class="sub_heading">پتہ</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->address }}</span></td>
    </tr>


    <tr>
        <th class="sub_heading">بمد</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->fund_type }}</span></td>
        <th class="sub_heading">رقم بصورت</th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->payment_type }}</span></td>
        <th class="sub_heading">رسید دینے والا </th>
        <td><span class="expense_span">{{ $bank_amount_outsource[0]->getEmployee->name }}</span></td>
    </tr>

    <tr>
        <th>ریمارکس</th>
        <td colspan="5" style="text-align: right;">{{$bank_amount_outsource[0]->remarks}}</td>
    </tr>

</table>

<div >
    <div style="padding-top:50px; display:flex; justify-content:space-between;">
        <label style="margin-right:auto; margin-top:30px;" for="">................................دستخط ناظم </label>
        <label style="margin-left:auto; margin-top:30px;" for="">دستخط ناظم................................</label>
    </div>
    
</div>


<script>

    function numberToUrduWord(number) {
        var onesWords = ["", "ایک", "دو", " تین" , " چار", " پانچ", " چھ", " سات", " آٹھ", "نو"];
        var tensWords = [ " گیارہ", " بارہ", " تیرہ", " چودہ", " پندرہ", " سولہ", " سترہ", " اٹھارہ", " انیس"];
        var twentiesWords = [ " بیس", " اکیس", " بائیس", " تئیس", " چوبیس", " پچس", " چھبیس", " ستائیس", " اٹھائیس", " انتیس"];
        var thirtiesWords = [ " تیس", " اکتیس", " بتیس", " تینتیس", " چونتیس", " پینتیس", " چھتیس", " سینتیس", " اٹھتیس", " انتالیس"];
        var fortiesWords = [" چالیس", " اکتالیس", " بیالیس", " تینتالیس", " چوالیس", " پینتالیس", " چھیالیس", " سینتالیس", " اڑھتالیس", " انچاس"];
        var fiftiesWords = [ " پچاس", " اکاون", " باون", " ترپن", " چون", " پچپن", " چھپن", " ستاون", " اٹھاون", " انسٹھ"];
        var sixtiesWords = [ " ساٹھ", " اکسٹھ", " باسٹھ", " تریسٹھ", " چونسٹھ", " پینسٹھ", " چھیاسٹھ", " ستاسٹھ", " اٹھاسٹھ", " انہتر"];
        var seventiesWords = [" ستر", " اکہتر", " بہتر", " تہتر", "چوہتر", "پچہتر", " چھہتر", " ستہتر", " اٹھہتر", " اناسی"];
        var eightiesWords = [ " اسی", " اکیاسی", " بیاسی", " تیراسی", " چوراسی", " پچاسی", " چھیاسی", " ستاسی", " اٹھاسی", " انانوے"];
        var ninetiesWords = [ " نوے", " اکانوے", " بانوے", " ترانوے", " چورانوے", " پچانوے", " چھیانوے", " ستانوے", " اٹھانوے", " ننانوے"];
        var hundred = "سو";
        var thousand = " ہزار  " ;
        var lakh = " لاکھ "
        var crore = " کروڑ ";

        // Function to convert number to words
        function convertToWords(number) {
            if (number === 0) {
                return "صفر";
            }

            var words = "";

            if (Math.floor(number / 10000000) > 0) {
                words += numberToUrduWord(Math.floor(number / 10000000)) + crore + " ";
                number %= 10000000;
            }

            if (Math.floor(number / 100000) > 0) {
                words += numberToUrduWord(Math.floor(number / 100000)) + lakh + " ";
                number %= 100000;
            }

            if (Math.floor(number / 1000) > 0) {
                words += numberToUrduWord(Math.floor(number / 1000)) + thousand + " ";
                number %= 1000;
            }

            if (Math.floor(number / 100) > 0) {
                words += onesWords[Math.floor(number / 100)] + " " + hundred + " ";
                number %= 100;
            }

            if (number > 0) {
                if (words !== "") {
                    words += " اور ";
                }

                if (number < 10) {
                    words += onesWords[number];
                } else if (number < 20) {
                    words += tensWords[number - 10];
                } else if (number < 30) {
                    words += twentiesWords[number - 20];
                } else if (number < 40) {
                    words += thirtiesWords[number - 30];
                } else if (number < 50) {
                    words += fortiesWords[number - 40];
                } else if (number < 60) {
                    words += fiftiesWords[number - 50];
                } else if (number < 70) {
                    words += sixtiesWords[number - 60];
                } else if (number < 80) {
                    words += seventiesWords[number - 70];
                } else if (number < 90) {
                    words += eightiesWords[number - 80];
                } else {
                    words += ninetiesWords[number - 90];
                }
            }

            return words.trim();
        }
        
        return convertToWords(number);
    }


    var number = "<?php echo  $bank_amount_outsource[0]->amount; ?>";

    var word = numberToUrduWord(number);

    document.getElementById("amount_in_word").innerText = word;

    document.getElementById("amount_in_word_two").innerText = word;
    
   </script>
  