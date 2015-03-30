<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Logging_model extends CI_Model
{	
	private $js_log_table				 	= 'logging_api_request_js';
	private $pay_by_shmart_log_table	 	= 'logging_api_request_pay_by_shmart';
	private $pay_via_stored_card_log_table 	= 'logging_api_request_pay_via_stored_card';
	function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$CI->logging = $this->load->database('logging', TRUE); //Uses the logging database defined in the database.php
		$this->js_log_table 					= $CI->config->item('db_table_prefix', 'paymentgateway').$this->js_log_table;
		$this->pay_by_shmart_log_table 			= $CI->config->item('db_table_prefix', 'paymentgateway').$this->pay_by_shmart_log_table;
		$this->pay_via_stored_card_log_table 	= $CI->config->item('db_table_prefix', 'paymentgateway').$this->pay_via_stored_card_log_table;
	}
	/*Logs the tokenex responses to the logging database @logging_tokenex table*/
	function tokenexLogging($tokenex_data)
	{
		$tokenex_data['token_creationTime'] = date('Y-m-d H:i:s');
		return ($this->logging->insert('logging_tokenex', $tokenex_data))?1:0;
	}
	function smsLogging($sms_data)
	{
		$tokenex_data['token_creationTime'] = date('Y-m-d H:i:s');
		return ($this->logging->insert('logging_sms', $sms_data))?1:0;
	}
    function emailLogging($email_data)
    {
        $email_data['email_creationTime'] = date('Y-m-d H:i:s');
        return ($this->logging->insert('logging_email', $email_data))?1:0;
    }
    function logWalletBalance($data)
    {
        return ($this->logging->insert('logging_wallet_balance', $data))?1:0;
    }
    function logLoginSession($data)
    {
        return ($this->logging->insert('logging_wallet_login', $data))?1:0;
    }
    function walletRegistrationLogging($data)
    {
        $required_data       = array(
            'TransactionRefNo',
            'PartnerRefNo',
            'AckNo',
            'ResponseCode',
            'ResponseMessage',
            'mobileNo'
        );
        $registration_data            = array_intersect_key($data, array_flip($required_data));
        $registration_data['registration_creationTime'] = date('Y-m-d H:i:s');
       return ($this->logging->insert('logging_wallet_registration', $registration_data))?1:0;
    }
    function updateCustomerLogging($data)
    {
        $data['updatecustomer_creationTime'] = date('Y-m-d H:i:s');
        return ($this->logging->insert('logging_wallet_updatecustomer', $data))?1:0;
    }
    function logWalletTransaction($data)
    {
        $data['transaction_creationTime'] = date('Y-m-d H:i:s');
        return ($this->logging->insert('logging_wallet_transactions', $data))?1:0;
    }
    function logWalletToWalletTransReq($data)
    {
        $data['creationTime'] = date('Y-m-d H:i:s');
        return ($this->logging->insert('logging_wallet_wallet_transactions', $data))?1:0;
    }
    function logWalletAccountAction($data)
    {
        return ($this->logging->insert('logging_wallet_account_action_request', $data))?1:0;
    }
    function logWalletBeneficiaryReq($data)
    {
        return ($this->logging->insert('logging_wallet_beneficiary_reg_req', $data))?1:0;
    }
    function logWalletBeneficiaryDeactivate($data)
    {
        $data['creationTime'] = date('Y-m-d H:i:s');
        return ($this->logging->insert('logging_wallet_beneficiary_deactivate_req', $data))?1:0;
    }
    function logWalletRemittanceTransReq($data)
    {
        $data['creationTime'] = date('Y-m-d H:i:s');
        return ($this->logging->insert('logging_wallet_remittance_trans_req', $data))?1:0;
    }
	function logApiRequest($log_request_data)
	{
		if($log_request_data['app_used']=='JS')
			$table_name = $this->js_log_table;
		else if($log_request_data['app_used']=='PAY_BY_SHMART')
			$table_name = $this->pay_by_shmart_log_table;
		else if($log_request_data['app_used']=='PAY_VIA_STORED_CARD')
			$table_name = $this->pay_via_stored_card_log_table;
		unset($log_request_data['app_used']);
		$log_request_data['creationTime'] = date('Y-m-d H:i:s');
		return ($this->logging->insert($table_name, $log_request_data))?1:0;
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */