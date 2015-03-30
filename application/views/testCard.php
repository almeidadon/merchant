<?php

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
	<head>
		<title>Merchant Site</title>
			<!-- define some style elements-->
			<style type="text/css">
				.centered {
				  position: fixed;
				  top: 50%;
				  left: 50%;
				  transform: translate(-50%, -50%);
				}
				.imgcentered {
				   position: fixed;
				  left: 50%;
				  transform: translate(-50%, -0%);
				} 
				label,a, body 
				{
					font-family : Arial, Helvetica, sans-serif;
					font-size : 12px; 
				}
				.err
				{
					font-family : Verdana, Helvetica, sans-serif;
					font-size : 12px;
					color: red;
				}
			</style>	
		<!-- a helper script for vaidating the form-->
		<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>	
		
	</head>

<body>
<?php
if(!empty($errors)){
echo "<p class='err'>".nl2br($errors)."</p>";
}
?>
<div id='contact_form_errorloc' class='err'></div>
<form method="POST" name="contact_form" action="http://www.zwitch.co/shmart/api/checkout/processTransaction/cards"> 

<p>
<label for='cnumber'>Card Number: </label><br>
<input type="text" name="cnumber" value='5211150000986742'class="zwitch_data" style="width:230px;">
</p>
<p>
<label for='cvv'>cvv: </label><br>
<input type="text" name="cvv" value='606' class="zwitch_cvv" style="width:230px;">
</p>
<p>
<label for='emonth'>Expiry month: </label><br>
<input type="text" name="expiry_month" value='02' style="width:230px;">
</p>
<p>
<label for='eyear'>Expiry year: </label><br>
<input type="text" name="expiry_year" value='2018' style="width:230px;">
</p>
<p>
<label for='ctype'>Card Type: </label><br>
<input type="text" name="cardType" value='DB' style="width:230px;">
</p>
<p>
<label for='cprovider'>Card provider: </label><br>
<input type="text" name="cardProvider" value='MC' style="width:230px;">
</p>
<p>
<label for='name'>Name on card: </label><br>
<input type="text" name="name_on_card" value='NIJIL VIJAYAN' style="width:230px;">
</p>
<p>
<label for='email'>email: </label><br>
<input type="text" name="email" value='ajeesh@gmail.com' style="width:230px;">
</p>
<p>
<label for='mobileno'>mobile no: </label><br>
<input type="text" name="mobileNo" value='9400429941' style="width:230px;">
</p>
<p>
<label for='refno'>ref No: </label><br>
<input type="text" name="shmart_refID" value='gafoorkp' style="width:230px;">
</p>


<input type="submit" value="PAY" name='submit' style="width:240px;" class ="zwitch_submit" >
</div>

</form>
 <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="https://www.zwitch.co/api/secure/zwitch.js"> </script>
</body>
</html>