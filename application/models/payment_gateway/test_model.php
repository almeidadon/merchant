<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Payment_gateway_model extends CI_Model
{
	private $otp_table = 'transactions_generated_otps';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$ci =& get_instance();
		$this->otp_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->otp_table;
	}
	

}