</div>
</div>
<!-- End of Main Content -->

<!-- Footer -->

<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->


<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>













<div class="modal fade payNowModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title payNowTitle" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close paynow-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body payNowModalBody">

            </div>

        </div>
    </div>
</div>





<div class="modal fade forListModalView" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title forListModalTitle" id="exampleModalLongTitle forListModalTitle">Modal title</h5>
                <button type="button" class="close close-global btn btn-secondary" id="close-list-view"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body forListModalBody">

            </div>
        </div>
    </div>
</div>







<div class="modal fade newForListModalView" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title newForListModalTitle" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close close-global btn btn-secondary" id="close-list-view"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body newForListModalBody">

            </div>
        </div>
    </div>
</div>



<div class="modal fade viewModal" id="exampleModalCenter" tabindex="-2" role="dialog" 
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title viewTitle" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close close-global btn btn-secondary" id="close-view" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body viewModalBody">

            </div>
        </div>
    </div>
</div>


{{-- extra large modal --}}
<div class="modal fade largeModal"  tabindex="-2" role="dialog" 
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content" style="width:1500px;">
            <div class="modal-header">
                <h5 class="modal-title largeModalTitle" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close close-global btn btn-secondary" id="close-view" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body largeModalBody">

            </div>
        </div>
    </div>
</div>




{{-- from role and permissin footer --}}

<!-- Bootstrap core JavaScript-->
<script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Core plugin JavaScript-->
<script src="{{ url('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<!-- Custom scripts for all pages-->
<script src="{{ url('js/sb-admin-2.min.js') }}"></script>

<script src="{{ url('vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ url('js/demo/chart-area-demo.js') }}"></script>



<script src="{{ url('vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- end --}}

<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>


{{-- <div id="wait" style="display:none;position:absolute;top:5%;left:50%;padding:2px; color:#4e73df; font-size:20px; font-weight:bolder;padding:8px; background:#4e73df;color:white;border-radius:5px;">Please Wait! PDF is creating......</div> --}}

</html>


<script>

function successAlert(){

    toastr.options = {
                            closeButton: true,
                            newestOnTop: false,
                            progressBar: true,
                            positionClass: 'toast-top-left',
                            // preventDuplicates: true,
                            showDuration: 300,
                            hideDuration: 1000,
                            timeOut: 1000,
                            extendedTimeOut: 1000,
                            showEasing: 'swing',
                            hideEasing: 'linear',
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut',
                            // Customize font size and color
                            "progressBar": true,
                            "positionClass": "toast-top-left",
                            "fontSize": "30px",
                            "fontColor": "#FFFFFF",
                        };
                        // Example notification
                        toastr.success('اندراج ہو گیا ہے!');       
}



function newCustomSuccessAlert(data){
    toastr.options = {
                            closeButton: true,
                            newestOnTop: false,
                            progressBar: true,
                            positionClass: 'toast-top-left',
                            // preventDuplicates: true,
                            showDuration: 500,
                            hideDuration: 2000,
                            timeOut: 2000,
                            extendedTimeOut: 2000,
                            showEasing: 'swing',
                            hideEasing: 'linear',
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut',
                            // Customize font size and color
                            "progressBar": true,
                            "positionClass": "toast-top-left",
                            "fontSize": "30px",
                            "fontColor": "#FFFFFF",
                        };
                        // Example notification
                        toastr.error(data);       
}


