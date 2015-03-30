<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Merchant_model extends CI_Model
{
	private $refID_unique_table			= 'processor_ref_no_unique';
	private $developer_api_rest_api		= 'developer_api_rest_apis';
	private $main_transactions_table	= 'transactions';
	private $temp_transaction_table		= 'transactions_temp';
	private $iframe_rurl_transaction_table		= 'transactions_iframe_rurl';
	private $merchant_profile_table		= 'merchant_profile';
	
    function __construct()
    {
        parent::__construct();
		$ci =& get_instance();
		$ci->merchant					= $this->load->database('default', TRUE);
		$this->refID_unique_table		= $ci->config->item('db_table_prefix', 'paymentgateway').$this->refID_unique_table;
		$this->developer_api_rest_api	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->developer_api_rest_api;
		$this->main_transactions_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->main_transactions_table;
		$this->temp_transaction_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->temp_transaction_table;
		$this->iframe_rurl_transaction_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->iframe_rurl_transaction_table;
		$this->merchant_profile_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->merchant_profile_table;
    }
	
	function generateRefID()
    {
        $cnt=0;
        do
        {
            $refID_data['shmart_refID'] = 'IF'.mt_rand(100000,999999999999).'RM';
            $query = $this->merchant->get_where($this->refID_unique_table, array('shmart_refID'=>$refID_data['shmart_refID']));
            if($query->num_rows() == 0)
			{
				$this->merchant->insert($this->refID_unique_table, $refID_data);
				if($this->merchant->affected_rows() > 0)
					return $refID_data['shmart_refID'];
			}
        }while($cnt==0);
    }
    	
	function getRestCredentials($username, $password)
	{
		$this->merchant->select('password');
		$query = $this->merchant->get_where($this->developer_api_rest_api, array('username'=>$username));
		if($query->num_rows()>0)
		{
			foreach($query->result() as $row)
			{
				$password_from_table 	= $row->password;
				$password_string 		= $this->encrypt->decode($password_from_table);
				return ($password_string == $password) ? 1 : 0;
			}
		}
		return 0;
	}
	
	function isUniqueMerchantRefID($merchant_user_id, $merchant_refID)
	{
		$query = $this->merchant->get_where($this->main_transactions_table, array('user_id'=>$merchant_user_id, 'merchant_refID'=>$merchant_refID));
		return ($query->num_rows() > 0) ? 0 : 1;
	}
	
	function getMerchantUserID($username)
	{
		$this->merchant->select('user_id');
		$query = $this->merchant->get_where($this->developer_api_rest_api, array('username'=>$username));
		foreach($query->result() as $row)
			return $row->user_id;
		return 0;
	}
	function getMerchantUserIDbyMerchantID($merchant_id)
	{
		$this->merchant->select('user_id');
		$query = $this->merchant->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
		foreach($query->result() as $row)
			return $row->user_id;
		return 0;
	}
	
	function setTransactionData($temp_trans_data, $table_type)
    {
		if($table_type == 'TEMP')
		{
			$query_table_name	= $this->temp_transaction_table;
			$iframe_query		= $this->merchant->get_where($this->iframe_rurl_transaction_table, array('shmart_refID'=>$temp_trans_data['shmart_refID']));
			if($iframe_query->num_rows() == 0)
			{
				$iframe_data['shmart_refID']		= $temp_trans_data['shmart_refID'];
				$iframe_data['response_url']		= $temp_trans_data['response_url'];
				$iframe_data['checksum_method']		= $temp_trans_data['checksum_method'];
				$iframe_data['creationTime']		= date('Y-m-d H:i:s');
				$this->merchant->insert($this->iframe_rurl_transaction_table, $iframe_data);
			}
			else
			{
				$iframe_data['response_url']		= $temp_trans_data['response_url'];
				$iframe_data['checksum_method']		= $temp_trans_data['checksum_method'];
				$iframe_data['updateTime']			= date('Y-m-d H:i:s');
				$this->merchant->where('shmart_refID', $temp_trans_data['shmart_refID']);
				$this->merchant->update($this->iframe_rurl_transaction_table, $iframe_data);
			}
		}
		else if($table_type == 'MAIN')
			$query_table_name	= $this->main_transactions_table;
		
		unset($temp_trans_data['response_url'], $temp_trans_data['checksum_method']);
        $query = $this->merchant->get_where($query_table_name, array('shmart_refID'=>$temp_trans_data['shmart_refID']));
        if($query->num_rows()==0)
		{
			$temp_trans_data['trans_startTime']		= date('Y-m-d H:i:s');
			$this->merchant->insert($query_table_name, $temp_trans_data);
		}
        else
        {
			$temp_trans_data['trans_updateTime']	= date('Y-m-d H:i:s');
            $this->merchant->where('shmart_refID', $temp_trans_data['shmart_refID']);
            $this->merchant->update($query_table_name, $temp_trans_data);
        }
		return ($this->merchant->affected_rows() > 0) ? 1 : 0;
    }
	
	function getMerchantID($merchant_user_id)
	{
		$this->merchant->select('merchant_id');
		$query = $this->merchant->get_where($this->merchant_profile_table, array('user_id'=>$merchant_user_id));
		foreach($query->result() as $row)
			return $row->merchant_id;
		return 0;
	}
	
	function isValidNewTransaction($merchant_id, $merchant_refID)
	{
		$this->merchant->select('user_id');
		$query = $this->merchant->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
		foreach($query->result() as $row)
			$merchant_user_id = $row->user_id;
		
		$query2 = $this->merchant->get_where($this->main_transactions_table, array('user_id'=>$merchant_user_id, 'merchant_refID'=>$merchant_refID, 'status'=>'Initiated'));
		return ($query2->num_rows() > 0) ? 1 : 0;
	}
	function getShmartRefID($merchant_id, $merchant_refID)
	{
		$this->merchant->select('user_id');
		$query = $this->merchant->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
		foreach($query->result() as $row)
			$merchant_user_id = $row->user_id;
		
		$query2 = $this->merchant->get_where($this->main_transactions_table, array('user_id'=>$merchant_user_id, 'merchant_refID'=>$merchant_refID, 'status'=>'Initiated'));
		if($query2->num_rows() > 0)
		{
			foreach($query2->result() as $row)
				return $shmart_refID = $row->shmart_refID;
		} else {
			return 0;
		}
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */