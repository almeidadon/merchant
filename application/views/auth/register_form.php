<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
		'class' => 'form-control',
		'placeholder' => 'Mobile Number'
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'class' => 'form-control',
	'placeholder' => 'Email'
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
	'class' => 'form-control',
	'placeholder' => 'Password',
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
	'class' => 'form-control',
	'placeholder' => 'Confirm Password',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
	
);
$submit_button = array(
	'class'	=> 'class="btn btn-secondary btn-block btn-lg"',	
);
$form_label = array(
	'class'	=> 'placeholder-hidden',	
);


?>

<body class="account-bg">




<div class="account-wrapper">



  <div class="account-logo">
    <img src="<?php echo base_url();?>assets/img/logo-login.png" alt="Target Admin">
  </div>


    <div class="account-body">

      <h3 class="account-body-title">Sign Up for Shmart !</h3>

      <h5 class="account-body-subtitle">Sign up in 30 seconds. No credit card required.</h5>

<?php echo form_open($this->uri->uri_string(),array('class' => 'form account-form')); ?>
	<?php if ($use_username) { ?>
		<?php echo form_label('Username', $username['id'], $form_label); ?>
		<?php echo form_input($username); ?>
		<?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?>
	<?php } ?>
		<?php echo form_label('Email Address', $email['id'], $form_label); ?>
		<?php echo form_input($email); ?>
		<?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?>
		<?php echo form_label('Password', $password['id'], $form_label); ?>
		<?php echo form_password($password); ?>
		<?php echo form_error($password['name']); ?>
		<?php //echo form_label('Confirm Password', $confirm_password['id'], $form_label); ?>
		<?php //echo form_password($confirm_password); ?>
		<?php echo form_error($confirm_password['name']); ?>
	<?php if ($captcha_registration) {
		if ($use_recaptcha) { ?>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p>Enter the code exactly as it appears:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirmation Code', $captcha['id'], $form_label); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>
</table>
<?php echo form_submit('register', 'Register', $submit_button['class']); ?>
<?php echo form_close(); ?>