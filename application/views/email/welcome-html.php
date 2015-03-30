<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shmart</title>
</head>

<body>
<div style="width:620px; margin:auto" id="main">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    
    <tr>
      <td align="center" valign="middle"><table width="84%" border="0" cellspacing="0" cellpadding="0" id="top">
        <tr>
          <td align="center" valign="bottom">
          
          <div id="account"><font color="#000000" style="font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:12px; font-weight:normal; line-height:18px;" ><a href="#" target="_blank" style="text-decoration:none; color:#000000;">Your Account</a> &nbsp;  | &nbsp; <a href="http://www.shmart.in" target="_blank" style="text-decoration:none; color:#000000;">Shmart.in</a></font></div>
          
          <div id="logo"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/shmart-logo.gif" alt="Shmart" width="154" height="30" /></div>
          
          </td>
        </tr>
      </table>
        <div style="width:100%; height:10px; background-color:#f36f21;" id="line"></div>
      </td>
    </tr>
    
    <tr>
      <td height="150" align="center" valign="middle" >
      <table width="100%" border="0" cellspacing="0" cellpadding="5" id="headline">
        <tr>
          <td align="center" valign="middle" style="font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:40px; font-weight:normal; line-height:40px; color:#3bab9d;">Your payment  <?php	if($status == '0')	{ ?>is successful! <?php } else { ?> has been failed  <?php  } ?></td>
        </tr>
      </table>
      <span style="font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:25px; font-weight:normal; line-height:25px; color:#f36f21;">Thank you for using Shmart! Wallet</span></td>
    </tr>
    <tr>
      <td align="center" valign="top">
      
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" valign="top">
          
          <div style="width:520px; margin:auto;" id="data">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="data">
            <tr>
              <td align="center" valign="top"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/clip.gif" width="100%" /></td>
            </tr>
            <tr>
              <td height="170" align="center" valign="top" bgcolor="#E7F5F3" style="border-bottom:1px solid #3bab9d;"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" align="left" valign="top"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/guaranteed.gif" alt="100% Guarantted" width="138" height="119" /></td>
                    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" id="canspamBar">
                      <tr>
                        <td colspan="2" align="left" valign="middle"><span style="margin: 0 0 10px 0; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:20px; color:#f36f21; font-weight:normal;">Payment Receipt</span></td>
                        </tr>
                      <tr>
                        <td align="left" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;"><?php	if($status == '0')	{ ?>You have paid:  <?php } else { ?> Amount tried  <?php  } ?></</td>
                        <td align="right" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;"><strong><?php echo Rs.$total_amount; ?></strong></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;">Payment made to:</td>
                        <td align="right" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;"><?php echo $merchant_website; ?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;">Payment mode:</td>
                        <td align="right" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;"><?php echo $cardType; ?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;">Transaction ID:</td>
                        <td align="right" valign="middle" style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#000000; font-weight:normal; line-height:14px;"><?php echo $merchant_refID; ?></td>
                      </tr>
                    </table>
                    </td>
                    </tr>
              </table>
              
              
              </td>
            </tr>
          </table> 
          </div>
          
          
          
          
          
                     </td>
        </tr>
        <tr>
          <td height="100" align="center" valign="middle">&nbsp;&nbsp;<br />
            &nbsp;<span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:13px; color:#535252; font-weight:normal; line-height:16px;">If you have not initiated this transaction or have any queries<br />
            please contact us at 022 – 6730 4948 or <a href="mailto:WeCare@Shmart.in" target="_blank" style="text-decoration:underline; color:#535252;">WeCare@Shmart.in</a></span><br />
            &nbsp;<br /></td>
        </tr>
        
        <tr>
          <td align="center" valign="top" bgcolor="#f6f6f7">&nbsp;<br />
            <font color="#3bab9d" style="font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:25px; font-weight:normal; line-height:30px;" >You have been pre-approved</font><br />
            <p style="color:#f36f21; margin:0 0 10px 0; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:24px; font-weight:normal; line-height:30px;" >for a Shmart! Wallet<br />
            </p>
            <table width="450" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="45" align="center" valign="top"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/icon1.gif" width="25" height="27" /></td>
                <td><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#535252; font-weight:normal; line-height:16px;">Use your smartphone to make payments instantly,<br />
                  without any transaction fees!</span></td>
              </tr>
              <tr>
                <td align="center" valign="top"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/icon2.gif" width="25" height="18" /></td>
                <td><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#535252; font-weight:normal; line-height:16px;">Store your card details so you canpay next time<br />
                  in quick clicks. </span></td>
              </tr>
              <tr>
                <td align="center" valign="top"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/icon3.gif" width="25" height="23" /></td>
                <td><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:14px; color:#535252; font-weight:normal; line-height:16px;">Send money to friends or family simply by selecting &amp;<br />
                  adding their number from your mobile contact list. </span></td>
              </tr>
            </table>
            <br />
            <table width="450" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
              <tr>
                <td height="110" align="center" valign="top" style="border-top:1px solid #3bab9d; border-bottom:1px solid #3bab9d;"><p style="color:#f36f21; margin:20px 0; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:18px; font-weight:normal; line-height:24px;"><a href="http://www.shmart.in" target="_blank"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/login.gif" alt="Login to shmart.in" width="188" height="31" border="0" /></a></p>
                <table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td align="center" valign="middle"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/icon4.gif" width="11" height="11" /></td>
    <td><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:16px; color:#535252; font-weight:normal; line-height:16px;">Use your mobile number as <strong><?php echo $username ; ?> </strong></span></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/icon5.gif" width="11" height="14" /></td>
    <td><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:16px; color:#535252; font-weight:normal; line-height:16px;">Use this password to login - <strong><?php echo $password ; ?> </strong></span></td>
  </tr>
