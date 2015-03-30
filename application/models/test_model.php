<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Test_model extends CI_Model
{
	private $otp_table = 'transactions_generated_otps';
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$ci =& get_instance();
		$this->otp_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->otp_table;
	}
	
	function validateOtp()
	{
	
	$query = $this->db->query("call validateOtp(9400429941,280928)");

    return $query->result();
	
	
	
	
	
		// $query = $this->db->'CALL validateOtp(9400429941,280928)';
		// if($query->num_rows()>0)
		// {
			// foreach($query->result() as $row)
				// if($otp_data['otp'] == $row->otp)
				// {
					// $this->db->delete($this->otp_table, array('mobileNo' => $otp_data['mobileNo']));
					// return 1;
				// }
				// return 0;
		// }
	}
}