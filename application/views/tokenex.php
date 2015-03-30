<?php



?>



<html>
<head>
<script src="https://pay.shmart.in/assets/payment_page/js/shmart.js"></script>
<script type="text/javascript">
    <!--our encryption function-->
    function encryptCCnumber() {
        try {
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
                //remove the clear text credit card from the form
                document.getElementById("txtCreditCard").value = "";



        }
        catch (e) {
            //display error message and prevent the post back
            var form = document.getElementById('Form1')
            var div = document.createElement("div");
            div.innerHTML = '<div id=\"error\" class=\"alert alert-error\"><strong>' + e + ' </strong><div class=\"pull-left\"><img src=\"img/RedX.png\" /></div><br /></div>';
            form.insertBefore(div, form.firstChild);
            return false;
        }
    }
</script>
</head>

<form action='push/tokenex' method='POST' id='card-form'>

<input autocomplete="off" class="form-control textbox creditCardText shmart_data" required="true" id="ccnumber" type="tel">

<button class="btn btn-lg btn-primary btn-block pay_button" onclick="encryptCCnumber();" type="submit">Cipher</button>

</form>