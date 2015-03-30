<p>
	Dear <?php echo $name_on_card; ?>,
</p>
<p>
	You made a transaction of Rs. <?php echo $total_amount; ?> on <?php echo $merchant_website; ?>.
</p>
<p>
	The details of transaction are as follows :
<br/>
	Merchant RefID : <?php echo $merchant_refID; ?>
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
	Merchant Contact Address : <?php echo $merchant_telephone; ?>
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