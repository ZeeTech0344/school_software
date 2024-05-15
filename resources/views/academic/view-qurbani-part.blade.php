
<style>


#qurbani-part td, #qurbani-part th{
    border:none;
    font-family: "Jameel Noori Nastaleeq Kasheeda";
}
#qurbani-part td{
    border-bottom:1px solid black;
    padding: 20px;
    
}

#qurbani-part th{
    width: 10%;
}

#qurbani-part td, #qurbani-part th{
    font-size: 20px;
}


#invoice_div h2{
    font-family: "Jameel Noori Nastaleeq Kasheeda";
}

#signature{
    font-family: "Jameel Noori Nastaleeq Kasheeda";
}

</style>

<button class="btn btn-sm btn-danger" onclick="getPdfQurbaniPart()">PDF</button>

<div id="invoice_div">
<h2 style="text-align: center;padding:10px;">جامعہ تذکیر القرآن واہ کینٹ</h2>
<table dir="rtl" style="width: 100%; border:1px solid black;" id="qurbani-part">
    <tr>
        <th>رسید</th>
        <td>{{$qurbani[0]->serial_no}}</td>
        <th>تاریخ</th>
        <td>{{date_format(date_create($qurbani[0]->created_at),"d-m-Y")}}</td>
    </tr>
    <tr>
        <th>جناب</th>
        <td>{{$qurbani[0]->full_name}}</td>
        <th>پتہ</th>
        <td>{{$qurbani[0]->address}}</td>
    </tr>
    <tr>
        <th>رابطہ نمبر</th>
        <td>{{$qurbani[0]->phone_no}}</td>
        <th>جانور نمبر</th>
        <td>{{$qurbani[0]->getQurbaniInfo->qurbani_name}}</td>
    </tr>
    <tr>
        <th>تعداد حصہ</th>
        <td>{{$qurbani[0]->total_parts}}</td>
        <th>رقم</th>
        <td>{{$qurbani[0]->total_parts_amount}}</td>
    </tr>
</table>
<div style="margin-top:50px;" id="signature">
   ......................................................... دستخط وصول کنندہ
</div>
</div>



<script>


    function getPdfQurbaniPart() {
    // Create a new window for printing
    var printWindow = window.open('', '_blank');

    // Append the HTML content for printing to the new window's document
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('#invoice_div h2, #qurbani-part td, #qurbani-part th { border: none; font-family:"Jameel Noori Nastaleeq Kasheeda"; }');
    printWindow.document.write('#qurbani-part td { border-bottom: 1px solid black; }');
    printWindow.document.write('#qurbani-part th { width: 10%; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');

    // Append only the table to the new window's document
    printWindow.document.write(document.getElementById('invoice_div').outerHTML);

    // Append the closing tags for the HTML and body
    printWindow.document.write('</body></html>');

    // Wait for the content to be loaded (you may need to adjust the timeout)
    setTimeout(function() {
        // Print the content
        printWindow.print();

        // Close the new window after printing
        printWindow.close();
    }, 500);
}




    
</script>