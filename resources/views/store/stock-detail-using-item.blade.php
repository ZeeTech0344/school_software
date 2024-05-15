
<style>
    table{
        width: 100%;
        text-align: center;
    }
</style>

@php
    $check_stock = 0;
@endphp

<table>
    <tr>
        <th>
            Date
        </th>
        <th>
            Remaining
        </th>
        <th>
            Action
        </th>
    </tr>
    @foreach ($stock as $get_data)

    @php
        $check =  ( $get_data->weight - $get_data->get_send_record_sum_stock_qty_send ) + $get_data->get_return_record_sum_stock_qty_return;
    @endphp
        @if ($check>0)
        <tr>
            <td>
                    {{ date_format(date_create($get_data->date),"d-m-Y") }}
            </td>
            <td>
                {{  $check }}
            </td>
            <td>
                
                @php
                    //$split_string = $get_data->id . "," . $get_data->vendor_id . "," . $get_data->getVendors->name . "," . $get_data->item_id . "," . $get_data->getItems->item_name . "," . $get_data->weight . "," . $get_data->current_rate;
               
               
                    $split_string = $get_data->id . "," . $get_data->vendor_id . ",(" . $get_data->getVendors->name.")," . $get_data->item_id . ",(" . $get_data->getItems->item_name . ")," . $get_data->weight . "," . $get_data->current_rate;
                

               @endphp
                <a class="btn btn-sm btn-primary send_stock_btn"  disabled href="{{ url('send-stock') . '/' .  $split_string }}">Send</a>
            </td>
        </tr>
        @endif
            
    @endforeach
</table>


<script>


// Function to disable all anchor tags except the first one
function disableAllAnchorsExceptFirst() {
    const allAnchors = document.querySelectorAll('.send_stock_btn');
    
    allAnchors.forEach((anchor, index) => {
        anchor.addEventListener('click', function(event) {
            if (index !== 0) {
                event.preventDefault(); // Prevent following the link for other anchors
            }
        });
    });
}

// Example usage: Disable all anchor tags except the first one
disableAllAnchorsExceptFirst();






</script>