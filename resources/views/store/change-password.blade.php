<form id="change-password-form">

    <div class="form-group">
        <label>User Name</label>
        <input type="text" name="user_name" readonly class="form-control" value="{{ Auth::User()->name }}">
    </div>

    <div class="form-group">
        <label>Old Password</label>
        <input type="password" name="old_password" id="old_password"  class="form-control">
    </div>

    <div class="form-group">
        <label>New Password</label>
        <input type="password" name="new_password" id="new_password"  class="form-control">
    </div>

    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password"  class="form-control">
    </div>

    <div class="form-group d-flex justify-content-end">
        <input type="submit" value="Update" id="submit_btn" class="btn btn-primary">
    </div>

</form>


<script>
     $('#change-password-form').validate({
            // errorPlacement: function(error, element) {
            //     // element[0].style.border = "1px solid red";
            // },
            rules: {
                user_name: "required",
                old_password: "required",
                new_password: {
                    required: true,
                    minlength: 5
                },
                confirm_password:{
                    required: true,
                    minlength: 5,
                    equalTo: "#new_password"
                }
            },
            messages:{
                confirm_password:{
                    
                    equalTo:"Password not match"
                }
            },

            submitHandler: function(form) {

                $("#submit_btn")[0].disabled = true;
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-password') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        // console.log(data);

                        $("form input[type='text'] , form input[type='number']").val("");

                        // $('#add-stock-form')[0].reset();
                        $("#submit_btn")[0].disabled = false;
                        get_stock_list.draw();
                        $("#stock_hidden_id").val("");
                    },
                    error: function(data) {


                    }

                })
            }
        });
</script>