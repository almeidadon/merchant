<script src="<?php echo base_url();?>/assets_todo/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url();?>/assets_todo/js/bootstrap.js"></script>
<!-- App -->
<script src="<?php echo base_url();?>/assets_todo/js/app.js"></script>
<script src="<?php echo base_url();?>/assets_todo/js/app.plugin.js"></script>
<script src="<?php echo base_url();?>/assets_todo/js/app.data.js"></script>
<!-- Fuelux -->
<script src="<?php echo base_url();?>/assets_todo/js/fuelux/fuelux.js"></script>
<script src="<?php echo base_url();?>assets_todo/js/iosOverlay.js"></script>
<script src="<?php echo base_url();?>assets_todo/js/spin.min.js"></script>
<script>
    $('#generate_otp').click(function(){
        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>checkout/ajaxUpdate/generateOtp",
            cache: false,
            data: {mobileNo : <?php echo $mobileNo; ?>},
            success: function(data){
                // alert('success');
                $("#user_otp").prop('disabled', false);
                $("#otp_field").fadeIn();
            },
            error: function(td){
                alert('Something went wrong!Please try again');
            }
        });

    });
    $('#validate_otp').click(function(){
        event.preventDefault();
        var opts = {
            lines: 13, // The number of lines to draw
            length: 11, // The length of each line
            width: 5, // The line thickness
            radius: 17, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var target = document.createElement("div");
        document.body.appendChild(target);
        var spinner = new Spinner(opts).spin(target);

        /*Loader starts showing here*/
        var overlay = iosOverlay({
            text: "Logging you in!",
            spinner: spinner
        });
        var user_otp = $('#user_otp').val();
        var shmart_password =  $('#shmart_password').val();
        var url;
        var credentials;
        if(user_otp != '')
        {
            url = '<?php echo base_url();?>checkout/ajaxUpdate/loginUsingOtp';
            credentials =  {mobileNo : <?php echo $mobileNo; ?> , otp : user_otp}
        }
        else if (shmart_password != '')
        {
            url = '<?php echo base_url();?>checkout/ajaxUpdate/loginUsingPassword';
            credentials =  {mobileNo : <?php echo $mobileNo; ?> , password : shmart_password}
        }
        else
        {
            $('#pass_error').text("Please enter password or OTP");
            overlay.hide(); /* Once ajax call to the backend is success ajax loader is faded */
            return false;
        }
        $.ajax({
            type: "post",
            url: url,
            cache: false,
            data: credentials,
            success: function(data){
                if(data == 1)
                {
                    overlay.update({
                        text: "Logged In!"
                    });
                    $("#login-form").submit();
                    overlay.hide(); /* Once ajax call to the backend is success ajax loader is faded */
                }
                else
                {
//                    overlay.update({
//                        text: "Invalid Password",
//                    });
                    $('#pass_error').text("Invalid Password or OTP");
                    overlay.hide();
                }
            },
            error: function(td){
                alert('Something went wrong!Please try again');
            }
        });

    });
</script>
</body>
</html>