<?php
$otp_field = array(
    'name'          => 'wallet_amount_field',
    'type'       => 'tel',
    'id'        =>'user_otp',
    'name'        =>'user_otp',
    'class'        =>'form-control',
    //'disabled'        =>'disabled',
);
$shmart_password_field = array(
    'type'       => 'password',
    'id'        =>'shmart_password',
    'name'        =>'shmart_password',
    'class'        =>'form-control',
);
$email_field = array(
    'name'          => 'email_field',
    'type'       => 'hidden',
    'id'        =>'email',
    'name'        =>'email',
    'value'     =>$email,
);
$mobileNo_field = array(
    'name'          => 'mobileNo_field',
    'type'       => 'hidden',
    'id'        =>'mobileNo',
    'name'        =>'mobileNo',
    'value'     =>$mobileNo,
);
$shmart_refID_field = array(
    'name'          => 'shmart_refID_field',
    'type'       => 'hidden',
    'id'        =>'shmart_refID',
    'name'        =>'shmart_refID',
    'value'    => $shmart_refID,
);
?>
<body  >    
<style>
.logo-image-holder {
    width: 120px;
    min-height: 25px;
    max-height: auto;
    float: left;
    margin: 3px;
    padding: 3px;
}
.logo-img
{
    max-width: 100%;
    height: auto;
}
.lock {
    opacity:0.1;
    filter: alpha(opacity = 10);
    position:absolute;
    top:0; bottom:0; left:0; right:0;
    display:block;
    z-index:2;
    background:#555;
}
.content {
    position:relative;
    z-index:1;
}
</style>
    <!-- preloader -->
        <div class='preloader-wrapper'>
            <div class="preloader">
                <img src="<?php echo base_url();?>/assets/payment_page/img/preloader.png" alt='loading image'/>
            </div>
        </div><!-- / preloader -->

        <!-- Container that wraps all content that gets "pushed" when chat panel shows-->
        <div id="container"> 

        <!-- Top Navbar  -->
        <div id="top-navbar" class="navbar navbar-static-top navbar-default navbar-inverse add-shadow" role="navigation">
            <div class="navbar-header text-center">  
				
                <!-- logo -->
                <div class="navbar-brand col-lg-12 col-lg-offset-9 col-md-12 col-md-offset-9 col-sm-12  col-sm-offset-9 col-xs-7">
				<a href="#"> <img src="<?php echo base_url();?>/assets/payment_page/img/logo.png" alt="logo" class="img-responsive"></a>
				</div>
                <!-- / logo -->
            </div>
          
        </div><!-- / top Navbar -->	      
        </div>
		
		
		<div id="container-boxed-layout"> 
        <!-- Wrapper for content below nav bar -->
        <div id="wrapper">
          <!-- Keep all page content within the page-content-wrapper -->
          <div id="page-content-wrapper" class=" animated-med-delay fadeInRight">
                    
                    <!--Page Content-->	
					<div class="row">
					<!--Use Shmart Wallet-->	
					<div class="panel panel-body-only panel-white-solid">
                                            <div id="recentAnnouncements" class="panel-body">
                                                   <div class="carousel slide carousel-fade" id="carousel_fade">
                                                        <div class="carousel-inner">       
                                                              <div class="item active">
																<div class="col-lg-12 padding-bottom">
																<?php if($whitelabel) { ?>
																	<div class="col-lg-8 col-sm-6 col-xs-6 logo-image-holder"><img src="https://merchant.shmart.in/merchant_business_logo_files/<?php echo $whitelabel['user_id'];?>/<?php echo $whitelabel['user_id'];?>.png" alt="bookmyshow" class="img-responsive logo-img"></div>
																<?php } ?>	
																<div class="row">
																	<div class="col-lg-6">
																	<h5 class="text-right pull-left text-primary">Merchant Name : <?php echo $merchant_name; ?></h5>
																	</div>
																	<div class="col-lg-6">
																	<h5 class="text-right text-primary">Merchant reference ID : <?php echo $merchant_refID; ?></h5>
																	</div>
																</div>
																	<span class="clearfix"></span> 
																</div>
															  <?php if($customer_details['customer_data'] != '0') { ?>
                                                                    <div class="alert alert-default col-lg-12 no-padding">
																	<div class="panel-body brand-warning-bg col-lg-3 text-inverse">
																		<div class="pull-left">
																		<div class="badge-circle daily-stat-left"><div class="big-text"><i class="icon-rupee"></i></div></div>							
																		</div>
																		<div class="stat">
																		<h4>Amount to Pay </h4>
																		</div>
																		<!-- <span class="medium-text">Rs.</span> -->
																		<span id="medium-text" class="medium-text"><?php echo $total_amount; ?></span>
																		 
																	</div>
																	
																	<div class="col-lg-9">
																		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 use-wallet custom-check">
																	<?php if($wallet_amount>'0' || $consumer_fund == '1' ) { ?>	<span class="check pull-left"><div class="ez-checkbox"><div class="ez-checkbox"><div class="ez-checkbox"><input type="checkbox" id="use_wallet" <?php if($wallet_amount) { ?> checked="checked" <?php } ?> class="checked ez-hide"></div></div></div></span> <?php } ?>
																		<div class="wallet_small pull-left"></div>
																		<span class="clearfix"></span> 
																		</div>
																		
																		<div class="col-lg-5 col-sm-5 col-xs-6" style="padding-top:25px;">																		
                                                                        <h3>
																	<?php if ($whitelabel) { ?>
																	<?php echo $whitelabel['brand_name'] ; ?>
																	<?php } else { ?>
																			Shmart
																	<?php }?>
																		Wallet</h3>
																		<h4>Your current balance is Rs <?php echo $wallet_amount ;?> </h4>
																		</div>
																		<form class="form-horizontal" role="form" method="POST" action="<?php echo base_url();?>checkout/processTransaction/cards">
																		<?php echo form_input($shmart_refID_field) ; ?>
																		<input type="hidden" name="merchant_id" value="<?php echo $customer_details['merchant_id'];?>"/>
																		<input type="hidden" name="use_wallet" value="<?php if($wallet_amount> "0" ) { ?>1<?php } else { ?>0<?php } ?>" class="use_wallet_field"/>
																		<div class="col-lg-4 col-sm-4 col-xs-6" style="padding-top:25px;">
																		<?php if($wallet_amount >= $total_amount) { ?>
                                                                        <button type="submit" class="btn btn-primary btn-lg" style="" id="wallet_pay_button">Pay using wallet	</button>
																		<?php } else { ?>
																		<button type="submit" class="btn btn-primary btn-lg" style="display:none" id="wallet_pay_button">Pay using wallet	</button>
																		<?php } ?>
																		</form>
																		</div>
																	</div>
																	
																	
																	
																	<span class="clearfix"></span> 
                                                                            </div>
																	<?php } else { ?>
																	 <div class="">
																	<div class="panel-body brand-warning-bg col-lg-3 text-inverse">
																		<div class="pull-left">
																		<div class="badge-circle daily-stat-left"><div class="big-text"><i class="icon-rupee"></i></div></div>							
																		</div>
																		<div class="stat">
																		<h4>Amount to Pay </h4>
																		</div>
																		<!-- <span class="medium-text">Rs.</span> -->
																		<span id="medium-text" class="medium-text"><?php echo $total_amount; ?></span>
																		 
																	</div>
																	</div>
																	<span class="clearfix"></span> 
                                                                            </div>
																	
																	<?php } ?>
                                                                        </div>       
                                                                        
                                                                       
                                                                     
                                                                                    
                                                                    </div>
                                                                    </div>
                                                            </div>
                                                 </div><!--/ Use Shmart Wallet-->
												 
					<!--Payment optins-->
							<div class="panel panel-body-only panel-white-solid content">
							
                                <div id="recentAnnouncements" class="panel-body">								
                                <div class="carousel slide carousel-fade" id="carousel_fade">
								<div class="col-md-12">
								<div class="col-md-7">
								<h2 style="padding-bottom:15px;">Select an option to pay balance</h2>
								</div>
								<div class="col-md-5">
								<span class="pull-right"><i class="icon-lock"></i> All transactions are secured via 256 bit SSL encrpytion</span>
								</div>
							</div>
                                <div class="carousel-inner">       
                                <div class="item active">	
						<?php if($wallet_amount >= $total_amount) { ?>								
						<div id="lock-overlay" class="lock"></div>		<!-- disable all payment options  -->	
						<?php } ?>
                                <div class="alert alert-default col-lg-12">								
															
                        <div class="col-md-12 vertical-nav"> <!--Tab Menu-->
                        
                            <div class="col-lg-3 col-md-3 col-sm-3 no-padding pills-default"> <!-- Nav pills -->
                                   <ul class="nav nav-pills nav-stacked">
								   <?php if($customer_details['customer_data'] != '0') { ?>
								   <?php  if($customer_details['customer_data']['saved_cards']['0'] !='0') { ?>
                                       <li class="active"><a href="#tab1" data-toggle="tab">Saved Cards</a></li>
									<?php }} ?>
                                       <li class="<?php  if($customer_details['customer_data']['saved_cards']['0'] =='0' || $customer_details['customer_data'] == '0') { echo "active" ;}?>"><a href="#tab2" data-toggle="tab">Debit Card</a></li>
									   
                                       <li class=""><a href="#tab2" data-toggle="tab">Credit Card</a></li>
									   <li class=""><a href="#tab3" data-toggle="tab">Net Banking</a></li>
                                   </ul>  
                            </div><!-- / nav pills -->
                         	 
                            <div class="col-lg-9 col-md-9 col-sm-9 no-padding"> <!-- content panes -->
                              	<div class="tab-content">
								 <?php if($customer_details['customer_data'] != '0') { ?>
										<div class="tab-pane  <?php  if($customer_details['customer_data']['saved_cards']['0'] !='0') { echo "active" ;}?>" id="tab1">
                                      		<h3>Saved Cards</h3>
									<form class="" role="form" method="POST" id="saved_card" action="<?php echo base_url();?>checkout/processTransaction/cards">
									<?php if($customer_details['customer_data']['saved_cards']['0'] !='0') foreach($customer_details['customer_data']['saved_cards'] as $saved_cards): ?>
										
										<div class="col-lg-6 no-padding">
											<div class="col-lg-12">
												<div class="saved-card custom-check white-bg">
												<span class="check">
													<div class="ez-radio">
														<div class="ez-radio">
														<input class="ez-hide padding-top" type="radio" name="saved_card_radio" value="<?php echo $saved_cards['user_card_unique_token']; ?>">
													</div>
													</div>
												</span>
												<span class="text-primary medium-text"><?php echo $saved_cards['card_id_by_consumer']; ?></span>
												<div class="<?php echo strtolower($saved_cards['card_provider']); ?> pull-right"></div>
												<div class="saved-card-nu"><?php echo $saved_cards['card_type']; ?></div>
												<div class="col-sm-12 saved_card_cvv form-group" id="<?php echo $saved_cards['user_card_unique_token']; ?>" style="display:none">
													<div class="col-sm-12">
													<label for="exampleInputEmail1">Enter CVV</label>
													</div>
										
													<div class="col-sm-5 form-group">																		
													<input class="form-control" placeholder="Enter" name="cvv" type="password">
													</div>
													<input type="hidden" name="user_card_unique_token" value="<?php echo $saved_cards['user_card_unique_token']; ?>" />
													<input type="hidden" name="merchant_id" value="<?php echo $customer_details['merchant_id'];?>"/>
													<input type="hidden" name="shmart_refID" value="<?php echo $shmart_refID ; ?>"/>
													<input type="hidden" name="use_wallet" value="<?php if($wallet_amount> "0" ) { ?>1<?php } else { ?>0<?php } ?>" class="use_wallet_field"/>
													<button type="submit"  class="btn btn-primary">Pay </button>
												</div>
												</div>
											</div>
										</div>
										<?php endforeach; ?>
										</form>

											
						<span class="clearfix"></span>
										</div>
							<?php } ?>
                                      
							<div class="tab-pane <?php  if($customer_details['customer_data'] == '0'||$customer_details['customer_data']['saved_cards']['0'] =='0') { echo "active" ;} ?>" id="tab2">
											
										<h3>Card Details</h3>
							<?php echo form_open('checkout/processTransaction/cards',array('id'=>'card-form','class'=>'panel-body')); ?>
								<div class="col-lg-10 custom-check"> 
								 <input id='ccType' name='ccType' type='hidden'>
								<input id='cardType' name='cardType' type='hidden' value="DB">
								<ul class="cards" style="float:right;display:none">
									<li class="visa"></li>
									<li class="visa_electron"></li>
									<li class="mastercard"></li>
									<li class="maestro"></li>
								</ul>
									
											<div class="col-sm-12 form-group">
												<label for="exampleInputEmail1">Enter Card Number</label>
												<div class="fake-input"><input autocomplete="off" class=
                            "form-control textbox creditCardText zwitch_data" required="true" id="ccnumber" type="tel"> <img id="ccd" src="<?php echo base_url();?>assets/payment_page/img/cards.png"></div>
											</div>
									
										<div class="col-sm-12 form-group no-padding">
												<div class="col-sm-6 form-group">
												<label for="cardExpiryMonth">Expiry Month</label>												
													 <select class="form-control" id="cardExpiryMonth"name="cardExpiryMonth">
														<?php for ( $i = 1; $i <= 12; $i++ ) {
															$month = ''.$i;
															if ( strlen ( $month ) < 2 ) {
																$month = '0'.$month;
															} ?>
															<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
														<?php } ?>
													</select>
												</div>
									
												<div class="col-sm-6 form-group">												
													<label for="cardExpiryYear">Expiry Year</label>														
													<select class="form-control"
                                                           data-required="true" data-trigger="change" id="cardExpiryYear" name=
													"cardExpiryYear">
														<?php for ( $i = date('Y'); $i < date('Y') + 30; $i++ ) { ?>
															<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
														<?php } ?>
													</select>
												</div>
											
												
												<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Name on Card</label>									
													<input class="form-control" placeholder="Name of card holder" name="name_on_card" id="name_on_card" required="true"  type="text">
												</div>
												<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Enter CVV</label>									
													<input class="form-control" placeholder="CVV" required="true" aria-required="true"  id="cvv" name="cvv"  type="password">
												</div>
												<span class="clearfix"></span>
												<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Email</label>									
													<input class="form-control" placeholder="Email" required="true" aria-required="true" id="email" name="email" required="true" value="<?php if(isset($email)AND $email){echo $email;}?>" type="text">
												</div>									
												
												<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Mobile Number</label>									
													<input class="form-control" placeholder="Mobile number" required="true" aria-required="true" id="mobileNo" required="true" name="mobileNo" value="<?php if(isset($mobileNo)AND $mobileNo){echo $mobileNo;}?>" type="text">
												</div>
												
												<div class="col-sm-12 pull-left">
													<label class="checkbox pull-left no-padding">
													<div class="ez-checkbox"><div class="ez-checkbox"><input class="ez-hide" name="save_card" id="save_card" value="1"  type="checkbox" checked="checked"></div></div>
													Save this card for faster checkout
													</label>
													
												</div>	
												<div class="col-sm-6 form-group" style="" id="card_id_by_consumer_field">
													<label for="exampleInputEmail1">Give a name for your card</label>									
													<input class="form-control" placeholder="eg : My SBI" id="card_id_by_consumer" required="true"  name="card_id_by_consumer" value="" type="text">
												</div>
										 <input type="hidden" name="merchant_id" value="<?php echo $customer_details['merchant_id'];?>"/>
										<input type="hidden" name="use_wallet" value="<?php if($wallet_amount> "0" ) { ?>1<?php } else { ?>0<?php } ?>" class="use_wallet_field"/>
										<?php echo form_input($shmart_refID_field) ; ?>
										<?php echo form_close(); ?>

												<div class="col-sm-12 pull-left no-padding">												
													<div class="col-sm-6 pull-left">
													<button class="btn btn-lg btn-primary btn-block pay_button" onclick="encryptCCnumber();" type="submit">
													<span class="text-transparent"></span>Pay
													</button> 
													</div>
													
													<div class="col-sm-6 pull-left">
													                        <button class="btn btn-lg btn-default btn-block" onClick="window.location.href ='<?php echo base_url();?>response/cancelResponseHandler/<?php echo $shmart_refID;?>'" type="button">Cancel Transaction</button>
										
													</div>
												</div> 
													<div class="col-sm-12 pull-left no-padding">
													<label for="exampleInputEmail1"><small></br>By clicking on pay , you agree to our <a target="_blank" href="http://shmart.in/tc.html" style="color:#3651A2">terms and conditions</a></small></label>
													</div>
													
												<span class="clearfix"></span>
										</div>
											
								</div> 
							</div> 
										
										<div class="tab-pane" id="tab3">
                                      		<h3>Net Banking</h3>
									<!--	<div class="col-lg-12 no-padding">
											<div class="col-lg-4 col-md-5">
												<div class="saved-card custom-check white-bg">
												<span class="check pull-left">
													<div class="ez-radio">
														<div class="ez-radio">
														<input class="ez-hide padding-top" type="radio" name="optionsRadios" id="optionsRadios2" value="option1" checked="">
													</div>
													</div>
												</span>
										
												<div class="pnb"></div>
													<span class="clearfix"></span>										
												</div>
											</div>
											
											<div class="col-lg-4 col-md-5">
												<div class="saved-card custom-check white-bg">
												<span class="check pull-left">
													<div class="ez-radio">
														<div class="ez-radio">
														<input class="ez-hide padding-top" type="radio" name="optionsRadios" id="optionsRadios2" value="option1" checked="">
													</div>
													</div>
												</span>
										
												<div class="kotak"></div>
													<span class="clearfix"></span>										
												</div>
											</div>
											
											<div class="col-lg-4 col-md-5">
												<div class="saved-card custom-check white-bg">
												<span class="check pull-left">
													<div class="ez-radio">
														<div class="ez-radio">
														<input class="ez-hide padding-top" type="radio" name="optionsRadios" id="optionsRadios2" value="option1" checked="">
													</div>
													</div>
												</span>
										
												<div class="icici"></div>
													<span class="clearfix"></span>										
												</div>
											</div>	

											<div class="col-lg-4 col-md-5">
												<div class="saved-card custom-check white-bg">
												<span class="check pull-left">
													<div class="ez-radio">
														<div class="ez-radio">
														<input class="ez-hide padding-top" type="radio" name="optionsRadios" id="optionsRadios2" value="option1" checked="">
													</div>
													</div>
												</span>
										
												<div class="hdfc"></div>
													<span class="clearfix"></span>										
												</div>
											</div>	

											<div class="col-lg-4 col-md-5">
												<div class="saved-card custom-check white-bg">
												<span class="check pull-left">
													<div class="ez-radio">
														<div class="ez-radio">
														<input class="ez-hide padding-top" type="radio" name="optionsRadios" id="optionsRadios2" value="option1" checked="">
													</div>
													</div>
												</span>
										
												<div class="axis"></div>
													<span class="clearfix"></span>										
												</div>
											</div>	
										
										</div>	-->
										<?php echo form_open('checkout/processTransaction/cards',array('id'=>'net-form','class'=>'panel-body')); ?>
										<span class="clearfix"></span>
											<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Name</label>									
													<input class="form-control" placeholder="Name" aria-required="true" name="name" id="name" required="true"  type="text">
												</div>
										
										<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Select your bank</label>
													<select class="form-control"  name="BankId" id="BankId">
													 <?php  foreach($net_banking_array as $net_banking_list): ?>
														<option value="<?php echo $net_banking_list['processor_net_banking_code']."||".$net_banking_list['processor']; ?>"><?php echo $net_banking_list['name_of_bank']; ?></option>
													<?php endforeach; ?>
													</select>
										</div>
										<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Email</label>									
													<input class="form-control" placeholder="Email" aria-required="true" required="true" id="email" name="email"  value="<?php if(isset($email)AND $email){echo $email;}?>" type="text">
												</div>									
												
												<div class="col-sm-6 form-group">
													<label for="exampleInputEmail1">Mobile Number</label>									
													<input class="form-control" placeholder="Mobile number" aria-required="true" required="true" id="mobileNo"  name="mobileNo" value="<?php if(isset($mobileNo)AND $mobileNo){echo $mobileNo;}?>" type="text">
										</div>
										 <input type="hidden" name="merchant_id" value="<?php echo $customer_details['merchant_id'];?>"/>
										<input type="hidden" name="cardType" value="NB"/>
										<input type="hidden" name="use_wallet" value="<?php if($wallet_amount> "0" ) { ?>1<?php } else { ?>0<?php } ?>" class="use_wallet_field"/>
										<?php echo form_input($shmart_refID_field) ; ?>
										<div class="col-sm-12 pull-left no-padding">												
													<div class="col-sm-6 pull-left">
													<button class="btn btn-lg btn-primary btn-block" type="submit">
													<span class="text-transparent"></span>Pay
													</button> 
													</div>
													
													<div class="col-sm-6 pull-left">
													
                        <button class="btn btn-lg btn-default btn-block" onClick="window.location.href ='<?php echo base_url();?>response/cancelResponseHandler/<?php echo $shmart_refID;?>'" type="button">Cancel Transaction</button>
													
													</div>
												</div>
												<div class="col-sm-12 pull-left no-padding">
													<label for="exampleInputEmail1"><small></br>By clicking on pay , you agree to our <a target="_blank" href="http://shmart.in/tc.html" style="color:#3651A2">terms and conditions</a></small></label>
													</div>
										   <?php echo form_close(); ?>
										
										<span class="clearfix"></span>
								</div>
                            </div><!-- / content panes -->
                        </div> <!--/ Tab Menu-->									
								
								<span class="clearfix"></span>	
								
                                </div>
                                </div>
							<!--	<div class="pci pull-right"></div> -->
								<div class="rbl pull-left"></div>           
                                </div>
                                </div>
                                </div>
                            </div>

						<!--/ Payment optins-->
							
					</div>
					
                                   
           </div><!-- /page-content-wrapper -->
		</div><!-- / wrapper for content below nav bar -->		

	</div>	<!-- / dd-->



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Welcome back to shmart!</h4>
                </div>
        
                <div class="modal-body text-center">	
                       <div class="form-group" style="" id="otp_field">
					    <label for="user_otp" class="text-info">Enter OTP to pay (OTP Sent to <?php echo $mobileNo;?>)</label>
					    <div class="input-group">
						   <?php echo form_input($otp_field) ; ?>
						   <span class="input-group-btn">
							  <button class="btn btn-primary  btn-sm" id="generate_otp" type="button">
								Regenerate OTP
							  </button>
						   </span>
						</div><!-- /input-group -->
						</div>
						 <p class="text-center"><strong>OR</strong></p>
						 
						 <div class="form-group">
                        <label for="shmart_password" class="text-info">Enter Shmart Password to pay</label>
                        <?php echo form_input($shmart_password_field) ; ?>
						</div>
                </div>
		
        
                <div class="modal-footer">
                  <div class="col-lg-8  col-lg-offset-2">
				   <?php echo form_input($mobileNo_field) ; ?>
                    <?php echo form_input($email_field) ; ?>
                    <?php echo form_input($shmart_refID_field) ; ?>
					<p id="pass_error" class="text-danger text-center"><small></small></p>
                  	<button type="button" id="validate_otp" class="btn btn-primary btn-lg btn-block" >Authorize transaction</button>
                  </div>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

		  
	
   	        <!-- JavaScript -->
        <!-- Placed at the end of the document so the pages load faster -->
        <!-- Theme Core -->
		<script src="<?php echo base_url();?>assets/payment_page/js/jquery.js"></script>
		<script src="<?php echo base_url();?>assets/payment_page/js/modernizr-2.6.2.min.js"></script>
        <script src="<?php echo base_url();?>assets/payment_page/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/payment_page/js/jquery-ui-1.10.4.custom.min.js"></script>        
		<script src="<?php echo base_url();?>assets/payment_page/js/script.js"></script>
		<script src="<?php echo base_url();?>assets/payment_page/js/cardlogodisplay.js"></script>
		<script src="<?php echo base_url();?>assets/payment_page/js/cardvalidation.js"></script>
		        
        <!-- Zabuto Calendar -->
		<script src="<?php echo base_url();?>assets/payment_page/js/zabuto_calendar.js"></script> 
                       
        <!-- EzMark -->
        <script src="<?php echo base_url();?>assets/payment_page/js/jquery.ezmark.js"></script>
        <script src="<?php echo base_url();?>assets/payment_page/js/iosOverlay.js"></script>
        <script src="<?php echo base_url();?>assets/payment_page/js/spin.min.js"></script>
        <script src="<?php echo base_url();?>assets/payment_page/js/shmart.js"></script>
        <script type="text/javascript">
			$(function() {
				$('.custom-check input').ezMark()
			});	
		</script>
        
               
        <!-- Font Awesome Markers -->
		<script src="<?php echo base_url();?>assets/payment_page/js/fontawesome-markers.js"></script>       
        
	   
	    <!-- Preloader -->
		<script>
            $(window).load(function(){
             $('.preloader-wrapper').fadeOut();
            });
        </script>       
