<?php

if(isset($_POST['submit']))
{
    $amount = $_POST['amount'];
    $currency_code = 'INR';
    $merchant_refID = '123456';
    $shmart_refID =  $this->paymentgateway->generateRefID() ;
    $merchant_id = 'SHOPCLUES';
    $apikey = '281f4a32bc92e00d49add4f73a7ba5be';
    $secretkey ='ccc9d34fd6925728a0d3d8e8379f3944';
    $checksum_method = 'MD5';
    $ip_address = '10.10.10.1';
    $email = $_POST['email'];
    $mobileNo = $_POST['mobileNo'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $addr = $_POST['addr'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $country = $_POST['country'];
    $shipping_city = $_POST['shipping_city'];
    $shipping_state = $_POST['shipping_state'];
    $shipping_country = $_POST['shipping_country'];
    $shipping_zipcode = $_POST['shipping_zipcode'];
    $shipping_mobileNo = $_POST['shipping_mobileNo'];
    $shipping_addr = $_POST['shipping_addr'];
    $udf1 = '1abc';
    $udf2 = '2abc';
    $udf3 = '3abc';
    $udf4 = '4abc';
    $authorize_user = 0;
    $show_shipping_addr = $_POST['show_shipping_addr'];
    //$show_addr = '1';
    $product_name = 'milk bottle';
    $product_description = 'used to drink milk';
    //print_r($_POST);
    $data['input_string'] = $merchant_id.'|'.$apikey.'|'.$ip_address.'|'.$merchant_refID.'|'.$currency_code.'|'.$amount.'|'.$checksum_method.'|'.$authorize_user ;
    //echo "input string=".$data['input_string'];

    $checksum = md5($secretkey.$data['input_string']);
    //echo "</br> checksum is=".$checksum;

    echo '<html>
			   <body>
			   <form id="pay" action="http://zwitch.co/shmart/api/checkout/v1/transactions" method="POST" >
			   <input type="hidden" name="currency_code" value="'.$currency_code.'" />
			   <input type="hidden" name="apikey" value="'.$apikey.'" />
			   <input type="hidden" name="amount" value="'.$amount.'" />
			   <input type="hidden" name="merchant_refID" value="'.$merchant_refID.'" />
			   <input type="hidden" name="merchant_id" value="'.$merchant_id.'" />
			   <input type="hidden" name="checksum_method" value="'.$checksum_method.'" />
			   <input type="hidden" name="ip_address" value="'.$ip_address.'" />
			   <input type="hidden" name="email" value="'.$email.'" />
			   <input type="hidden" name="mobileNo" value="'.$mobileNo.'" />
			   <input type="hidden" name="f_name" value="'.$f_name.'" />
			   <input type="hidden" name="l_name" value="'.$l_name.'" />
			   <input type="hidden" name="addr" value="'.$addr.'" />
			   <input type="hidden" name="city" value="'.$city.'" />
			   <input type="hidden" name="state" value="'.$state.'" />
			   <input type="hidden" name="zipcode" value="'.$zipcode.'" />
			   <input type="hidden" name="country" value="'.$country.'" />
			   <input type="hidden" name="show_shipping_addr" value="'.$show_shipping_addr.'" />
			   <input type="hidden" name="shipping_addr" value="'.$shipping_addr.'" />
			   <input type="hidden" name="shipping_city" value="'.$shipping_city.'" />
			   <input type="hidden" name="shipping_state" value="'.$shipping_state.'" />
			   <input type="hidden" name="shipping_country" value="'.$shipping_country.'" />
			   <input type="hidden" name="shipping_zipcode" value="'.$shipping_zipcode.'" />
			   <input type="hidden" name="shipping_mobileNo" value="'.$shipping_mobileNo.'" />
			   <input type="hidden" name="udf1" value="'.$udf1.'" />
			   <input type="hidden" name="udf2" value="'.$udf2.'" />
			   <input type="hidden" name="udf3" value="'.$udf3.'" />
			   <input type="hidden" name="udf4" value="'.$udf4.'" />
			   <input type="hidden" name="product_name" value="'.$product_name.'" />
			   <input type="hidden" name="product_description" value="'.$product_description.'" />
			   <input type="hidden" name="authorize_user" value="'.$authorize_user.'" />
			   <input type="hidden" name="checksum" value="'.$checksum.'" />
			   </form>
			   <script>document.getElementById("pay").submit();</script>
			   </body>
			   </html>';

}
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
<form method="POST" name="contact_form" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">

    <p>
        <label for='name'>First Name: </label><br>
        <input type="text" name="f_name" value='Ajeesh' style="width:230px;">
    </p>
    <p>
        <label for='name'>Last Name: </label><br>
        <input type="text" name="l_name" value='Achuthan' style="width:230px;">
    </p>
    <p>
        <label for='email'>Email: </label><br>
        <input type="text" name="email" value='ajeesh@gmail.com' style="width:230px;">
    </p>
    <p>
        <label for='mobile'>Mobile: </label><br>
        <input type="text" name="mobileNo" value='' style="width:230px;">
    </p>
    <p>
        <label for='addr'>Address: </label><br>
        <input type="text" name="addr" value='shanti nagar' style="width:230px;">
    </p>
    <p>
        <label for='city'>City: </label><br>
        <input type="text" name="city" value='pmna' style="width:230px;">
    </p>
    <p>
        <label for='state'>State: </label><br>
        <input type="text" name="state" value='kerala' style="width:230px;">
    </p>
    <p>
        <label for='country'>Country: </label><br>
        <input type="text" name="country" value='india' style="width:230px;">
    </p>
    <p>
        <label for='zipcode'>Zipcode: </label><br>
        <input type="text" name="zipcode" value='691665' style="width:230px;">
    </p>

    <p>
        <Input type = 'checkbox' Name ='show_shipping_addr' id ='ch1' value ='1' checked="checked" onclick="toggle('show')";> Show shipping address
    </p>

    <script>
        function toggle(value){
            if(value=='show')
                document.getElementById('date').style.visibility='visible';
            else
                document.getElementById('date').style.visibility='hidden';
        }
    </script>

    <ul id = 'date' style='hidden'>
        <p>
            <label for='shipping_mobileNo'>Shipping Mobile Number: </label><br>
            <input type="text" name="shipping_mobileNo" value='9995346716' id= 'ch2' style="width:230px;">
        </p>
        <p>
            <label for='shipping_addr'>Shipping Address: </label><br>
            <input type="text" name="shipping_addr" value='shanti nagar' style="width:230px;">
        </p>
        <p>
            <label for='shipping_city'>Shipping City: </label><br>
            <input type="text" name="shipping_city" value='pmna' style="width:230px;">
        </p>
        <p>
            <label for='shipping_state'>Shipping State: </label><br>
            <input type="text" name="shipping_state" value='kerala' style="width:230px;">
        </p>
        <p>
            <label for='shipping_country'>Shipping Country: </label><br>
            <input type="text" name="shipping_country" value='india' style="width:230px;">
        </p>
        <p>
            <label for='shipping_zipcode'>Shipping Zipcode: </label><br>
            <input type="text" name="shipping_zipcode" value='691665' style="width:230px;">
        </p>
    </ul>
    <div class = "centered">
        <p>
            <label for='amount'><b><h1>Amount</h1></b> </label><br>
            <input type="text" name="amount" value='' style="width:230px;">
        </p>
        <input type="submit" value="PAY" name='submit' style="width:240px;" >
    </div>

</form>

</body>
</html>