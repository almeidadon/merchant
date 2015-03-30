<!-- <!DOCTYPE html >
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Transaction alert</title>
    <style type="text/css">
body {
	margin: 10px 0;
	padding: 0 10px;
	background: #ffffff;
	font-size: 12px;
}
table {
	border-collapse: collapse;
}
td {
	font-family: verdana
}

@media only screen and (max-width: 480px) {
body, table, td, p, a, li, blockquote {
	-webkit-text-size-adjust: none !important;
}
table {
	width: 100% !important;
}
.responsive-image img {
	height: auto !important;
	max-width: 100% !important;
	width: 100% !important;
}
.row_col2 {
	display: none
}
}
</style>
    </head>
    <body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" align="center" width="640" bgcolor="#FFFFFF">
            <tr>
            <td bgcolor="#ffe2c0" height="177" align="center" class="responsive-image"><img src="<?php echo base_url();?>assets/mailers/transaction_to_consumer/01.jpg" width="640" alt=""/></td>
          </tr>
            <tr>
            <td style="padding: 0px 21px" bgcolor="#ffe2c0"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                <td height="25" bgcolor="#f7941d" align="left"><span style="font-size: 14px; padding: 0px 5px;font-weight:bold;color:#ffffff">Dear <?php echo $name_on_card; ?>,</span></td>
                <td align="left" class="row_col2" width="50%"></td>
              </tr>
              </table></td>
          </tr>
            <tr>
			<p>
    Dear <?php echo $name_of_customer; ?>
</p>
<p>
    Thank you for signing up for <?php if(isset($brand_name)) echo $brand_name; else echo "Shmart"; ?> account. You can now continue paying through your mobile phone in a secure and convenient way.
</p>
<p>
    Your <?php if(isset($brand_name)) echo $brand_name; else echo "Shmart"; ?> account now make your online payments quicker, safer by enabling you to load money to your prepaid account or save card details for faster
    express checkout or lets you instantly receive refunds and gift vouchers or even enabling you to load funds to a Bank grade prepaid account.
</p>
<p>
    Your <?php if ( isset($brand_name) ) { echo $brand_name; } else { echo "Shmart"; }?> account details are as follows:
</p>
<p>
    Login :  <?php echo $username ; ?> </br>
	Password :  <?php echo $password ; ?>
</p>
<p>
    You can use your Shmart password or Shmart OTP (6 digit OTP that receives on your phone) to transact across any merchant that accepts Shmart.
</p>
<p>
    If you have any queries regarding this transaction or to know more about Shmart! Wallet, please write at wecare@shmart.in or call at 022 6370 4948. <a href="http://shmart.in/tc.html">Click here</a> for detailed Terms and Conditions.
</p>
<p>
   Enjoy the shift to Mobile!
</p>
<p>
    - Team <?php if ( isset($brand_name) ) { echo $brand_name; } else { echo "Shmart"; } ?>
</p>
          </tr>

            <tr>
            <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
          </tr>
            <tr>
            <td style="padding: 0px 26px 20px 26px;line-height:1.6;color:#1e1e1e" bgcolor="#ffffff"><div>
            <p>Kindly note the transaction will be marked as Transerv Pvt Ltd. in your bank/card <br />
              statement.</p>
            <p>If you have any queries regarding this transaction, please write to us - <br />
              @ <a href="mailto:support@shmart.in" target="_blank" style="color:#243ca9">wecare@shmart.in</a></p>
          </div></td>
          </tr>
            <tr>
            <td style="font-size: 0; line-height: 0;" height="30">&nbsp;</td>
          </tr>
            <tr>
          <td bgcolor="#020d62" height="29" style="padding:10px"><table border="0" cellpadding="0" cellspacing="0" align="left" width="26%">
              <tr>
                <td height="29" width="20%" align="right"><img src="<?php echo base_url();?>assets/mailers/transaction_to_consumer/f01.png" width="29" alt=""/></td>
                <td  height="29" width="80%" align="left" valign="middle"><div style="padding:0 0 0 10px"><a href="http://www.shmart.in/terms.html" target="_blank" style="font-size:11px;text-decoration:none;color:#ffffff">Terms &amp; Conditions</a></div></td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" align="left" width="40%">
              <tr>
                <td height="29" width="20%" align="right"><img src="<?php echo base_url();?>assets/mailers/transaction_to_consumer/f02.png" width="29" alt=""/></td>
                <td  height="29" width="80%" align="left" valign="middle"><div style="padding:0 0 0 10px"><a href="mailto:wecare@shmart.in" target="_blank" style="font-size:11px;text-decoration:none;color:#ffffff">E-mail: wecare@shmart.in</a></div></td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" align="right" width="33%">
              <tr>
                <td height="29" width="20%" align="right"><img src="<?php echo base_url();?>assets/mailers/transaction_to_consumer/f03.png" width="29" alt=""/></td>
                <td  height="29" width="80%" align="left" valign="middle"><div style="padding:0 0 0 10px"><a href="http://shmart.in/" target="_blank" style="font-size:11px;text-decoration:none;color:#ffffff">Website: shmart.in</a></div></td>
              </tr>
            </table
								></td>
        </tr>
          </table></td>
      </tr>
    </table>
</body>
</html> -->

<html>
<head>
</head>

<body>

Dear <?php echo $name_of_customer; ?>,
<br />
<p>Thank You for using Shmart! We offer you a secure and convenient way of making online payments. Your payment receipt is attached below. </p> </br>


<table>
  <tr>
    <td>Total amount</td>
    <td>Rs <?php echo $total_amount; ?></td>
  </tr>
  <tr>
    <td>Merchant Name</td>
    <td><?php echo $merchant_website; ?></td>
  </tr>
  <tr>
    <td>Transaction reference ID</td>
    <td><?php echo $merchant_refID; ?> </td>
  </tr>
  <tr>
    <td>Payment Mode</td>
    <td><?php if($transaction_mode == 'PG') { echo 'Payment Gateway' ;} else if($transaction_mode == 'W') {echo 'Wallet' ;} else { echo 'Wallet + Payment Gateway' ; } ?> </td>
  </tr>
</table>

<br />
<br />


<div>
    Note: This is an electronically generated receipt and does not require a signature.
</div>
<br />
<div>
    If you have not initiated this transaction or have any queries please contact us at 022 – 6730 4948 or WeCare@Shmart.in
</div>
<br />
<div>
    You can now avail of all Shmart! services by using your Shmart! Wallet. It lets you send money to your friends or family members instantly. Just type
    mobile number of the person you wish to send money to and click. You can pay from a credit / debit card or netbanking account.
</div>
<br />
<div>
    What's more, it will let you save credit / debit cards, so next time you pay faster in 2 steps without the need to remember card number and expiry date.
</div>
<div>
    Just use following details to login on shmart.in and access your Shmart! Wallet
</div>
<br />
<div>
    Mobile Number : <?php echo $username ; ?> 
</div>
<div>
    Password :   <?php echo $password ; ?>
</div>
<div>
    <br/>
</div>
<div>
    Shmart! Care Team
</div>
<div>
    022-67304948
</div>
<div>
    WeCare@Shmart.in
</div>




</body>



</html>