<?php  if(isset($customer_details['authorize_user'])) {
        if($customer_details['authorize_user'] == '1' && $customer_details['customer_data'] )
        {
            if( !$customer_details['is_logged_in']) { ?>
                <script>
                    $(window).load(function(){
                        $('#myModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        })
                    });
					 $.ajax({
							type: "post",
							url: "<?php echo base_url();?>checkout/ajaxUpdate/generateOtp",
							cache: false,
							data: {mobileNo : <?php echo $mobileNo; ?>},
							success: function(data){
								// alert('success');
								// // $('#generate_otp').attr('disabled',true);
								$('#generate_otp').text('OTP sent!If not recieved click here');
								
							},
							error: function(td){
								alert('Something went wrong!Please try again');
							}
						});
                </script>
            <?php } ?>
    <?php } ?>
<?php } else {
    if($customer_details['customer_data'] )
    {
    if( !$customer_details['is_logged_in']) { ?>
    <script>
        $(window).load(function(){
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        });
		 $.ajax({
            type: "post",
            url: "<?php echo base_url();?>checkout/ajaxUpdate/generateOtp",
            cache: false,
            data: {mobileNo : <?php echo $mobileNo; ?>},
            success: function(data){
                // alert('success');
				$('#generate_otp').attr('disabled',true);
                $('#generate_otp').text('OTP sent !');
				
            },
            error: function(td){
                alert('Something went wrong!Please try again');
            }
        });
    </script>
<?php } } } ?>	
<?php if($customer_details['customer_data']) { ?>
<script>
    $('#generate_otp').click(function(){
	$(this).text('Please wait..');
        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>checkout/ajaxUpdate/generateOtp",
            cache: false,
            data: {mobileNo : <?php echo $mobileNo; ?>},
            success: function(data){
                // alert('success');
				$('#generate_otp').attr('disabled',true);
                $('#generate_otp').text('OTP Resent!');
				
            },
            error: function(td){
                alert('Something went wrong!Please try again');
            }
        });

    });
    $('#validate_otp').click(function(){
//        evt.preventDefault();
        var opts = {
            lines: 13, // The number of lines to draw
            length: 11, // The length of each line
            width: 5, // The line thickness
            radius: 17, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var target = document.createElement("div");
        document.body.appendChild(target);
        var spinner = new Spinner(opts).spin(target);

        /*Loader starts showing here*/
        var overlay = iosOverlay({
            text: "Logging you in!",
            spinner: spinner
        });
        var user_otp = $('#user_otp').val();
        var shmart_password =  $('#shmart_password').val();
        var url;
        var credentials;
        if(user_otp != '')
        {
            url = '<?php echo base_url();?>checkout/ajaxUpdate/loginUsingOtp';
            credentials =  {mobileNo : <?php echo $mobileNo; ?> , otp : user_otp}
        }
        else if (shmart_password != '')
        {
            url = '<?php echo base_url();?>checkout/ajaxUpdate/loginUsingPassword';
            credentials =  {mobileNo : <?php echo $mobileNo; ?> , password : shmart_password}
        }
        else
        {
            $('#pass_error').text("Please enter password or OTP");
            overlay.hide(); /* Once ajax call to the backend is success ajax loader is faded */
            return false;
        }
        $.ajax({
            type: "post",
            url: url,
            cache: false,
            data: credentials,
            success: function(data){
                overlay.hide();
                if(data == 1)
                {
                    overlay.update({
                        text: "Logged In!"
                    });
                    $('#myModal').modal('hide');
                }
                else
                {
//                    overlay.update({
//                        text: "Invalid Password",
//                    });
                    $('#pass_error').text("Invalid Password or OTP");
					$('#generate_otp').attr('disabled',false);
					$('#generate_otp').text('Regenerate OTP');
					
                }
            },
            error: function(td){
                alert('Something went wrong!Please try again');
                overlay.hide();
            }
        });

    });
</script>
<script>
    $(document).ready(function(){
        $(".pg_tab").click(function() {
            if($(this).hasClass('active'))
            {
                var id =  $(this).attr('id');
                $('#cardType').val(id);
            }
            else
            {
                var myId =  $(this).attr('id');
                $('#cardType').val(myId);
            }

            $('#pg_forms').fadeIn();
            var first_amount = <?php echo $total_amount ;?>;               //Get the total amount and set it as first amount (default amount without changes)
            var first_wallet_amount = <?php echo $wallet_amount ;?>;
            if(parseFloat(first_wallet_amount) > parseFloat(first_amount))
            {
                $('input:checkbox').iCheck('uncheck');
            }
            $('.cvv_blocks').fadeOut();
            $('input:radio').iCheck('uncheck');
        });
    });
</script>

<!--<script>-->
<!--    $('input:checkbox').on('ifChanged', function(event){-->
<!---->
<!--    });-->
<!--</script>-->
<script>
    $('input:checkbox[id="use_wallet"]').on('ifChanged', function(event){
        var first_amount = <?php echo $total_amount ;?>;               //Get the total amount and set it as first amount (default amount without changes)
        var first_wallet_amount = <?php echo $wallet_amount ;?>;       //Get the default wallet amount with no changes
        if($(this).is(":checked"))								//Check if the checkbox is checked (Use wallet amount or not) . If checked,wallet amount need to be counted
        {
            var shmart_refID = '<?php echo $shmart_refID; ?>';	//Get the shmart_refID from the hidden input field
            var use_wallet = 1;								//Set use_wallet as 1
            makeAjaxCall(shmart_refID, use_wallet);				//Pass shmart_refID and use_wallet to ajax to set in transaction_temp table
        } else { /* If checkbox is unchecked,set all the values back to default */
            $('#show_wallet_pay').fadeOut();
            $('#pg_forms').fadeIn();
            $('input:radio').iCheck('enable');
            var shmart_refID = '<?php echo $shmart_refID; ?>';
            var use_wallet = 0;
            makeAjaxCall(shmart_refID, use_wallet,first_amount,first_wallet_amount);  //Set in the temp_transactions table to default use_wallet,first_amount,first_wallet_amount
        }
    });
    function makeAjaxCall(shmart_refID , use_wallet, first_amount,first_wallet_amount){   //Inititates the Ajax Call
        /*
         Ajax loader animation settings
         Dependencies : iosOverlay.js and spin.min.js
         */
        var opts = {
            lines: 13, // The number of lines to draw
            length: 11, // The length of each line
            width: 5, // The line thickness
            radius: 17, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var target = document.createElement("div");
        document.body.appendChild(target);
        var spinner = new Spinner(opts).spin(target);

        /*Loader starts showing here*/
        var overlay = iosOverlay({
            text: "Loading..",
            spinner: spinner
        });
        /* Ajax call starts here */
        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>checkout/ajaxUpdate/useWallet",
            cache: false,
            data: {shmart_refID : shmart_refID, use_wallet : use_wallet},
            success: function(data){
                overlay.hide(); /* Once ajax call to the backend is success ajax loader is faded */
                if(first_amount && first_wallet_amount)											//Controls gets in if checkbox is unchecked,sets the temp_transactions table to default use_wallet,first_amount,first_wallet_amount
                {	/* Sets frontend values back to default values */
//                    $('#total_amount_show').text(first_amount);
                    $('#wallet_amount_show').text('');
                    $('input:radio').iCheck('uncheck');

                    $('.pay_button').val('Pay Rs '+first_amount);
                } else { /* Control goes in if checkbox is checked */
                    var total_amount = <?php echo $total_amount ;?>;               //Get the total amount and set it as first amount (default amount without changes)
                    var wallet_amount = <?php echo $wallet_amount ;?>;
							console.log('total_amount is'+total_amount);
							console.log('wallet_amount is'+wallet_amount);
                    if (parseFloat(total_amount) > parseFloat(wallet_amount))  //Check if total_amount greater than wallet amount
                    {
                        var net_amount = (total_amount - wallet_amount);   //Calculates the net amount of transaction which has to be done through PG
                        total_amount = net_amount.toFixed(2);          //convert to 2 decimal points
                        $('input:radio').iCheck('uncheck');
                        $('#show_wallet_pay').fadeOut();

                        /* Displays the amount in wallet and amount to be paid through PG in the front end */

                        $('#wallet_amount_show').text('- Rs '+wallet_amount);
                        $('.pay_button').val('Pay Rs '+total_amount); //Set value of pay buttons as remaning payable amount through PG
                    } else {
                        $('input:radio').iCheck('disable');
                        $('input:radio').iCheck('uncheck');
                        $('#pg_forms').fadeOut();
                        $('#show_wallet_pay').fadeIn();
                        /* Gets in if wallet_amount is greater than wallet_amount */
//                        wallet_amount = (wallet_amount - total_amount).toFixed(2); //Calculates the wallet amount after deduction
                        $('#wallet_amount_show').text('- Rs '+total_amount);
                        $('.pay_button').val('Pay Rs '+total_amount); //Set value of pay buttons as remaning payable amount through PG
                    }
                }
            },
            error: function(td){
                alert('Something went wrong!Please try again');
                overlay.hide();
            }
        });
    }
