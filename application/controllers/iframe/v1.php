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
		if(array_key_exists('email', $data)&& array_key_exists('mobileNo', $data)&&array_key_exists('total_amount', $data)&&array_key_exists('merchant_refID', $data))
		{	
			$r_data_list = array('email',
						'mobileNo',
						'total_amount',
						'merchant_refID',
						'response_url',
						'checksum_method'
						);			
			$response = $this->api_validation->inputValidation($data, $r_data_list);
			if($response)
			{
				$merchant_user_id = $this->merchant_model->getMerchantUserID(REST_API_USERNAME);
				if($this->api_validation->_checkMerchantRefID($data['merchant_refID'], $merchant_user_id))
				{
					$data['shmart_refID']		= $this->merchant_model->generateRefID();
					$data['app_used']			= 'IFRAME_CHECKOUT';
					$data['app_id']				= REST_API_USERNAME;
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
}