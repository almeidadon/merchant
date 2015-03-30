<?php defined('BASEPATH') OR exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require APPPATH.'/libraries/REST_Controller.php';
class v1 extends REST_Controller
{
	function __construct()
    {
        parent::__construct();
        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
		define('REST_API_USERNAME',$_SERVER['PHP_AUTH_USER']);
    }
	
    function createorder_post()
    {
        $data = json_decode(file_get_contents('php://input'), true);
		if(array_key_exists('email', $data)&& array_key_exists('mobileNo', $data)&&array_key_exists('total_amount', $data)&&array_key_exists('merchant_id', $data))
		{	
			$r_data_list = array('email',
						'mobileNo',
						'total_amount',
						'merchant_id',
						'response_url',
						'checksum_method'
						);
			$data['response_url'] = 'https://google.com';
			$data['merchant_refID']		= $this->merchant_model->generateRefID();
			$response = $this->api_validation->inputValidation($data, $r_data_list);
			if($response)
			{
				$merchant_user_id = $this->merchant_model->getMerchantUserIDbyMerchantID($data['merchant_id']);
				unset($data['merchant_id']);
				if($this->api_validation->_checkMerchantRefID($data['merchant_refID'], $merchant_user_id))
				{
					$data['shmart_refID']		= $this->merchant_model->generateRefID();
					$data['app_used']			= 'CONSUMER_PAY_AT_MERCHANT_APP';
					$data['app_id']				= 'TEST';
					$data['ip_address']			= $_SERVER['SERVER_ADDR'];
					$data['user_id']			= $merchant_user_id;
					$set_temp_data				= $this->merchant_model->setTransactionData($data, 'TEMP');
					if($set_temp_data)
					{
						//Main transaction table data
						$data['transaction_mode']	= 'PG';
						$data['trans_amount_pg']	= $data['total_amount'];
						$this->merchant_model->setTransactionData($data, 'MAIN');
						$merchant_id				= $this->merchant_model->getMerchantID($merchant_user_id);
						$api_response				= array(
														'status'		=>'Success',
														'message'		=>'Order created successfully',
														'merchant_refID'=>$data['merchant_refID']
														);
						$this->response($api_response, 201);
					}
					else
					{
						$api_response				= array(
														'status'		=>'Error',
														'message'		=>'Cannot create order',
														'error_code'	=>'101'
														);
						$this->response($api_response, 409);
					}
				}
				else
				{
					$api_response = array(
									'status'			=>'Error',
									'message'			=>'Duplicate Merchant RefID',
									'error_code'		=>'111'
									);
					$this->response($api_response, 400);
				}
			}
			else
			{
				$api_response = array(
								'status'			=>'Error',
								'message'			=>$response,
								'error_code'		=>'110'
								);
				$this->response($api_response, 400);
			}
		}
		else
		{
			$api_response = array(
							'status'			=>'Error',
							'message'			=>'Parameter missing',
							'error_code'		=>'112'
							);
			$this->response($api_response, 400);
		}
    }
	function pay_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$r_data = array('merchant_refID',
						'charge_type');
		$response = $this->api_validation->incomingDataValidation($data,$r_data);
		$merchant_user_id = $this->merchant_model->getMerchantUserIDbyMerchantID($data['merchant_id']);
		$order_data = $this->merchant_model->getOrderData($data['merchant_refID'],$merchant_user_id);
		/* 
		email
		mobileNo
		shmart_refID
		total_amount
		seller_id
		buyer_id
		*/
		$order_data['merchant_refID'] = $data['merchant_refID'];
		$order_data['user_id'] = $merchant_user_id;
		$order_data['total_amount_to_be_charged_from_wallet'] = $order_data['total_amount'];
		$order_data['merchant_id'] = $data['merchant_id'];
		$order_data['app_id'] = REST_API_USERNAME ;
		$order_data['app_used'] = 'IFRAME_CHECKOUT' ;
		$order_data['ip_address'] = '' ;
		$order_data['consumer_id'] = $order_data['buyer_id'] ;
	    $temp_var        = array(
            'shmart_refID',
            'email',
            'mobileNo',
            'total_amount',
            'merchant_id',
            'app_id',
            'app_used',
            'merchant_refID',
            'ip_address',
            'user_id',
			'consumer_id'
        );
        $temp_trans_data = array_intersect_key($order_data, array_flip($temp_var));
        $this->paymentgateway->setInitiateMainTransactionData($temp_trans_data);
		if ($response == '1')
		{
			if($data['charge_type'] == 'W')
			{
				$card_info['app_used']		= $temp_trans_data['app_used'];
				$card_info['app_id']		= $temp_trans_data['app_id'];
				$card_info['merchant_refID']= $temp_trans_data['merchant_refID'];
				$card_info['ip_address']	= $temp_trans_data['ip_address'];
				$card_info['user_id']		= $temp_trans_data['user_id'];
				$card_info['processor']		= 'ESS';
				$temp_trans_data['use_wallet'] = '1';  // Pure wallet
				$this->paymentgateway->setUseWallet($temp_trans_data['use_wallet'],$order_data['shmart_refID']); //EXTRA ADDED
				$temp_trans_data['total_amount_to_be_charged_from_wallet'] = $temp_trans_data['total_amount'];
				$card_info['trans_amount_wallet'] = number_format($temp_trans_data['total_amount_to_be_charged_from_wallet'], 2,'.','');
				$card_info['transaction_mode'] = 'W';
				$w_debit = $this->_charge_wallet($order_data);
				if($w_debit['ResponseCode']=='0')
				{
					$api_response = array(
										'status'=>'success',
										'message'=>'Wallet debited successfully',
										'amount'=>$card_info['trans_amount_wallet'],
										'merchant_refID'=>$card_info['merchant_refID'],
										'shmart_refID'=> $order_data['shmart_refID'],
										'charge_type'=> $data['charge_type']
									);
					$this->response($api_response, 200);
				} else {
					$api_response = array(
										'status'=>'error',
										'error_code'=>$w_debit['ResponseCode'],
										'message'=>$w_debit['ResponseMessage'],
										'amount'=>$card_info['trans_amount_wallet'],
										'merchant_refID'=>$card_info['merchant_refID'],
										'shmart_refID'=> $order_data['shmart_refID'],
										'charge_type'=> $data['charge_type']
									);
					$this->response($api_response, 400);
				}
			} 
			else if ($data['charge_type'] == 'PG')
			{
				$card_info['app_used']		= $temp_trans_data['app_used'];
				$card_info['app_id']		= $temp_trans_data['app_id'];
				$card_info['merchant_refID']= $temp_trans_data['merchant_refID'];
				$card_info['ip_address']	= $temp_trans_data['ip_address'];
				$card_info['user_id']		= $temp_trans_data['user_id'];
				// $card_info['processor']		= 'ESS';
				$temp_trans_data['use_wallet'] = '0';  // Pure wallet
				$this->paymentgateway->setUseWallet($temp_trans_data['use_wallet'],$order_data['shmart_refID']); //EXTRA ADDED
				$temp_trans_data['total_amount_to_be_charged_from_wallet'] = $temp_trans_data['total_amount'];
				$card_info['trans_amount_wallet'] = number_format($temp_trans_data['total_amount_to_be_charged_from_wallet'], 2,'.','');
				$card_info['transaction_mode'] = 'PG';
				$this->_charge_pg_wallet($data);
			}
		}
	}
	function _charge_wallet($data)
	{
		unset($data['mobileNo']); // Unset the mobile nuber coming from request
		$data['mobileNo'] = $this->consumer_model->getCustomerMobileNo($data['buyer_id']);
		$data['transaction_mode'] = 'W';
		$data['trans_amount_pg'] = '0';
		$data['trans_amount_wallet'] = $data['total_amount'];
		$data['mobileNo'] = $this->consumer_model->getCustomerMobileNo($data['buyer_id']);
		$w_response = $this->paymentgateway->chargeWallet($data);
		$this->payment_gateway_model->setMainTransactionData($data);
		return $w_response;
	}
	function _charge_pg_wallet($data)
	{
		$this->merchant_model->updateMarketplaceData($data['merchant_refID'], $merchant_user_id, $data['rurl']);
		if($data['charge_type'] == 'PG')
			{
				  $temp_var        = array(
					'shmart_refID',
					'email',
					'mobileNo',
					'total_amount',
					'merchant_id',
					'app_id',
					'app_used',
					'merchant_refID',
					'ip_address',
				);
				$temp_trans_data = array_intersect_key($request_data, array_flip($temp_var));
				$this->paymentgateway->setTempTransactionData($temp_trans_data);
				$this->paymentgateway->setInitiateMainTransactionData($temp_trans_data);
			}
			else if($data['charge_type'] == 'HY')
			{
				//Add data into PG related table , set use_wallet as 1, give back payment link
			}
	
	}
}