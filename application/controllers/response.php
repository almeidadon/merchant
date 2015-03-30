<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class Response extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        define('ESS_MERCHANT_ID','ESSIZS0003');
        $this->load->helper(array('form', 'url', 'payment_gateway_helper'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->load->library('paymentgateway');
        $this->load->library('wallet_shmart');
        $this->load->library('notification_lib');
        $this->load->library('android_push_notification');
        $this->lang->load('tank_auth');
    }

    function processorResponseHandler()
    {
		// print_r($_REQUEST);
		$response_data = $this->input->post();
        if(array_key_exists('status', $response_data)) //Transaction is coming from Essecom
        {
            /* Essecom response parameters */
            $response['status'] = $this->input->post('status'); //Status codes from essecom
            $response['processor_txnID'] = $this->input->post('ref_no'); //Essecom transaction ID
            $response['shmart_refID'] = $this->input->post('cust_ref_no'); // shmart_refID sent from shmart
            $response['amount'] = $this->input->post('amount'); //Amount of transaction
            $response['status_msg'] = $this->paymentgateway->getStatusMessage($response['status']);
            $response['merchant_mid'] = $this->input->post('MerchantId');
            $response['trans_completedTime'] = date('d/m/Y H:i:s');// Txn Date
        }
		
        else if(array_key_exists('f_code', $response_data)) //Transaction is coming from Atom
        {
            /* Atom response parameters */
            $response['status'] = $this->input->post('f_code'); //Ok for success , F for failed
            $response['processor_txnID'] = $this->input->post('mmp_txn'); // Atom txn id
            $response['shmart_refID'] = $this->input->post('mer_txn'); //shmart_refID sent from Shmart
            $response['amount'] = $this->input->post('amt'); //Amount of transaction
            $response['status_msg'] = ($response['status']=='Ok')?'Success':'Failed'; // Atom txn id
            $response['bank_txnNo'] = $this->input->post('bank_txn'); // Bank txn id
            $response['processor_name'] = 'ATOM';// Processor name
            $response['merchant_mid'] = $this->input->post('merchant_id');
            $response['trans_completedTime'] =  date('d/m/Y H:i:s');// Txn Date
        }
		$isValidTrans = $this->paymentgateway->isValidTransaction($response);
        if($isValidTrans)//Check if the transaction exists with same amount.
        {
            if($response['status'] == '0' || $response['status'] == 'Ok')
            {
                $response ['status'] = '0';
            }
            $this->paymentgateway->updateTransactionStatus($response);

            /*
             * Array has
             * mobileNo
             * email
             * custom_response_page
             * response_url
             * trans_amount_pg
             * trans_amount_wallet
             * app_used
             * merchant_refID
             * app_id
             * merchant_id
             * f_name
             * l_name
             * addr
             * city
             * state
             * country
             * zipcode
             * shipping_addr
             * shipping_city
             * shipping_state
             * shipping_country
             * shipping_zipcode
             * product_name
             * product_description
             * udf1
             * udf2
             * udf3
             * udf4
             * rurl
             * surl
             * furl
             * codurl
             * checksum_method
             * secretkey
             * */
            $transaction_data	= $this->paymentgateway->getTransactionData($response['shmart_refID']);
			$is_whitelabel		= $this->paymentgateway->isWhitelabel($transaction_data['merchant_id']);
            $response['mobileNo'] = $transaction_data['mobileNo'];
            if($transaction_data['trans_amount_wallet']==null)
            {
                $transaction_data['trans_amount_wallet'] = '0';
            }
            $transaction_data['total_amount'] = ($transaction_data['trans_amount_pg'] + $transaction_data['trans_amount_wallet']);
			$is_consumer = $this->paymentgateway->getConsumerExists($transaction_data);
			$transaction_data['status_msg'] = $response['status_msg'];
			$merchant_contact_data = $this->paymentgateway->getMerchantContactTelephone($transaction_data['merchant_id']);
			$transaction_data['merchant_email'] = 0;
			$transaction_data['merchant_telephone'] = 0;
			if($merchant_contact_data)
			{
				$merchant_contact_data_array = explode('*&&&*', $merchant_contact_data);
				$transaction_data['merchant_email']	= $merchant_contact_data_array[0];
				$transaction_data['merchant_telephone']	= $merchant_contact_data_array[1];
			}
			if(!$transaction_data['merchant_email'])
				$transaction_data['merchant_email'] = $this->paymentgateway->getMerchantContactData($transaction_data['merchant_id'], 'EMAIL');
			
			if(!$transaction_data['merchant_telephone'])
				$transaction_data['merchant_telephone'] = $this->paymentgateway->getMerchantContactData($transaction_data['merchant_id'], 'TELEPHONE');
			
			if($is_whitelabel)
				$transaction_data['merchant_website'] = $is_whitelabel['website'];
			else
				$transaction_data['merchant_website'] = $this->paymentgateway->getMerchantName($transaction_data['merchant_id']);
			
			if($transaction_data['email']==null)
			{
				$consumer_id = $this->paymentgateway->getConsumerID($response['shmart_refID']);
				$transaction_data['email'] = $this->paymentgateway->getConsumerEmail($consumer_id);
			}	
            if($is_consumer === 0)
            {
                //Pass email,mobileNo,f_name,l_name,addr,city,state,country,zipcode
                $registration_response = $this->wallet_shmart->createShmartCustomer($transaction_data);
				if($registration_response)
				{
					if($is_whitelabel)
					{
						$registration_response['website'] = $is_whitelabel['website'];
						$registration_response['brand_name'] = $is_whitelabel['brand_name'];
						$registration_response['user_id'] = $is_whitelabel['user_id'];
						$registration_response['total_amount'] = $transaction_data['total_amount'];
						$registration_response['merchant_website'] = $transaction_data['merchant_website'];
						$registration_response['merchant_refID'] = $transaction_data['merchant_refID'];
						$registration_response['transaction_mode'] = $transaction_data['transaction_mode'];
					}
					$registration_response['status'] = $response ['status'] ;
					$transaction_data['username'] = $registration_response['username'];
					$transaction_data['password'] = $registration_response['password'];
					if($response['status'] == '0')
						{
							$this->_send_email('welcome', $registration_response['email'], $transaction_data);
						} else {
							$this->_send_email('failed', $registration_response['email'], $transaction_data);
						}
					unset($transaction_data['username']);
					unset($transaction_data['password']);
					$this->notification_lib->signUpNotify($registration_response);					
					$this->paymentgateway->updateSavedCardsForConsumer($transaction_data['mobileNo']);
				}
            } else {
				$transaction_data['status'] =  $response ['status'];
				$transaction_data['shmart_refID']  = $response['shmart_refID'] ;
				$this->_send_email('transaction_email_merchant', $transaction_data['merchant_email'], $transaction_data);
				$this->_send_email('transaction_email_customer', $transaction_data['email'], $transaction_data);
				unset($transaction_data['shmart_refID'] );
			}
            if($response['status'] == '0')
            {
               //$this->paymentgateway->setSettlementData($response);
               $this->paymentgateway->updateTransParams($transaction_data);
			   $transaction_data['merchant_website'] = $this->paymentgateway->getMerchantName($transaction_data['merchant_id']);
				$this->notification_lib->transactionConfirmationSMS($transaction_data);
            // }
            // else {
            // //Failed
               $is_wallet_used = $this->paymentgateway->isWalletUsed($response['shmart_refID']);
               if($is_wallet_used)
               {
                    //$this->paymentgateway->creditBackToWallet($response);					
					$temp_data['total_amount_to_be_charged_from_wallet'] = $transaction_data['trans_amount_wallet'];
					$temp_data['shmart_refID'] = $response['shmart_refID'];
					$temp_data['merchant_id'] = $transaction_data['merchant_id'];
					$temp_data['mobileNo'] = $transaction_data['mobileNo'];
					$wallet_transaction_status = $this->paymentgateway->chargeWallet($temp_data);
					$this->paymentgateway->updateTransactionAsHybrid($response['shmart_refID']);
					$wallet_transaction_status = $this->paymentgateway->updateWalletMainTransactionStatus($response['shmart_refID'], $wallet_transaction_status);
					if($wallet_transaction_status == '0')
						{
							$this->paymentgateway->setSettlementData($response);
						}
					else	
						{
						$response['status'] = '-1000'; // Wallet failed and PG Success
						$response['status_msg'] = 'Failed'; // Wallet failed and PG Success
						}
               }
			   else
				   $this->paymentgateway->setSettlementData($response);
            }
            $response['total_amount'] = $transaction_data['total_amount'];
            $this->processResponseUrl($response,$transaction_data);
        }
        else
        {
            echo "Cannot process due to data mismatch";
        }
    }
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $data['merchant_website']));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		// $this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

    /*
     * Wallet response to be sent to merchant for wallet only transactions*/
    function walletResponseHandler()
    {
        $shmart_refID = $this->uri->segment(3);
        $transaction_data = $this->paymentgateway->getTransactionData($shmart_refID);
        /* Get all transactions data from transactios_wallet */
        $get_wallet_response = $this->paymentgateway->getWalletTransactionData($shmart_refID);
        $response['status'] = $get_wallet_response ['wallet_trans_status'];
        $response['shmart_refID'] = $shmart_refID;
        $response['trans_general_wallet_amount'] = $get_wallet_response ['trans_general_wallet_amount'];
        $response['trans_voucher_wallet_amount'] = $get_wallet_response ['trans_voucher_wallet_amount'];
        $response['voucher_wallet_txnNo'] = $get_wallet_response ['voucher_wallet_txnNo'];
        $response['general_wallet_txnNo'] = $get_wallet_response ['general_wallet_txnNo'];
        $response['wallet_trans_type'] = $get_wallet_response ['wallet_trans_type'];
        if($response['trans_voucher_wallet_amount']==null)
        {
            $response['trans_voucher_wallet_amount'] = '0';
        }
        $response['total_amount'] = ($response['trans_general_wallet_amount'] + $response['trans_voucher_wallet_amount']);
        $this->processResponseUrl($response,$transaction_data);
    }

    function cancelResponseHandler()
    {
        $response['shmart_refID'] = $this->uri->segment(3);
        $response['status'] = '-1';
        $response['status_msg'] = 'Cancelled by user';
        $this->paymentgateway->updateTransactionAsCancelled($response['shmart_refID']);
        $transaction_data = $this->paymentgateway->getTransactionData($response['shmart_refID']);
        $response['total_amount'] = $transaction_data['total_amount'];
        $this->processResponseUrl($response,$transaction_data);
    }
	    function lowAmountResponseHandler()
    {
        $response['shmart_refID'] = $this->uri->segment(3);
        $response['status'] = '-1';
        $response['status_msg'] = 'Minimum amount of transaction is Rs 10';
        $this->paymentgateway->updateTransactionAsCancelled($response['shmart_refID']);
        $transaction_data = $this->paymentgateway->getTransactionData($response['shmart_refID']);
        $response['total_amount'] = $transaction_data['total_amount'];
        $this->processResponseUrl($response,$transaction_data);
    }

    function processResponseUrl($response,$transaction_data)
    {
        if($transaction_data['app_used'] == 'INVOIZ'|| $transaction_data['app_used'] == 'COLLECT')
        {
            if($response['status'] == '0')
            {
                $payment_status = 'PC';
            }
            else
            {
                $payment_status = 'PF';
            }
            $this->paymentgateway->updateAppPaymentStatus($transaction_data['app_used'],$transaction_data['app_id'],$payment_status);
			if( $transaction_data['app_used'] == 'COLLECT')
				{
					$response['trans_completedTime'] =  date('d/m/Y H:i:s');
					$c_data = $this->paymentgateway->isValidCollctId($transaction_data['app_id']);
					$transaction_data['request_channel'] = $c_data['email_or_mobileNo'];
					$this->android_push_notification->transaction_alert_merchant($response,$transaction_data);
				}
			//Push notification to mobile app
			
        }
        $generate_checksum['checksum_method'] = $transaction_data['checksum_method'];
        $generate_checksum['input_string'] = $response['status']."|".$response['shmart_refID']."|".$transaction_data['merchant_id']."|".$response['total_amount'].'|'.$generate_checksum['checksum_method'].'|'.$transaction_data['app_id'].'|'.$transaction_data['app_used'];
        $generate_checksum['secretkey'] = '0';
        if(isset($transaction_data['secretkey']))
        {
            $generate_checksum['secretkey'] =  $transaction_data['secretkey'];
        }
        $response['merchant_checksum'] = $this->paymentgateway->generateChecksum($generate_checksum);

        if($transaction_data['custom_response_page'])
        {
            if($response['status'] == '0')
            {
                //Redirect to merchant and show response on merchant side
                if( $transaction_data['surl'] )
                {
                    $transaction_data['posturl'] = $transaction_data['surl'];
                }
            }
            else
            {
                if( $transaction_data['furl'] )
                {

                    //POST response to $transaction_data['furl']
                    $transaction_data['posturl'] = $transaction_data['furl'];
                }
            }
            if(!isset($transaction_data['posturl']) || ($transaction_data['posturl']==null))
            {
                if ($transaction_data['rurl'])
                {
                    //POST response to $transaction_data['rurl']
                    $transaction_data['posturl'] = $transaction_data['rurl'];  //RESPONSE URL SENT IN API REQ
                } else {
					$transaction_data['posturl'] = $transaction_data['response_url']; //RESPONSE URL GOT FROM DATABASE (PAYMENT APPS)
				}
                //POST response to $transaction_data['response_url']
                
            }
            $this->postResponse($response,$transaction_data);
        } else {
            $response['merchant_refID'] = $transaction_data['merchant_refID'];
            $this->load->view('response_page/header.php');
            $this->load->view('response_page/body.php',$response);
            $this->load->view('response_page/footer.php');
        }

    }

    function postResponse($response,$transaction_data)
    {
        $required_data       = array(
            'status',
            'status_msg',
            'shmart_refID',
            'total_amount',
            'trans_completedTime',
            'merchant_checksum'
        );
        $response_redirect_data    = array_intersect_key($response, array_flip($required_data));
        $required_data_extra       = array(
            'merchant_refID',
            'trans_amount_pg',
            'trans_amount_wallet',
            'app_used',
            'merchant_id',
            'transaction_mode',
            'cardType',
            'cardProvider',
            'f_name',
            'addr',
            'city',
            'state',
            'country',
            'zipcode',
            'email',
            'mobileNo',
            'l_name',
            'shipping_addr',
            'shipping_city',
            'shipping_state',
            'shipping_zipcode',
            'shipping_email',
            'shipping_mobileNo',
            'udf1',
            'udf2',
            'udf3',
            'udf4',
            'checksum_method'
        );
        $response_redirect_data_extra            = array_intersect_key($transaction_data, array_flip($required_data_extra));

        $input_fields = '';
        foreach ($response_redirect_data as $key => $value)
        {
            $input_fields .= '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($value).'">';
        }
        foreach ($response_redirect_data_extra as $key => $value)
        {
            if($value!=null)
            {
                $input_fields .= '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($value).'">';
            }
        }
		$redirect_merchant['posturl'] = $transaction_data['posturl'];
		$redirect_merchant['input_fields'] = $input_fields;
		$this->load->view('redirect_merchant',$redirect_merchant);
    }
}