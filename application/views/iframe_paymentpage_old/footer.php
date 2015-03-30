<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="<?php echo base_url(); ?>assets/iframe_assets/js/vendor/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="<?php echo base_url(); ?>assets/iframe_assets/js/flat-ui.min.js"></script>
		
		<script src="<?php echo base_url(); ?>assets/iframe_assets/js/application.js"></script>	
		<script src="<?php echo base_url(); ?>assets/payment_page/js/cardvalidation.js"></script>	
		<script src="<?php echo base_url(); ?>assets/payment_page/js/cardlogodisplay.js"></script>	
		

<script src="https://pay.shmart.in/assets/payment_page/js/shmart.js"></script>	
<script type="text/javascript">
    <!--our encryption function-->
    function encryptCCnumber() {
            var ccnumber = document.getElementById("ccnumber");
            var creditCard = ccnumber.value;
                //insert your custom credit card validation logic here.

                //grab the public key from the hidden field
                var key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8gGPMwBRPuVyJReZGIkWH/+Bf5pnpDN1bSR2cLYLXVT8CaSnTw74qeboSnktgYCi1D9R3Bj2fYzTIwGGZb8KinLdxwbqZmHwrE9cFhCaHbG/E0PbqQGhXP2vbniZTT4M0i0Cbi7ES3Bw5zqNbIZZnX041QEpxLvIyWPKZCX+fBogNMhWfCF779aclChjHkwZMsufThVWE9xklWGxXiytx5aL4I5JPxq0I7cAkZGGs4aF/GxWwPaq7R3wPikJQ0YHnCMfcURzl2Hnw/inqyMy+JB6djTq2/BdzMAhWTh3cDWq9Xu+gEkb/QCd0n1yYIdKuDISlk/AfHdWe34IvzhVyQIDAQAB';
                //encrypt the data
                var cipherText = TxEncrypt(key, creditCard);
                //add a new field to our form.
                var myin = document.createElement("input");
                myin.type = 'hidden';
                myin.name = 'shmart_cipherText';
                myin.value = cipherText;
                document.getElementById('card-form').appendChild(myin);
                
    }
</script>	
<script>

$('#ccType').change(function(){
    if($(this).val() == 'maestro'||$(this).val() == 'visa_electron')
{
	$('#cvv').attr('readonly', false);
	$("#cvv").val("123");
	$("#cardExpiryMonth").val("12");
	$("#cardExpiryYear").val("2020");
}
    else
{
	$('#cvv').attr('readonly',false);
	$("#cvv").val("");
	$("#cardExpiryMonth").val("");
	$("#cardExpiryYear").val("");
}
});

</script>
				</body>
				</html>	