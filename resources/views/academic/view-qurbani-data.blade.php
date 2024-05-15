
<style>
    #qurbani_parts_table, th, td{
        border:1px solid rgb(230 230 230);
        padding: 10px;
    }
    #qurbani_parts_table{
        width: 100%;
        border-collapse: collapse;
    }
    
    
    </style>
    
    @php
     $sr = 1;
     $total_amount = 0;
     $total_parts = 0;
    @endphp

    <table id="qurbani_parts_table" dir="rtl">
        
        <tr>
            <th>سیریل نمبر</th>
            <th>تاریخ</th>
            <th>نام</th>
            <th>پتہ</th>
            <th>حصے</th>
            <th>اداکردہ رقم</th>
            <th>ریمارکس</th>
            <th>ایکشن</th>
        </tr>
        @foreach($get_qurbani_data as $data_get)

        <tr>
            <td>{{$sr++}}</td>
            <td>{{ date_format(date_create($data_get->created_at),"d-m-Y") }}</td>
            <td>{{ $data_get->full_name }}</td>
            <td>{{ $data_get->address }}</td>
            <td>{{ $data_get->total_parts }}</td>
            <td>{{ $data_get->total_parts_amount }}</td>
            <td>{{ $data_get->remarks }}</td>
            <td><a href="#"  class="btn btn-sm btn-danger get-qurbani-data" data-id="{{$data_get->id}}">دیکھیں</a></td>
        </tr>
        @php
        
        $total_parts = $total_parts + $data_get->total_parts;
        $total_amount = $total_amount + $data_get->total_parts_amount;

        @endphp
        @endforeach
        <tr>
            <td colspan="5">ٹوٹل حصے:{{$total_parts}}</td>
        </tr>
        <tr>
            <td colspan="5">  ٹوٹل رقم:{{$total_amount}}</td>
        </tr>
    </table>

    <script>
    
        $(document).on("click", ".get-qurbani-data" ,function(){

            var id = $(this).data("id")

            var url = "{{url('view-qurbani-part')}}" + "/" + id;

            viewModal(url);

        })
    </script>
