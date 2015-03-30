<?php
$otp_field = array(
    'name'          => 'wallet_amount_field',
    'type'       => 'tel',
    'id'        =>'user_otp',
    'name'        =>'user_otp',
    'class'        =>'form-control',
    'disabled'        =>'disabled',
);
$shmart_password_field = array(
    'type'       => 'password',
    'id'        =>'shmart_password',
    'name'        =>'shmart_password',
    'class'        =>'form-control',
);
$email_field = array(
    'name'          => 'email_field',
    'type'       => 'hidden',
    'id'        =>'email',
    'name'        =>'email',
    'value'     =>$email,
);
$mobileNo_field = array(
    'name'          => 'mobileNo_field',
    'type'       => 'hidden',
    'id'        =>'mobileNo',
    'name'        =>'mobileNo',
    'value'     =>$mobileNo,
);
$shmart_refID_field = array(
    'name'          => 'shmart_refID_field',
    'type'       => 'hidden',
    'id'        =>'shmart_refID',
    'name'        =>'shmart_refID',
    'value'    => $shmart_refID,
);

?>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <a class="nav-brand" href="index.html">todo</a>
    <div class="row m-n">
        <div class="col-md-4 col-md-offset-4 m-t-lg">
            <section class="panel">
                <header class="panel-heading text-center">
                    Sign in using OTP or Shmart password to Pay
                </header>
                    <?php echo form_open('checkout/v1/login',array('id'=>'login-form','class'=>'panel-body','autocomplete'=>'off')); ?>
                    <p class="text-muted text-center"><small>Click here to send an OTP to 9400429941</small></p>

                    <a href="#" class="btn btn-facebook btn-block m-b-sm" id="generate_otp"><i class="icon-envelope pull-left"></i>Generate OTP</a>
                    <div class="form-group" style="display:none" id="otp_field">
                        <label for="user_otp" class="control-label">Enter OTP to sign in</label>
                        <?php echo form_input($otp_field) ; ?>
                    </div>
                    <p class="text-muted text-center"><strong>OR</strong></p>

                    <!--                    <div class="form-group">-->
<!--                        <label class="control-label">Email / Mobile Number</label>-->
<!--                        <input type="email" placeholder="test@example.com" class="form-control">-->
<!--                    </div>-->

                    <div class="form-group">
                        <label for="shmart_password" class="control-label">Shmart Password</label>
                        <?php echo form_input($shmart_password_field) ; ?>
                    </div>
                    <p id="pass_error" class="text-danger text-center"><small></small></p>
                    <!--                    <div class="checkbox">-->
<!--                        <label>-->
<!--                            <input type="checkbox"> Keep me logged in-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <a href="#" class="pull-right m-t-xs"><small>Forgot password?</small></a>-->
                    <div class="line line-dashed"></div>
                    <?php echo form_input($mobileNo_field) ; ?>
                    <?php echo form_input($email_field) ; ?>
                    <?php echo form_input($shmart_refID_field) ; ?>
                    <button type="submit" id="validate_otp" class="btn btn-info btn-block">Sign in</button>
<!--                    <div class="line line-dashed"></div>-->
<!--                    <p class="text-muted text-center"><small>Do not have an account?</small></p>-->
<!--                    <a href="signup.html" class="btn btn-white btn-block">Create an account</a>-->
                <?php echo form_close(); ?>
            </section>
        </div>
    </div>
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder clearfix">
        <p>
            <small>Mobile first web app framework base on Bootstrap<br>&copy; 2013</small>
        </p>
    </div>
</footer>
