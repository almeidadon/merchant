<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Api_validation
{	
	function __construct()
	{
		$this->ci =& get_instance();
        $this->ci->load->library('validation');
	}
	
	function inputValidation($data, $r_data_list)																					
	{
		$this->ci->validation->set_data($data);
		$this->ci->validation->required($r_data_list, 'Mandatory field missing');
		$this->ci->validation->email('email', 'Invalid email');
		$this->ci->validation->num('mobileNo', 'Invalid mobile number');
		$this->ci->validation->minlen('mobileNo',10, 'Mobile number should be 10 digit');
		$this->ci->validation->maxlen('mobileNo',10, 'Mobile number should be 10 digit');
		$this->ci->validation->num('total_amount', 'Invalid amount value');
		$this->ci->validation->alphanum('merchant_refID', 'Invalid merchant_refID. This should be alpha numeric');
		$this->ci->validation->num('use_wallet', 'Only 1 or 0 is accepted');
		$this->ci->validation->url('response_url', 'Use proper response URL');
		return ($this->ci->validation->is_valid()) ? 1 : ($this->ci->validation->get_error_message());
	}
	
	function _checkMerchantRefID($merchant_refID, $merchant_user_id)
	{
		return $this->ci->merchant_model->isUniqueMerchantRefID($merchant_user_id, $merchant_refID);
	}
}