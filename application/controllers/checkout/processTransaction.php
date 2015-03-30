<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class ProcessTransaction extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper( array('url', 'payment_gateway_helper', 'basic_helper') );
		$this->load->library('paymentgateway');
		$this->load->library('wallet_shmart');
		$this->load->library('notification_lib');
		$this->load->library('encrypt');
		$this->load->library('form_validation');
        $this->ci =& get_instance();
        $this->ci->load->model('payment_gateway/logging_model');
        $this->ci->load->model('payment_gateway/payment_gateway_model');
        $this->ci->load->model('payment_gateway/consumer_model');
	}
	
	function cards()
	{
		// $this->output->enable_profiler(TRUE);
		$shmart_refID = $card_info['shmart_refID'] = $this->input->post('shmart_refID');		
		$isNewTransaction = $this->payment_gateway_model->isNewTransaction($shmart_refID);
		if($isNewTransaction)
		{
			$temp_data = $this->paymentgateway->getTempTransactionData($shmart_refID);
			$temp_data['merchant_id'] 	= $this->input->post('merchant_id');
			$card_info['email']			= $temp_data['email'];
			$card_info['mobileNo']			= $temp_data['mobileNo'];
			if($this->input->post('email'))
			{
			   $card_info['email']	= $this->input->post('email');
			}
			if($this->input->post('mobileNo'))
			{
				$card_info['mobileNo']	= $this->input->post('mobileNo');
			}
			$card_info['app_used']		= $temp_data['app_used'];
			$card_info['app_id']		= $temp_data['app_id'];
			$card_info['merchant_refID']= $temp_data['merchant_refID'];
			$card_info['ip_address']	= $temp_data['ip_address'];
			$card_info['user_id']		= $temp_data['user_id'];
			$is_saved_card=0;
			$wallet_transaction_status=1;
			$card_info['processor']		= 'ESS';
			$temp_data['use_wallet'] = $this->input->post('use_wallet');  // EXTRA ADDED
			$this->paymentgateway->setUseWallet($temp_data['use_wallet'],$shmart_refID); //EXTRA ADDED
			if(($temp_data['use_wallet'] === "1") && ($temp_data['wallet_amount'] >= $temp_data['total_amount'])) //Pure Wallet
			{
				$temp_data['total_amount_to_be_charged_from_wallet'] = $temp_data['total_amount'];
				$card_info['trans_amount_wallet'] = number_format($temp_data['total_amount_to_be_charged_from_wallet'], 2);
				$card_info['transaction_mode'] = 'W';
				$wallet_transaction_status = $this->paymentgateway->chargeWallet($temp_data);
				$card_info['consumer_id'] = $this->tank_auth->get_user_id();
				$this->ci->payment_gateway_model->setMainTransactionData($card_info); // Set Main Transaction datas
				redirect('index.php/response/walletResponseHandler/'.$shmart_refID );
			}
			else //Either Pure PG or Hybrid
			{
				$card_info['user_card_unique_token']	= $this->input->post('user_card_unique_token');
				$card_info['cvv']		 				= $this->input->post('cvv');
				$card_info['cardType']					= $this->input->post('cardType');
				if(isset($card_info['cardType']) && ($card_info['cardType']=='NB'))
				{
					$card_info['name_on_card']	= $this->input->post('name');
					$bank_id 					= $this->input->post('BankId');
					$bank_id_array 				= explode('||', $bank_id);
					$card_info['BankId']		= $card_info['cardProvider'] = $bank_id_array[0];
					$card_info['processor']		= $bank_id_array[1];
				}
				else
				{
					if($card_info['user_card_unique_token']!=null)
					{
						$is_saved_card=1;
						$saved_card_info = $this->consumer_model->getSavedCardDataWithUniqueToken($card_info['user_card_unique_token']);
						$card_info['cardType'] 			= $saved_card_info['cardType'];
						$card_info['token'] 			= $saved_card_info['token'];
						$card_info['cardProvider'] 		= $saved_card_info['cardProvider'];
						$card_info['cardExpiryMonth'] 	= $saved_card_info['cardExpiryMonth'];
						$card_info['cardExpiryYear'] 	= $saved_card_info['cardExpiryYear'];
						$card_info['name_on_card'] 		= $saved_card_info['name_on_card'];
					}
					else
					{
						$card_info['encrypted_data'] 	= $this->input->post('shmart_cipherText');
						$card_info['token'] 			= $this->paymentgateway->generateToken($card_info);
						$card_info['cardExpiryMonth'] 	= $this->input->post('cardExpiryMonth');
						$card_info['cardExpiryYear'] 	= $this->input->post('cardExpiryYear');
						$card_info['name_on_card'] 		= $this->input->post('name_on_card');
						$card_info['cardProvider'] 		= $this->input->post('ccType');
						$card_info['save_card'] 		= $this->input->post('save_card');
						if($card_info['save_card']=='1')
						{
							$card_info['card_id_by_consumer']		= $this->input->post('card_id_by_consumer');
							$card_info['user_card_unique_token']	= $this->consumer_model->generateUniqueTokenForCard();
							$this->consumer_model->setNewSavedCardData($card_info);
						}
					}
					switch ($card_info['cardProvider'])
					{
						case 'visa':
							$card_info['cardProvider'] 		= 'VISA';
							break;
						case 'visa_electron':
							$card_info['cardProvider'] 		= 'SBIME';
							// $card_info['cardExpiryMonth'] 	= '12';
							// $card_info['cardExpiryYear'] 	= '2020';
							break;
						case 'maestro':
							$card_info['cardProvider'] 		= 'MAEST';
							// $card_info['cardExpiryMonth'] 	= '12';
							// $card_info['cardExpiryYear'] 	= '2020';
							break;
						case 'mastercard':
							$card_info['cardProvider'] 		= 'MC';
							break;
					}
				}
				if($temp_data['use_wallet']=="0") //Pure PG transaction
				{
					$card_info['transaction_mode']		= 'PG';
					$card_info['trans_amount_pg'] 		= $card_info['pg_amount'] = number_format($temp_data['total_amount'], 2 , '.' , '');
					$card_info['trans_amount_wallet'] 	= '';
				}
				else //Hybrid transaction
				{
					$card_info['transaction_mode'] 		= 'HY';
					$card_info['trans_amount_pg'] 		= $card_info['pg_amount'] = number_format(($temp_data['total_amount'] - $temp_data['wallet_amount']), 2,'.','');
					$temp_data['total_amount_to_be_charged_from_wallet'] = $temp_data['wallet_amount'];
					$card_info['trans_amount_wallet'] 	= number_format($temp_data['total_amount_to_be_charged_from_wallet'], 2);
					//$wallet_transaction_status = $this->paymentgateway->chargeWallet($temp_data);
				}
				$this->ci->payment_gateway_model->setPgTransactionData($card_info); // Set PG Transaction datas

				if($card_info['transaction_mode'] == 'HY')
					$this->ci->payment_gateway_model->updateTransactionAsHybrid($shmart_refID);
				if($is_saved_card==1)
					$this->ci->payment_gateway_model->updateTransactionAsSavedCard($shmart_refID);
				
				//if(($wallet_transaction_status!=0) && ($card_info['cardType']!= 'NB'))
				if($card_info['cardType']!= 'NB')
					{
					// $this->paymentgateway->paymentProcessingEssecomCards($card_info);
					$card_info['merchant_id'] = 'ESS_SAND';
					$card_info['status_data'] = $this->payment_gateway_model->get_distinct_trans_status();
					$this->load->view('3dSecure_page/header');
					$this->load->view('3dSecure_page/body',$card_info);
					$this->load->view('3dSecure_page/footer');
					}
				//else if(($wallet_transaction_status!=0) && ($card_info['cardType']== 'NB'))
				else if($card_info['cardType']== 'NB')
				{
					if((($card_info['processor']=='ESS') && ($card_info['pg_amount']>='10.00')) || (($card_info['processor']=='ATOM') && ($card_info['pg_amount']>='5.00')))
						{
							// $this->paymentgateway->paymentProcessingNB($card_info);
							$card_info['merchant_id'] = 'ESS_SAND';
							$card_info['status_data'] = $this->payment_gateway_model->get_distinct_trans_status();
							$this->load->view('3dSecure_page/header');
							$this->load->view('3dSecure_page/body',$card_info);
							$this->load->view('3dSecure_page/footer');
						}
					else
						{
						// echo "should not be allowed to transact";
						redirect( base_url().'index.php/response/lowAmountResponseHandler/'.$shmart_refID );
						}
				}
			}
			$card_info['consumer_id'] = $this->tank_auth->get_user_id();
			$this->ci->payment_gateway_model->setMainTransactionData($card_info); // Set Main Transaction datas
		}
		else
		{
			echo "Your session has been expired! Please initiate a new transaction.";
		}
    }
}