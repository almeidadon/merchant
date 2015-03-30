$( window ).load(function() {
       
   
$("#ccType").on('change', function () {
    var ccType = $(this).val();
    if (ccType == "visa") {
	 $('#ccd').attr('src', 'https://www.zwitch.co/api/assets/img/cardlogos/visa.png');
    }
    if (ccType == "maestro") {
        $('#ccd').attr('src', 'https://www.zwitch.co/api/assets/img/cardlogos/maestro.png');
    }
    if (ccType == "mastercard") {
        $('#ccd').attr('src', 'https://www.zwitch.co/api/assets/img/cardlogos/mastercard.png');
    }
    if (ccType == "visa_electron") {
        $('#ccd').attr('src', 'https://www.zwitch.co/api/assets/img/cardlogos/maestro.png');
    }
	
})
 

 });
 