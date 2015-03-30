<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Shmart!</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
  <link rel="stylesheet" href="<?php echo base_url();?>assets_todo/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets_todo/css/animate.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets_todo/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets_todo/css/font.css" cache="false">
  <link rel="stylesheet" href="<?php echo base_url();?>assets_todo/css/plugin.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets_todo/css/app.css">
  <!--[if lt IE 9]>
    <script src="js/ie/respond.min.js" cache="false"></script>
    <script src="js/ie/html5.js" cache="false"></script>
    <script src="js/ie/fix.js" cache="false"></script>
  <![endif]-->
</head>
<body>
  <section id="content">
    <div class="row m-n">
      <div class="col-sm-4 col-sm-offset-4">
        <div class="text-center m-b-lg">
          <h2 class="h text-white animated bounceInDown">Error!</h2>
        </div>
		<div class="list-group m-b-sm bg-white m-b-lg">
		 <li class="list-group-item">
            <i class="icon-chevron-down"></i>
            <i class="icon-frown"></i> Why I'm seeing this ?
          </li>
          <li class="list-group-item">
            <i class="icon-reply"></i> You clicked the back button.
          </li>
          <li class="list-group-item">
            <i class="icon-remove"></i> Your session expired.
          </li>
        </div>
        <div class="list-group m-b-sm bg-white m-b-lg">
          <li href="index.html" class="list-group-item">
            <i class="icon-hand-down"></i>
             What you can do.
          </li>
          <li  class="list-group-item">
            <i class="icon-remove"></i> Clear your browser cache.
          </li>
          <li class="list-group-item">
            <i class="icon-repeat"></i> Retry the transaction from begining
          </li>
		  <li class="list-group-item">
            <span class="badge">mail us : wecare@shmart.in</span>
            <i class="icon-envelope"></i> Still facing issue?
          </li>
        </div>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small>Shmart!<br>&copy; 2014</small>
      </p>
    </div>
  </footer>
  <!-- / footer -->
	<script src="<?php echo base_url();?>assets_todo/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?php echo base_url();?>assets_todo/js/bootstrap.js"></script>
  <!-- app -->
  <script src="<?php echo base_url();?>assets_todo/js/app.js"></script>
  <script src="<?php echo base_url();?>assets_todo/js/app.plugin.js"></script>
  <script src="<?php echo base_url();?>assets_todo/js/app.data.js"></script>
</body>
</html>