function errorAlert(){

toastr.options = {
                        closeButton: true,
                        newestOnTop: false,
                        // progressBar: true,
                        positionClass: 'toast-top-left',
                        // preventDuplicates: true,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 1000,
                        extendedTimeOut: 1000,
                        showEasing: 'swing',
                        hideEasing: 'linear',
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut',
                        // Customize font size and color
                        // "progressBar": true,
                        "positionClass": "toast-top-left",
                        "fontSize": "30px",
                        "fontColor": "#FFFFFF",
                        
                    };
                    // Example notification
                    toastr.error('اندراج پہلے سے ہو گیا ہے!');       
}




    function viewModal(url) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('.viewModal').modal('show');
                    $('.viewTitle').html(data["title"]);
                    $('.viewModalBody').html(data["view"]);

                }
            })
        }
    }


    function payNowModalBody(url) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('.payNowModal').modal('show');
                    $('.payNowTitle').html(data["title"]);
                    $('.payNowModalBody').html(data["view"]);
                }
            })
        }
    }



    function extraLargeModal(url) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('.largeModal').modal('show');
                    $('.largeModalTitle').html(data["title"]);
                    $('.largeModalBody').html(data["view"]);
                }
            })
        }
    }


    function forListModalView(url) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('.forListModalView').modal('show');
                    $('.forListTitle').html(data["title"]);
                    $('.forListModalBody').html(data["view"]);
                }
            })
        }
    }


    function newForListModalView(url, data) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                data:{data},
                success: function(data) {
                    $('.newForListModalView').modal('show');
                    $('.newForListModalTitle').html(data["title"]);
                    $('.newForListModalBody').html(data["view"]);
                }
            })
        }
    }



    // $(document).on("click", "#view_rate_list", function() {

    // var url = "{{ url('view-rate-list-button') }}";

    // payNowModalBody(url);

    // })




    $("#change_password").click(function() {


        var url = "{{ url('change-password') }}";

        payNowModalBody(url);

    });










    //fetch amount of easy paisa

    function getEasypaisaClosing() {

        return false;

        // $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: "{{ url('easypaisa-last-closing-amount') }}",
        //             type: "GET",
        //             success: function(data) {
        //                 // console.log(data);
        //                 $(".easypaisa_amount_last")[0].innerText = data;
        //             }
        //         })

    }


    getEasypaisaClosing();

    function getHBLClosing() {

        return false;

        // $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: "{{ url('hbl-last-closing-amount') }}",
        //             type: "GET",
        //             success: function(data) {
        //                 // console.log(data);
        //                 $(".hbl-last-closing-amount")[0].innerText = data;
        //             }
        //         })

    }
    getHBLClosing();



    // $(".main-nav div a").click(function(e) {

    //     if(e.target.id !== "grand_list"){
    //         e.preventDefault();
    //         var href = $(this).attr("href");
    //         $(".container-fluid").load(href);
    //     }

    // });


    // $(document).ready(function() {
    //     $('.menu-link').click(function() {
    //       // Remove active class from all links
    //       $('.menu-link').removeClass('active');

    //       // Add active class to the clicked link
    //       $(this).addClass('active');
    //     });
    //   });



    $(document).ajaxStart(function() {
        NProgress.start();
    });
    $(document).ajaxComplete(function() {
        NProgress.done();
    });
    //   $("button").click(function(){
    //     $("#txt").load("demo_ajax_load.asp");
    //   });




    $.ajax({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{ url('get-admission-year') }}",
        type: "GET",
        success: function(data) {

            var session_parent = $("#session")[0];
            $(data).each(function(index, element) {
                var create_option = document.createElement("option");
                create_option.value = element["id"];
                create_option.innerText = element["from_year"] + "-" + element["to_year"];
                if (element["status"] == "On") {
                    create_option.selected = true;
                }
                session_parent.appendChild(create_option);

            });
            refreshTableAfterAdmissionYearLoad();

        }
    })



    $("#create_paper").click(function() {

        var url = "{{ url('create-paper') }}";
        viewModal(url);

    })

    $("#print_results").click(function() {

        var url = "{{ url('view-result-sheets') }}";
        payNowModalBody(url);

    })

    $("#view-not-recieved-voucher-list").click(function() {

        var url = "{{ url('view-not-recieve-voucher-list') }}";
        payNowModalBody(url);

    })


    $(document).on("click", "#check_amount", function() {

        var url = "{{ url('check-balance') }}";
        payNowModalBody(url);

    })

    // $(document).on("click", "#multiple-fee-voucher-print", function() {

    //     var url = "{{ url('view-print-voucher-multiple') }}";
    //     payNowModalBody(url);

    // })

    // $("#view-diary").on("click", function(){

    //     var url = "{{ url('view-diary') }}";
    //     payNowModalBody(url);
       

    // })

    // $(document).ready(function() {
    //     $('.payNowModal').on('shown.bs.modal', function () {
    //         // Initialize Select2 inside the modal
    //         $('.find-student').select2();
    //     });
    // });


</script>

@yield('script')
