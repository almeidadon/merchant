<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Response_iframe extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        define('ESS_MERCHANT_ID', 'ESSIZS0003');
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
            $response['status'] = $this->input->post('f_code'); //Ok for success, F for failed
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
            if($response['status'] == 'Ok')
				$response ['status'] = '0';
            $this->paymentgateway->updateTransactionStatus($response);
            /*
             * Array has
             * mobileNo
             * email
             * response_url
             * trans_amount_pg
             * app_used
             * merchant_refID
             * app_id
             * merchant_id
             * */
            $transaction_data	= $this->paymentgateway->getIframeTransactionData($response['shmart_refID']);
            $response['mobileNo'] = $transaction_data['mobileNo'];
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
			
			$transaction_data['merchant_website'] = $this->paymentgateway->getMerchantName($transaction_data['merchant_id']);
			$transaction_data['status'] =  $response ['status'];
			$transaction_data['shmart_refID']  = $response['shmart_refID'] ;
			$this->_send_email('transaction_email_merchant', $transaction_data['merchant_email'], $transaction_data);
			$this->_send_email('transaction_email_customer', $transaction_data['email'], $transaction_data);
			unset($transaction_data['shmart_refID'] );
            if($response['status'] == '0')
            {
				$this->paymentgateway->updateTransParams($transaction_data);
				$transaction_data['merchant_website'] = $this->paymentgateway->getMerchantName($transaction_data['merchant_id']);
				$this->notification_lib->transactionConfirmationSMS($transaction_data);
				$this->paymentgateway->setSettlementData($response);
            }
            $response['total_amount'] = $transaction_data['total_amount'];
            $this->processResponseUrl($response,$transaction_data);
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
		$this->email->send();
	}

    function processResponseUrl($response, $transaction_data)
    {
			$generate_checksum['checksum_method']	= $transaction_data['checksum_method'];
			$generate_checksum['input_string']		= $response['status']."|".$response['shmart_refID']."|".$transaction_data['merchant_id']."|".$response['total_amount'].'|'.$generate_checksum['checksum_method'].'|'.$transaction_data['app_id'].'|'.$transaction_data['app_used'];
			$generate_checksum['secretkey']			= $transaction_data['secretkey'];
			$response['merchant_checksum']			= $this->paymentgateway->generateChecksum($generate_checksum);

			$transaction_data['posturl'] = $transaction_data['response_url']; //RESPONSE URL GOT FROM DATABASE (PAYMENT APPS)
            $this->postResponse($response,$transaction_data);
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
            'app_used',
            'merchant_id',
            'transaction_mode',
            'cardType',
            'cardProvider',
            'email',
            'mobileNo',
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
           $input_fields .= '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($value).'">';
        }
		$redirect_merchant['posturl'] = $transaction_data['posturl'];
		$redirect_merchant['input_fields'] = $input_fields;
		$this->load->view('redirect_merchant',$redirect_merchant);
    }
}