</table>

                </p></td>
              </tr>
            </table>
            &nbsp;<br />
            &nbsp;<br />
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="70%" align="left" valign="bottom" style="border-top:1px solid #ddddde;"><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:11px; color:#535252; font-weight:normal; line-height:13px;">&nbsp;<br />
                  Terms and conditions apply.<br />
                  <br />
                  Shmart! Wallet offers membership to the<br />
                  semi-closed prepaid instrument issued by RBL Bank.</span></td>
                <td align="right" valign="bottom" style="border-top:1px solid #ddddde;"><table width="130" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td width="18%" align="right" valign="middle"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/phone.gif" alt="Phone" width="11" height="17" /></td>
                    <td width="82%"><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:11px; color:#535252; font-weight:normal; line-height:13px;">022 - 6730 4948</span></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle"><img src="<?php echo base_url();?>/assets/mailers/successful-payment/images/email.gif" alt="Email" width="17" height="11" /></td>
                    <td><span style="margin:0px; font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:11px; color:#535252; font-weight:normal; line-height:13px;"><a href="mailto:WeCare@Shmart.in" target="_blank" style="text-decoration:underline; color:#535252;">WeCare@Shmart.in</a></span></td>
                  </tr>
                </table></td>
              </tr>
            </table>            
            &nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="center" valign="middle" bgcolor="#e2e3e4"><p style="font-family: 'Trebuchet MS', helvetica, Arial, sans; font-size:10px; color:#535352; font-weight:normal; line-height:12px;"> 2015 All rights are reserved with TranServ Pvt. Ltd.</p></td>
        </tr>
        
      </table></td>
    </tr>
  </table>
</div>
<style type="text/css">
#logo{ float:left; margin:30px 0; height:30px; }
#account{ float:right; height:20px; margin:30px 0; padding-top:10px;}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
                    @media only screen and (max-width: 480px){
                        table[id="canspamBar"] td{font-size:12px !important;} 
						table[id="headline"] td{font-size:30px !important;} 
						table[id="data"] table{width:95% !important;} 
						table[id="top"] {width:95% !important;}
						td[id="wrap"] td{border:none !important;}				
						#main {width: 100% !important; margin: auto;}
						#data {width: 95% !important; margin: auto;}		
						#logo{ clear:both; height:30px; margin:20px 0; text-align:center; width:100%; }
						#account{ clear:both; height:20px; padding-top:10px; margin:0px;  text-align:right; width:100%; }
						#line{display:none;}
                   		}
                </style>
</body>
</html>