</script>
<?php } ?>
<script type="text/javascript">
    <!--our encryption function-->
    function encryptCCnumber() {
        try {
            var ccnumber = document.getElementById("ccnumber");
            var creditCard = ccnumber.value;
            if((' ' + ccnumber.className + ' ').indexOf(' ' + 'valid' + ' ') > -1)
            {
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
            else
            {
                event.preventDefault();
                alert('Please enter a valid card number');
            }



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
<script>
$("input:radio[name=saved_card_radio]").click(function() {
    var value = $(this).val();
	if($('input:radio[name=saved_card_radio]').is(':checked')) { 
		$('.saved_card_cvv').hide();
		$('#'+value).show(); 
	} else {
		$('#'+value).hide();
	} 
});
</script>
<script>
$('#use_wallet').click(function() {
	var use_wallet;
	if($('input:checkbox[id=use_wallet]').is(':checked'))
	{
	  use_wallet = '1';
	  <?php if($wallet_amount >= $total_amount) { ?>
	  $('#wallet_pay_button').fadeIn();
	  <?php } ?>
	  <?php if($wallet_amount >= $total_amount) { ?>
	  $('#lock-overlay').addClass('lock');
	  <?php } ?>
	}
	else
	{
		use_wallet = '0';
		$('#wallet_pay_button').fadeOut();
		$('#lock-overlay').removeClass('lock');
	}
	$('.use_wallet_field').val(use_wallet);
	
});
</script>
<script>
$('#save_card').click(function() {
	if($('input:checkbox[id=save_card]').is(':checked'))
	{
		$('#card_id_by_consumer_field').fadeIn();
		$('#card_id_by_consumer').attr("required",true);
	}
	else
	{
		$('#card_id_by_consumer_field').fadeOut();
		$('#card_id_by_consumer').attr("required",false);
	}
});
</script>
<script>
$('.lock').click(function() {
	$('#lock-overlay').removeClass('lock');
	$('#wallet_pay_button').fadeOut();
	$('#use_wallet').parent().removeClass('ez-checked');
	use_wallet = '0';	
	$('.use_wallet_field').val(use_wallet);
});
</script>
<script type="text/javascript">
    // window.onbeforeunload = function() {
        // return "Your transaction will be cancelled if you refresh this page.Do you want to cancel this transaction?";
    // }
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
<script>
$('#card-form').submit(function(e){
     var opts = {
            lines: 13, // The number of lines to draw
            length: 11, // The length of each line
            width: 5, // The line thickness
            radius: 17, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var target = document.createElement("div");
        document.body.appendChild(target);
        var spinner = new Spinner(opts).spin(target);

        /*Loader starts showing here*/
        var overlay = iosOverlay({
            text: "Proccessing..",
            spinner: spinner
        });
});

$('#saved_card').submit(function(e){
     var opts = {
            lines: 13, // The number of lines to draw
            length: 11, // The length of each line
            width: 5, // The line thickness
            radius: 17, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var target = document.createElement("div");
        document.body.appendChild(target);
        var spinner = new Spinner(opts).spin(target);

        /*Loader starts showing here*/
        var overlay = iosOverlay({
            text: "Processing..",
            spinner: spinner
        });
});
$('#net-form').submit(function(e){
     var opts = {
            lines: 13, // The number of lines to draw
            length: 11, // The length of each line
            width: 5, // The line thickness
            radius: 17, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var target = document.createElement("div");
        document.body.appendChild(target);
        var spinner = new Spinner(opts).spin(target);

        /*Loader starts showing here*/
        var overlay = iosOverlay({
            text: "Processing..",
            spinner: spinner
        });
});

</script>