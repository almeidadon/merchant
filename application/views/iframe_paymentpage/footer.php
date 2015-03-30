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
	 $('.expiry').addClass('valid');
	 $('#cvv').addClass('valid');

}
    else
{
	$('#cvv').attr('readonly',false);
	$("#cvv").val("");
	// $("#cardExpiryMonth").val("");
	// $("#cardExpiryYear").val("");
}
});

</script>
<script>
 $('#ccnumber').on('blur', function() {
        if ($('#ccnumber').hasClass('valid')) {
            $('#error').html('');
            if ($('#ccType').val() == 'visa_electron') {
                document.getElementById("expiry_month").disabled = true;
                document.getElementById("expiry_year").disabled = true;
                $('.expiry').addClass('valid');
                $('#cvv').addClass('valid');
            }
        } else {
            $('.expiry').removeClass('valid');
            $('#error').html('Enter a valid card number');
        }
    });
    $('.expiry').on('change', function() {
        var isDisabled = $('.expiry').is(':disabled');
        if (!isDisabled) {
            var today, someday;
            var exMonth = document.getElementById("cardExpiryMonth").value;
            var exYear = document.getElementById("cardExpiryYear").value;
            today = new Date();
            someday = new Date();
            someday.setFullYear(exYear, exMonth, 1);
            if (someday < today) {
                $('#expiry-error').html('Invalid expiry');
                $('.expiry').removeClass('valid');
            } else {
                $('#expiry-error').html('');
                $('.expiry').addClass('valid');
            }
        }
    });
    $('#name_on_card').on('keyup', function() {
        if ($(this).val().match('^[a-zA-Z ]{3,16}$')) {
            $('#name-error').html('');
            $(this).addClass('valid');
        } else {
            $(this).removeClass('valid');
            $('#name-error').html('Enter a valid name');
        }
    });
	$('#cvv').on('keyup', function() {
        if ($(this).val().match('^[0-9]{3,4}$')) {
            $('#cvv-error').html('');
            $(this).addClass('valid');
        } else {
            $(this).removeClass('valid');
            $('#cvv-error').html('Enter a valid CVV');
        }
    });
    $('input[name=cardType]').on('change', function() {
        $(this).addClass('valid');
    });
    $('#ccnumber, #cardExpiryMonth, #cardExpiryYear,#cvv, #name_on_card').on('click keyup change blur focus', function() {
        if ($('#ccnumber').hasClass('valid') && $('#cardExpiryMonth').hasClass('valid') && $('#cardExpiryYear').hasClass('valid') && $('#cvv').hasClass('valid') && $('#name_on_card').hasClass('valid')) {
            document.getElementById("submit-btn").disabled = false;
			alert('wooh');
        } else {
            document.getElementById("submit-btn").disabled = true;
        }
    });
	</script>
				</body>
				</html>	