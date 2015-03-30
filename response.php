<?php
/** Edit by Nijil **/

		 $status= $_REQUEST['Status'];
		 $cust_ref_no= $_REQUEST['ReferenceNo'];
		 $ref_no= $_REQUEST['TransactionId'];
		 $merchantId= $_REQUEST['MerchantId'];
		 $amount= $_REQUEST['Amount'];
		 $checkSum= $_REQUEST['CheckSum'];
		 
		$input= $merchantId."|".$amount."|".$cust_ref_no."|".$status;
	    $checksumBuild = hash('sha256', $input);
		if(preg_match('/IF[a-zA-Z0-9]+RM/', $cust_ref_no)) {	
				echo '<html>
					<body>
					<form id="pay" action="http://180.179.146.81/pay/index.php/response_iframe/processorResponseHandler" method="POST" >
					<input type="hidden" name="status" value="'.$status.'" />
						<input type="hidden" name="cust_ref_no" value="'.$cust_ref_no.'" />
						<input type="hidden" name="amount" value="'.$amount.'" />
						<input type="hidden" name="ref_no" value="'.$ref_no.'" />
					</form>
					<script>document.getElementById("pay").submit();</script>
					</body>
					</html>';	

				} else {
				
		echo '<html>
				<body>
				<form id="pay" action="http://180.179.146.81/pay/index.php/response/processorResponseHandler" method="POST" >
				<input type="hidden" name="status" value="'.$status.'" />
					<input type="hidden" name="cust_ref_no" value="'.$cust_ref_no.'" />
					<input type="hidden" name="amount" value="'.$amount.'" />
					<input type="hidden" name="ref_no" value="'.$ref_no.'" />
				</form>
				<script>document.getElementById("pay").submit();</script>
				</body>
				</html>';	
				}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Shmart!</title>

    <!-- Bootstrap core CSS -->
    <link href="https://pay.shmart.in/assets_todo/css/bootstrap.css" rel="stylesheet">

    <!-- Boostrap Theme -->
    <link href="https://pay.shmart.in/assets_todo/css/boostrap-overrides.css" rel="stylesheet">

    
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
	
    
</head>
<body  onload="initPieChart();">    
    <!-- preloader -->
     <!--   <div class='preloader-wrapper'>
            <div class="preloader">
                <img src="https://pay.shmart.in/assets_todo/images/preloader.png" alt='loading image'/>
            </div>
        </div><!-- / preloader --> 

        <!-- Container that wraps all content that gets "pushed" when chat panel shows-->
        <div id="container"> 

        <!-- Top Navbar  -->
        <div id="top-navbar" class="navbar navbar-static-top navbar-default add-shadow" role="navigation">
            <div class="navbar-header text-center">  				
                <!-- logo -->
                <div class="navbar-brand col-lg-12 col-md-12 col-sm-12 col-xs-7"><a href="#"> <img src="https://pay.shmart.in/assets_todo/images/logo.png" alt="logo" class="img-responsive"></a></div>
                <!-- / logo -->
            </div>
            
        </div><!-- / top Navbar -->	
        
		
        <!-- Wrapper for content below nav bar -->
        <div id="wrapper">
          <!-- Keep all page content within the page-content-wrapper -->
          <div class="col-lg-12">
                                        		
			<div class="col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 white-bg">
              <div class="box">
			  <div class="col-lg-12">
			  Redirecting back to merchant page.
			  </div>
			  <div class="col-lg-8 col-lg-offset-2 ">
			  <div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
					<span class="sr-only">100% Complete</span>
					</div>
				</div>
			</div>
			<span class="clearfix"></span>
			  </div>      
				
            </div>	
			
			
                                   
           </div><!-- /page-content-wrapper -->
		</div><!-- / wrapper for content below nav bar -->		

		</div>	<!-- / dd-->
	
		
	
   	        <!-- JavaScript -->
        <!-- Placed at the end of the document so the pages load faster -->
        <!-- Theme Core -->
		<script src="https://pay.shmart.in/assets_todo/js/jquery.min.js"></script>
    	
        <script src="https://pay.shmart.in/assets_todo/js/bootstrap.js"></script>   
        
	    <!-- Preloader -->
		<script>
            $(window).load(function(){
             $('.preloader-wrapper').fadeOut();
            });
        </script>  
              
</body>
</html> 
