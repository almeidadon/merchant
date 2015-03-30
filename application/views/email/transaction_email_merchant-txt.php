<p>
	Dear <?php echo $merchant_id; ?>,
</p>
<p>
	<?php echo $name_on_card; ?> made a transaction of Rs. <?php echo $total_amount; ?> on <?php echo $merchant_website; ?>.
</p>
<p>
	The details of transaction are as follows :
	<br/>
	Merchant RefID : <?php echo $merchant_refID; ?>
	<br/>
	Shmart RefID : <?php echo $shmart_refID; ?>
	<br/>
	App Used : <?php echo $app_used; ?>
	<br/>
	Transaction Status : <?php echo $status_msg; ?>
	<br/>
	Amount : <?php echo $total_amount; ?>
	<br/>
	Transaction Mode : <?php echo $transaction_mode; ?>
	<br/>
	Payment Mode : <?php echo $cardType; ?>
	<br/>
	Payment Provider : <?php echo $cardProvider; ?>
	<br/>
	Customer Email : <?php echo $email; ?>
	<br/>
	Customer MobileNo : <?php echo $mobileNo; ?>
	<br/>
	<?php if(($app_used=='JS') || ($app_used=='PAY_BY_SHMART'))
			{
				echo "Customer Address :".$addr." ".$city." ".$state." ".$country." ".$zipcode;
			} ?>
	<br/>
</p>
<p>
	Kindly note the transaction will be marked as Transerv Pvt Ltd. in your bank/card statement.
</p>
<p>
	If you have any queries regarding this transaction, please write to us @ support@shmart.in
</p>
<p>
Regards,
<br/>
Team Shmart