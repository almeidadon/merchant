<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'class' => 'form-control'
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'class' => 'form-control'
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
	'class' => 'form-control'
);
$submit_button = array(
	'class'	=> 'class="btn btn-primary btn-block btn-lg"',	
);
$form_label = array(
	'class'	=> 'placeholder-hidden',	
);
?>

<body class="account-bg">

<hr class="account-header-divider">

<div class="account-wrapper">
  <div class="account-logo">
    <img src="<?php echo base_url();?>assets/img/logo-login.png" alt="Target Admin">
  </div>

    <div class="account-body">

      <h3 class="account-body-title">Welcome back to Shmart !</h3>

      <h5 class="account-body-subtitle">Please sign in to get access.</h5>

<?php echo form_open($this->uri->uri_string(), array('class' => 'form account-form')); ?>
		<?php echo form_label($login_label, $login['id'], $form_label); ?>
		<?php echo form_input($login); ?>
		<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
		<?php echo form_label('Password', $password['id'], $form_label); ?>
		<?php echo form_password($password); ?>
		<?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?>

	<?php if ($show_captcha) {
		if ($use_recaptcha) { ?>
			<div id="recaptcha_image"></div>
			<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
		<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
		<?php echo form_error('recaptcha_response_field'); ?>
		<?php echo $recaptcha_html; ?>
	<?php } else { ?>
			<p>Enter the code exactly as it appears:</p>
			<?php echo $captcha_html; ?>
		<?php echo form_label('Confirmation Code', $captcha['id'], $form_label); ?>
		<?php echo form_input($captcha); ?>
		<?php echo form_error($captcha['name']); ?>
	<?php }
	} ?>
			<div class="form-group clearfix">
          <div class="pull-left"> 
			
            <?php echo form_checkbox($remember); ?>
			<?php echo form_label('Remember me', $remember['id']); ?>
          </div>
          <div class="pull-right">
			<?php echo anchor('/auth/forgot_password/', 'Forgot password ?'); ?>
          </div>
        </div> <!-- /.form-group -->		
	
<?php echo form_submit('submit', 'Sign In', $submit_button['class']); ?>
<?php echo form_close(); ?>

 </div> <!-- /.account-body -->

    <div class="account-footer">
      <p>
      Don't have an account? &nbsp;
		<?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Register'); ?>
      </p>
    </div> <!-- /.account-footer -->

  </div> <!-- /.account-wrapper -->

