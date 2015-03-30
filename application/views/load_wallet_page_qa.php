<?php
if(isset($_POST['submit']))
{
    $data['mobileNo']  = '9400429941';
    $data['trans_general_wallet_amount'] = ($_POST['amount_general_wallet']*100);
    $data['trans_voucher_wallet_amount']  = ($_POST['amount_voucher_wallet']*100);
    $data['expiry_date'] = $data['voucher_expiry']  = $_POST['voucher_expiry'];
    $data['voucher_number']  = '12121';

    $data['general_wallet_txnNo'] = mt_rand(1000000,9999999);
    if($data['trans_general_wallet_amount'] != 0)
    {
        //credit general wallet
        $response_general_credit = $this->wallet_shmart->creditGeneralWallet($data);
        if($response_general_credit['ResponseCode']==0)
        {
            echo "<h2>Your general wallet was loaded with Rs ".($data['trans_general_wallet_amount']/100)."</h2>";
			//print_r($response_general_credit);
        }
		else
		{
			echo "<h2>Sorry Please Try Again... :(</h2>";
			
		}
    }
    if($data['trans_voucher_wallet_amount'] != 0)
    {
        $data['voucher_type'] = 'G';
        $data['narrations'] = 'QA';
        $data['merchant_id'] = 'SHOPCLUES';
        unset( $data['trans_general_wallet_amount']);
        unset( $data['general_wallet_txnNo']);
        unset( $data['voucher_expiry']);
        unset( $data['voucher_number']);
        $response_voucher_credit = $this->wallet_shmart->createVoucher($data);
        if($response_voucher_credit)
        {
            echo "<h2>You created a voucher worth Rs ".($data['trans_voucher_wallet_amount']/100)."</h2>";
        }
    }
    $res_gen = $this->wallet_shmart->generalWalletBal($data);
    $res_vou = $this->wallet_shmart->voucherWalletBal($data);
    echo "<h3>Your General wallet balance is</h3>".$res_gen['AvailableBalance'];
    echo "<h3>Your Voucher wallet balance is</h3>".$res_vou['AvailableBalance'];


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
<div id='contact_form_errorloc' class='err'></div>
<form method="POST" name="contact_form" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">

    <p>
        <label for='amount_general_wallet'>Load General Wallet: </label><br>
        <input type="text" name="amount_general_wallet" value='' style="width:230px;">
    </p>
    <p>
        <label for='name'>Load Voucher Wallet: </label><br>
        <input type="text" name="amount_voucher_wallet" value='' style="width:230px;">
    </p>
    <p>
        <label for='name'>IF Voucher enter Expiry: </label><br>
        <input type="text" name="voucher_expiry" value='' style="width:230px;">
    </p>
    <div>

        <input type="submit" value="Load !" name='submit' style="width:240px;" >
    </div>

</form>

</body>
</html>