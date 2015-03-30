<!DOCTYPE html >
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Transaction report</title>
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
            <td bgcolor="#ffe2c0" height="177" align="center" class="responsive-image"><img src="<?php echo base_url();?>assets/mailers/transaction_to_merchant/01.jpg" width="640" alt=""/></td>
          </tr>
            <tr>
            <td style="padding: 0px 21px" bgcolor="#ffe2c0"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                <td height="25" bgcolor="#f7941d" align="left"><span style="font-size: 14px; padding: 0px 5px;font-weight:bold;color:#ffffff">Dear Merchant,</span></td>
                <td align="left" class="row_col2" width="50%"></td>
              </tr>
              </table></td>
          </tr>
            <tr>
            <td style="padding: 20px 26px 20px 26px;line-height:1.6;color:#ffffff" bgcolor="#020d62" ><div>Your customer ,<?php echo $name_on_card; ?> has made a transaction for Rs  <?php echo $total_amount; ?> </div></td>
          </tr>
            <tr>
            <td style="font-size: 0; line-height: 0;" height="30">&nbsp;</td>
          </tr>
            <tr>
            <td style="padding: 20px 26px 20px 26px;line-height:1.6;color:#1f1f1e" bgcolor="#ffffff"><div style="font-size:14px;font-weight:bold">The details of transaction are as follows : </div></td>
          </tr>
            <tr>
            <td style="font-size: 0; line-height: 0;" height="5">&nbsp;</td>
          </tr>
          
            <tr>
            <td style="padding: 0px 26px 20px 26px;line-height:1.6;color:#1e1e1e" bgcolor="#ffffff">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Merchant RefID</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><?php echo $merchant_refID; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Shmart RefID</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><?php echo $shmart_refID; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">App Used</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><?php echo $app_used; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Transaction Status</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><?php echo $status_msg; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Amount</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"> <?php echo $total_amount; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Transaction Mode</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"> <?php echo $transaction_mode; ?> </td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Payment Mode </td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><?php echo $cardType; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Payment Provider </td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"> <?php echo $cardProvider; ?></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Customer Email</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><a href="mailto:<?php echo $email; ?>" style="color:#354da7"><?php echo $email; ?></a></td>
                  </tr>
                  <tr>
                    <td width="35%" height="30" align="left" valign="middle">Customer MobileNo</td>
                    <td width="3%" height="30" align="left" valign="middle">:</td>
                    <td width="62%" height="30" align="left" valign="middle"><?php echo $mobileNo; ?></td>
                  </tr>
                </table>

            </td>
          </tr>
            
           
            <tr>
          <td bgcolor="#020d62" height="29" style="padding:10px"><table border="0" cellpadding="0" cellspacing="0" align="left" width="26%">
              <tr>
                <td height="29" width="20%" align="right"><img src="<?php echo base_url();?>assets/mailers/transaction_to_merchant/f01.png" width="29" alt=""/></td>
                <td  height="29" width="80%" align="left" valign="middle"><div style="padding:0 0 0 10px"><a href="http://www.shmart.in/terms.html" target="_blank" style="font-size:11px;text-decoration:none;color:#ffffff">Terms &amp; Conditions</a></div></td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" align="left" width="40%">
              <tr>
                <td height="29" width="20%" align="right"><img src="<?php echo base_url();?>assets/mailers/transaction_to_merchant/f02.png" width="29" alt=""/></td>
                <td  height="29" width="80%" align="left" valign="middle"><div style="padding:0 0 0 10px"><a href="mailto:wecare@shmart.in" target="_blank" style="font-size:11px;text-decoration:none;color:#ffffff">E-mail: wecare@shmart.in</a></div></td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" align="right" width="33%">
              <tr>
                <td height="29" width="20%" align="right"><img src="<?php echo base_url();?>assets/mailers/transaction_to_merchant/f03.png" width="29" alt=""/></td>
                <td  height="29" width="80%" align="left" valign="middle"><div style="padding:0 0 0 10px"><a href="http://shmart.in/" target="_blank" style="font-size:11px;text-decoration:none;color:#ffffff">Website: shmart.in</a></div></td>
              </tr>
            </table
								></td>
        </tr>
          </table></td>
      </tr>
    </table>
</body>
</html>