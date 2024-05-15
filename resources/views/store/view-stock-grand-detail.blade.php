@extends('layout.structure')

<style>

    td{
        text-align: center;
    }
    th{
        text-align: center;
    }

    table{
        text-align: center;
    }

</style>

@section('content')
    <div class="col-12 d-flex justify-content-center">
        <div class="col-12 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Grand Detail</h6>
                    <div>
                        <a class="btn btn-sm btn-primary" id="view_report">View Report</a>
                        <a class="btn btn-sm btn-danger" id="get_grand_report_pdf">PDF Report</a>
                    </div>
                </div>
                
                  
                
                 <div class="card-body">
@php
    $sr = 1;
    
@endphp
<div class=" p-2 d-flex justify-content-end">
           
    <input type="text" id="search_employee" name="search" placeholder="Search Stock......." onchange="checkValues(this)" class="form-control w-25" >

</div>
<table class="table table-bordered no-footer" id="stock_grand_report">
    <thead>
        <tr>
            <th>
                Sr#
            </th>
            <th>
                Items
            </th>
            <th>
                Total Stock
            </th>
            <th>
                Send Stock
            </th>
            <th>
                Return Stock
            </th>
            <th>
                Remaining
            </th>
            <th>
                Action
            </th>
        </tr>    
    </thead>

    <tbody>
       @foreach ($items as $get_date)

            <tr>
                <td>
                    {{ $sr++ }}
                </td>
                <td style="text-align: left;">
                    {{ $get_date->item_name." (".$get_date->getVendors->name.") " }}
                </td>
                <td>
                
                    {{ $get_date->total_stock_sum_weight ? $get_date->total_stock_sum_weight  : "-"}}
                   
                </td>
                <td>
                    {{ $get_date->send_stock_sum_stock_qty_send ? $get_date->send_stock_sum_stock_qty_send : "-" }}
                   
                </td>
                 <td>
                    {{ $get_date->return_stock_sum_stock_qty_return ?  $get_date->return_stock_sum_stock_qty_return : "-"}}
                
                </td>
                <td>
                    {{ ($get_date->total_stock_sum_weight- $get_date->send_stock_sum_stock_qty_send)  + $get_date->return_stock_sum_stock_qty_return }}
                </td>
                <td>
                    <input type="button" class="btn btn-sm btn-danger get_data_of_stock" data-id='{{ $get_date->id }}' value="View Remaining">
                </td>
            </tr>

           

       @endforeach
    </tbody>
    
</table>



            </div>

        </div>
        </div>

    </div>

@endsection




@section('script')
<script>

$("#search_employee").keyup(function () {

var value = this.value.toLowerCase().trim();

$("#stock_grand_report tr").each(function (index) {
    if (!index) return;
    $(this).find("td").each(function () {
        var id = $(this).text().toLowerCase().trim();
        var not_found = (id.indexOf(value) == -1);
        $(this).closest('tr').toggle(!not_found);
        return not_found;
    });
});
});


$(document).on("click", ".get_data_of_stock", function() {

var data = $(this).data("id");

var url = "{{ url('get_stock_detail_using_item') }}" + "/" + data;

payNowModalBody(url);

})



$("#view_report").click(function(){

    var url = "{{ url('remaining_stock') }}";

    viewModal(url);

})




$("#get_grand_report_pdf").click(function() {

    $("#wait").css("display", "block");

$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "{{ url('get-whole-grand-pdf-report') }}",
    type: "POST",
   
    success: function(data) {

        const pdfData = data[0];
        // Create a blob object from the base64-encoded data
        const byteCharacters = atob(pdfData);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], {
            type: 'application/pdf'
        });


        // Create a URL for the blob object
        const url = URL.createObjectURL(blob);

        // Create a link element with the URL and click on it to download the PDF file
        const link = document.createElement('a');
        link.href = url;
        link.download = 'grand-report.pdf';
        document.body.appendChild(link);
        link.click();

        $("#wait").css("display", "none");
    }


})


});

  




</script>


@endsection