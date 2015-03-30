<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="nav-brand"><img src="<?php echo base_url();?>assets_todo/images/shmart_transparent.png" /></div>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading text-center">
            Simulated bank response
          </header>
          <form class="panel-body" action="<?php echo base_url();?>response.php" method="POST">
            <div class="form-group">
                <label for="status_code">Choose the response</label>
					<select name="Status" class="form-control" class='col-sm-1'>
						<!-- <option value="">Choose status</option> -->
						<?php foreach($status_data as $row):
								echo "<option value='".$row['status_code']."'>".$row['status_msg']."</option>";
						  endforeach; ?>
					</select>
                <input type="hidden" name="ReferenceNo" value="<?php echo $shmart_refID; ?>"  />
                <input type="hidden" name="TransactionId" value="<?php echo "SANDBOX_REFNO";?>"  />
                <input type="hidden" name="MerchantId" value="<?php echo $merchant_id; ?>" />
                <input type="hidden" name="Amount" value="<?php echo $pg_amount; ?>" />
                <!-- <input type="hidden" name="CheckSum" value="<?php //echo $checksum; ?>" /> -->
            </div>
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
