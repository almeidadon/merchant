<body>
	<form action="<?php echo base_url(); ?>iframe/pay/process" method="post" id="card-form">
		<ul class="cards" style="float:right;display:none">
			<li class="visa"></li>
			<li class="visa_electron"></li>
			<li class="mastercard"></li>
			<li class="maestro"></li>
		</ul>
		<div class="col-md-12 no-padding">
			<div class="col-md-12"><h6>Card Details</h6></div>
		<div class="col-md-12 col-xs-12 padding-bottom">
			<select id="cardType" name="cardType" class="form-control  mrs mbm">
				<option value="CC">Credit Card</option>
				<option value="DB">Debit Card</option>
			</select>
		</div>
			<div style="clear:both;"></div>
		<!--	<div class="col-md-12 padding-bottom">
				<input type="text" id="ccnumber" value="" placeholder="Card Number" class="form-control shmart_data" />
			</div> -->
		
			
		<div class="col-sm-12 form-group padding-bottom">
			<div class="fake-input"><input autocomplete="off" class="form-control textbox creditCardText" placeholder="Card number" required="true" id="ccnumber" type="tel"> <img id="ccd" src="<?php echo base_url();?>assets/payment_page/img/cards.png"></div>
		</div>
	<div id="error" class="col-sm-12 form-group padding-bottom text-danger">
		
	</div>
		
		
			<div class="col-md-6 col-xs-6 padding-bottom">
				<select id="cardExpiryMonth" name="cardExpiryMonth" class="form-control expiry  mrs mbm">
				<option value="0">Expiry Month</option>
					<?php for ( $i = 1; $i <= 12; $i++ ) {
						$month = ''.$i;
						if ( strlen ( $month ) < 2 ) {
							$month = '0'.$month;
						} ?>
						<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
					<?php } ?>
				</select>
			</div>
				
				<div class="col-md-6 col-xs-6 padding-bottom">
				<select id="cardExpiryYear" name="cardExpiryYear" class="form-control expiry mrs mbm">
				<option value="0">Expiry Year</option>
					<?php for ( $i = date('Y'); $i < date('Y') + 30; $i++ ) { ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
				</div>				
			<div id="expiry-error" class="col-sm-12 form-group padding-bottom text-danger">
		
			</div>
			<div style="clear:both;"></div>
			
			<div class="col-md-4 col-xs-4 padding-bottom">
				<input type="text" name="cvv" id="cvv" value="" maxlength="4" placeholder="CVV" class="form-control" />				
			</div>
			<div id="cvv-error" class="col-sm-12 form-group padding-bottom text-danger">
		
			</div>
			<div class="pa">3 digits usually found on the signature strip</div>
			<div class="col-md-12 col-xs-12 padding-bottom">
				<input type="text" name="name_on_card" id="name_on_card" value="" placeholder="Name on card" class="form-control" />				
			</div>
			<div id="name-error" class="col-sm-12 form-group padding-bottom text-danger">
		
			</div>
			
			<div style="clear:both;"></div>
		
			
			
		</div>
		<input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>" class="form-control" />
		<input type="hidden" name="merchant_refID" value="<?php echo $merchant_refID; ?>" class="form-control" />
		<input type="hidden" name="shmart_refID" value="<?php echo $shmart_refID; ?>" class="form-control" />
		<input type="hidden" id="ccType" name="ccType" value="" class="form-control" />
		<div class="col-md-12 col-xs-12">
			<input type="submit" id="submit-btn" disabled="disabled" class="btn btn-sm btn-primary sub-button" onclick="encryptCCnumber()"  value="MAKE PAYMENT" />
		</div>
	</form>
