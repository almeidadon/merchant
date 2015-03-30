<?php
if($app_used=='COLLECT')
{
    $url = base_url().'collect/processCollect/transactions';
}
else if ($app_used=='INVOIZ')
{
    $url = base_url().'invoiz/processInvoiz/transactions';
}
else if ($app_used=='WEBSTORE')
{
    $url = base_url().'webstore/processStore/transactions';
} else
{
    $url = base_url().'buttn/processButtn/transactions';
}
 ?>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="nav-brand"><img src="<?php echo base_url();?>assets_todo/images/shmart_transparent.png" /></div>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading text-center">
            Enter Details
          </header>
          <form class="panel-body" action="<?php echo $url;?>" method="POST">
            <div class="form-group">
                <label for="mobileNo">Enter your mobile number</label>
            <input type="text" placeholder="Enter mobile number" name="mobileNo" class="form-control text-center" required="true" data-type="number">

                <input type="hidden" name="merchant_refID" value="<?php echo $merchant_refID; ?>"     />
                <input type="hidden"name="email"   value="<?php echo $email;?>"  />
                <input type="hidden"name="apikey"   value="<?php echo $app_id; ?>" />
                <input type="hidden"name="ip_address"  value="<?php echo $ip_address; ?>"   />
                <input type="hidden"name="checksum_method" value="<?php echo $checksum_method; ?>"   />
                <input type="hidden"name="secretkey" value="<?php echo $secretkey ;?>"   />
                <input type="hidden"name="currency_code"   value="<?php echo $currency_code; ?>"  />
                <input type="hidden"name="authorize_user"  value="<?php echo $authorize_user;?>"   />
                <input type="hidden"name="amount"   value="<?php echo $amount ;?>"  />
                <input type="hidden"name="checksum"   value="<?php if(isset($checksum)) { echo $checksum ; }?>"  />
                <input type="hidden"name="merchant_id"   value="<?php echo $merchant_id ;?>"  />
                <input type="hidden"name="app_used"   value="<?php echo $app_used ;?>"  />
                <input type="hidden"name="app_id"   value="<?php echo $app_id ;?>"  />
                <input type="hidden"name="f_name"   value="NA"  />
                <input type="hidden"name="addr"   value="NA"  />
                <input type="hidden"name="city"   value="NA"  />
                <input type="hidden"name="state"   value="NA"  />
                <input type="hidden"name="country"   value="NA"  />
                <input type="hidden"name="show_shipping_addr"   value="0"  />
                <input type="hidden"name="zipcode"   value="123456"  />
            </div>
              <?php if($amount == '0') {?>
              <div class="form-group">
                  <label for="amount">Enter amount</label>
                  <input type="text" placeholder="Enter amount" id="amount" value="" class="form-control text-center" required="true" data-type="number">
                  <input type="hidden" placeholder="Enter amount" id="actualamount" name="amount" value="" class="form-control text-center" required="true" data-type="number">
             
			 </div>
              <?php } else { ?>
              <input type="hidden" name="amount"   value="<?php echo $amount ;?>"  />
              <?php } ?>
            <button type="submit" id="submit" class="btn btn-twitter btn-block" ><i class="icon-mobile-phone pull-left"></i><i class="icon-arrow-right pull-right"></i> Proceed</button>
            <div class="line line-dashed"></div>
          </form>
        </section>
      </div>
    </div>
  </section>
  <script>

  
  $(document).ready(function() {
  
		$('#submit').click(function(){
			var amount =	$('#amount').val();
			amount = (amount*100);
			$('#actualamount').val(amount);
			
		});
		
    });
  
  </script>
</body>
