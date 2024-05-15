@extends('layout.structure') 

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
   
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xls font-weight-bold text-primary text-uppercase mb-1">
                               اخراجات </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">40,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xls font-weight-bold text-success text-uppercase mb-1">
                                ٹوٹل رقم</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">215,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xls font-weight-bold text-info text-uppercase mb-1">ٹوٹل سٹاف
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="total_staff"></div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xls font-weight-bold text-warning text-uppercase mb-1">
                                ٹوٹل سٹوڈنٹ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_student"></div>
                        </div>
                        <div class="col-auto">
                            
                            <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">اخراجات</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                      
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="myAreaChart" style="display: block; width: 611px; height: 320px;" width="611" height="320" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection



@section('script')
<script>





function displayImage(input) {
  const imgElement = document.getElementById('image_name');
  const file = input.files[0]; // Get the selected file

  if (file) {
    const reader = new FileReader();

    reader.onload = function(e) {
      // Set the src attribute of the <img> element to the data URL
      imgElement.src = e.target.result;
    };

    // Read the selected file as a data URL
    reader.readAsDataURL(file);
    $("#image_name")[0].classList.remove("d-none");
  } else {
    // If no file is selected, clear the <img> element
    imgElement.src = '';
  }
}




    

    // admission_list.draw();

   
   function refreshTableAfterAdmissionYearLoad(){


    //search functions
   

    $("#session").on('change', function(e) {
        var session = $("#session")[0].value;
       totalStaff();
        totalStudent(session);
       });
   
       
    $("#search_value").on('keyup', function(e) {
       
       if (e.key === 'Enter' || e.keyCode === 13) {
           admission_list.draw();
       }
    });

    var session = $("#session")[0].value;
    totalStaff();
        totalStudent(session);


}




function totalStaff(){

    $.ajax({
        url:"{{ url('total-staff') }}",
        type:"GET",
        success:function(data){
            $("#total_staff").text(data);
        }

    })

}




function totalStudent(session){

$.ajax({
    url:"{{ url('total-student') }}",
    type:"GET",
    data:{session:session},
    success:function(data){
        $("#total_student").text(data);
    }

})

}









</script>
@endsection

