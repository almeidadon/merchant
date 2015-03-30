<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Transaction_response_model extends CI_Model
{
	private $users_table				= 'users';
	private $transaction_status_codes_table	= 'transactions_status_codes';
	private $main_response_table		= 'transactions_response';
	private $main_transactions_table	= 'transactions';
	private $pg_transactions_table		= 'transactions_pg';
	private $wallet_transactions_table	= 'transactions_wallet';
	private $temp_transactions_table	= 'transactions_temp';
	private $transactions_additional_parameters_table	= 'transactions_additional_parameters';
	private $iframe_rurl_transaction_table	= 'transactions_iframe_rurl';
	private $main_settlements_table		= 'settlements';
	private $merchant_profile_table		= 'merchant_profile';
	private $js_table					= 'developer_api_js';
	private $pay_by_shmart_table		= 'developer_api_pay_by_shmart';
	private $iframe_table				= 'developer_api_iframe';
	private $buttn_table				= 'payment_apps_buttn';
	private $invoiz_table				= 'payment_apps_invoiz';
	private $webstore_table				= 'payment_apps_webstore';
	private $collct_table				= 'payment_apps_collct';
	private $merchant_notifications_settings_table	= 'merchant_notifications_settings';
	private $merchant_contact_details_table	= 'merchant_contact_details';
	private $merchant_business_details_table	= 'merchant_business_details';
	private $user_gcm_link	= 'user_gcm_link';

    function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
		$ci->consumer 					= $this->load->database('consumer', TRUE); //Uses the consumer database defined in the database.php
		$this->users_table				= $ci->config->item('db_table_prefix', 'paymentgateway').$this->users_table;
		$this->transaction_status_codes_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->transaction_status_codes_table;
		$this->main_response_table		= $ci->config->item('db_table_prefix', 'paymentgateway').$this->main_response_table;
		$this->main_transactions_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->main_transactions_table;
		$this->pg_transactions_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->pg_transactions_table;
		$this->wallet_transactions_table= $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_transactions_table;
		$this->temp_transactions_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->temp_transactions_table;
		$this->transactions_additional_parameters_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->transactions_additional_parameters_table;
		$this->iframe_rurl_transaction_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->iframe_rurl_transaction_table;
		$this->main_settlements_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->main_settlements_table;
		$this->merchant_profile_table	= $ci->config->item('db_table_prefix', 'paymentgateway').$this->merchant_profile_table;
		$this->js_table					= $ci->config->item('db_table_prefix', 'paymentgateway').$this->js_table;
		$this->pay_by_shmart_table		= $ci->config->item('db_table_prefix', 'paymentgateway').$this->pay_by_shmart_table;
		$this->iframe_table				= $ci->config->item('db_table_prefix', 'paymentgateway').$this->iframe_table;
		$this->buttn_table				= $ci->config->item('db_table_prefix', 'paymentgateway').$this->buttn_table;
		$this->invoiz_table				= $ci->config->item('db_table_prefix', 'paymentgateway').$this->invoiz_table;
		$this->webstore_table			= $ci->config->item('db_table_prefix', 'paymentgateway').$this->webstore_table;
		$this->collct_table				= $ci->config->item('db_table_prefix', 'paymentgateway').$this->collct_table;
		$this->merchant_notifications_settings_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->merchant_notifications_settings_table;
		$this->merchant_contact_details_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->merchant_contact_details_table;
		$this->merchant_business_details_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->merchant_business_details_table;
		$this->user_gcm_link = $ci->config->item('db_table_prefix', 'paymentgateway').$this->user_gcm_link;
    }
	
	function getStatusMessage($status_code)
	{
		$this->db->select('status_msg');
		$query = $this->db->get_where($this->transaction_status_codes_table, array('status_code'=>$status_code));
		foreach($query->result() as $row)
			return $row->status_msg;
		return 'Failed';
	}
	
	function isValidTransaction($response_data)
	{
		$response_data['amount'] = number_format($response_data['amount'],2,'.','');
		$this->db->select('user_id, trans_amount_pg');
		$query = $this->db->get_where($this->main_transactions_table, array('shmart_refID'=>$response_data['shmart_refID']));		
		foreach($query->result() as $row)
		{
			$response_data['user_id']	= $row->user_id;
			$transaction_amount			= $row->trans_amount_pg;
		}
		$response_data['trans_responseTime'] = date('Y-m-d H:i:s');
		$this->db->insert($this->main_response_table, $response_data);
		return ($transaction_amount == $response_data['amount']) ? 1 : 0;
	}
	
	function updateTransactionStatus($trans_data)
	{
		$update_trans_data['status']			= $trans_data['status_msg'];
		$update_trans_data['trans_updateTime']	= date('Y-m-d H:i:s');
		$this->db->where('shmart_refID', $trans_data['shmart_refID']);
		$this->db->update($this->main_transactions_table, $update_trans_data);
		
		$this->db->where('shmart_refID', $trans_data['shmart_refID']);
		$this->db->update($this->pg_transactions_table, $update_trans_data);
	}
	
	function setSettlementData($trans_temp_data)
	{
		$temp_table = array(
			'shmart_refID',
			'amount',
			'trans_completedTime'
		);
		$trans_data = array_intersect_key($trans_temp_data, array_flip($temp_table));
		$trans_data['trans_date'] = $trans_data['trans_completedTime'];
		$this->db->select('transactions.merchant_refID, transactions_pg.name_on_card, transactions_pg.email, transactions_pg.user_id');
		$this->db->from($this->main_transactions_table);
		$this->db->join($this->pg_transactions_table, 'transactions_pg.shmart_refID = transactions.shmart_refID');
		$this->db->where('transactions.shmart_refID', $trans_data['shmart_refID']);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$trans_data['merchant_refID']	= $row->merchant_refID;
			$trans_data['customer_name']	= $row->name_on_card;
			$trans_data['email']			= $row->email;
			$trans_data['user_id']			= $row->user_id;
		}
		$trans_data['trans_amount']		= number_format($trans_data['amount'],2,'.','');
		unset($trans_data['amount'], $trans_data['trans_completedTime']);
		$trans_data['trans_startTime']	= date('Y-m-d H:i:s');
		
		$this->db->select('is_release_on_delivery_enabled');
		$second_query = $this->db->get_where($this->merchant_profile_table, array('user_id'=>$trans_data['user_id']));
		if($second_query->num_rows()>0)
		{
			foreach($second_query->result() as $row2)
			{
				if($row2->is_release_on_delivery_enabled=='1')
					$trans_data['settlement_status'] = 'R';
			}
		}
		$this->db->insert($this->main_settlements_table, $trans_data);
	}
	
	function setWalletSettlementData($trans_temp_data)
	{
		$temp_table = array(
			'shmart_refID',
			'trans_general_wallet_amount'
		);
		$trans_data = array_intersect_key($trans_temp_data, array_flip($temp_table));
		$this->db->select('transactions.merchant_refID, transactions_pg.name_on_card, transactions.email, transactions.user_id');
		$this->db->from($this->main_transactions_table);
		$this->db->join($this->pg_transactions_table, 'transactions_pg.shmart_refID = transactions.shmart_refID', 'left');
		$this->db->where('transactions.shmart_refID', $trans_data['shmart_refID']);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$trans_data['merchant_refID']	= $row->merchant_refID;
			$trans_data['customer_name']	= $row->name_on_card;
			$trans_data['email']			= $row->email;
			$trans_data['user_id']			= $row->user_id;
		}
		$trans_data['trans_amount']		= $trans_data['trans_general_wallet_amount'];
		unset($trans_data['trans_general_wallet_amount']);
		$trans_data['is_wallet'] = '1';
		$trans_data['trans_date'] = date('d/m/Y H:i:s');
		$trans_data['trans_startTime']	= date('Y-m-d H:i:s');
		
		$this->db->select('is_release_on_delivery_enabled');
		$second_query = $this->db->get_where($this->merchant_profile_table, array('user_id'=>$trans_data['user_id']));
		if($second_query->num_rows()>0)
		{
			foreach($second_query->result() as $row2)
			{
				if($row2->is_release_on_delivery_enabled=='1')
					$trans_data['settlement_status'] = 'R';
			}
		}		
		$this->db->insert($this->main_settlements_table, $trans_data);
	}
	
	function setWalletVoucherSettlementData($trans_temp_data)
	{
		$temp_table = array(
			'shmart_refID',
			'trans_voucher_wallet_amount'
		);
		$trans_data = array_intersect_key($trans_temp_data, array_flip($temp_table));
		$this->db->select('merchant_refID, email, user_id');
		$query = $this->db->get($this->main_transactions_table, array('shmart_refID'=>$trans_data['shmart_refID']));
		foreach($query->result() as $row)
		{
			$trans_data['merchant_refID']	= $row->merchant_refID;
			$trans_data['email']			= $row->email;
			$trans_data['user_id']			= $row->user_id;
		}
		$trans_data['trans_amount']		= $trans_data['trans_voucher_wallet_amount'];
		unset($trans_data['trans_voucher_wallet_amount']);
		$trans_data['trans_date'] = date('d/m/Y H:i:s');
		$trans_data['trans_startTime']	= date('Y-m-d H:i:s');
		
		$this->db->select('is_release_on_delivery_enabled');
		$second_query = $this->db->get_where($this->merchant_profile_table, array('user_id'=>$trans_data['user_id']));
		if($second_query->num_rows()>0)
		{
			foreach($second_query->result() as $row2)
			{
				if($row2->is_release_on_delivery_enabled=='1')
					$trans_data['settlement_status'] = 'R';
			}
		}
		$this->db->insert($this->main_settlements_table, $trans_data);
	}
	
	function isWalletUsed($shmart_refID)
	{
		$this->db->select('use_wallet');
		$query = $this->db->get_where($this->temp_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
			return $row->use_wallet;
		return 0;
	}
	
	function getTransactionData($shmart_refID)
	{
		$this->db->select('transaction_mode, email, mobileNo, total_amount, trans_amount_pg, trans_amount_wallet, app_used, app_id, merchant_refID, merchant_profile.merchant_id');
		$this->db->from($this->main_transactions_table);
		$this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = transactions.user_id');
		$this->db->where('transactions.shmart_refID', $shmart_refID);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$transaction_data['transaction_mode']	= $row->transaction_mode;
			$transaction_data['email']				= $row->email;
			$transaction_data['total_amount']		= $row->total_amount;
			$transaction_data['trans_amount_pg']	= $row->trans_amount_pg;
			$transaction_data['trans_amount_wallet']= $row->trans_amount_wallet;
			$transaction_data['app_used']			= $row->app_used;
			$transaction_data['app_id']				= $row->app_id;
			$transaction_data['merchant_refID']		= $row->merchant_refID;
			$transaction_data['merchant_id']		= $row->merchant_id;
		}
		
		$this->db->select('mobileNo');
		$temp_table_query = $this->db->get_where($this->temp_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($temp_table_query->result() as $temp_table_row)
			$transaction_data['mobileNo']			= $temp_table_row->mobileNo;
		
		$this->db->select('f_name, l_name, addr, city, state, country, zipcode, shipping_addr, shipping_city, shipping_state, shipping_country, shipping_zipcode, product_name, product_description, udf1, udf2, udf3, udf4, rurl, surl, furl, codurl, checksum_method');
		$query = $this->db->get_where($this->transactions_additional_parameters_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
		{
			$transaction_data['f_name']	= $row->f_name;
			$transaction_data['l_name']	= $row->l_name;
			$transaction_data['addr']	= $row->addr;
			$transaction_data['city']	= $row->city;
			$transaction_data['state']	= $row->state;
			$transaction_data['country']= $row->country;
			$transaction_data['zipcode']= $row->zipcode;
			$transaction_data['shipping_addr']	= $row->shipping_addr;
			$transaction_data['shipping_city']	= $row->shipping_city;
			$transaction_data['shipping_state']	= $row->shipping_state;
			$transaction_data['shipping_country']	= $row->shipping_country;
			$transaction_data['shipping_zipcode']	= $row->shipping_zipcode;
			$transaction_data['product_name']		= $row->product_name;
			$transaction_data['product_description']= $row->product_description;
			$transaction_data['udf1']	= $row->udf1;
			$transaction_data['udf2']	= $row->udf2;
			$transaction_data['udf3']	= $row->udf3;
			$transaction_data['udf4']	= $row->udf4;
			$transaction_data['rurl']	= $row->rurl;
			$transaction_data['surl']	= $row->surl;
			$transaction_data['furl']	= $row->furl;
			$transaction_data['codurl']	= $row->codurl;
			$transaction_data['checksum_method']	= $row->checksum_method;
		}
		$column_list = 'response_url, custom_response_page';
		if($transaction_data['app_used']=='BUTTN')
		{
			$query_table_name = $this->buttn_table;
			$app_column = 'buttn_id';
		}
		else if($transaction_data['app_used']=='INVOIZ')
		{
			$query_table_name = $this->invoiz_table;
			$app_column = 'inv_no';
		}
		else if($transaction_data['app_used']=='WEBSTORE')
		{
			$query_table_name = $this->webstore_table;
			$app_column = 'product_name';
		}
		else if($transaction_data['app_used']=='COLLECT')
		{
			$query_table_name = $this->collct_table;
			$app_column = 'collct_id';
		}
		else if($transaction_data['app_used']=='JS')
		{
			$query_table_name = $this->js_table;
			$app_column = 'apikey';
			$column_list .= ', secretkey';
		}
		else if($transaction_data['app_used']=='PAY_BY_SHMART')
		{
			$query_table_name = $this->pay_by_shmart_table;
			$app_column = 'apikey';
			$column_list .= ', secretkey';
		}
		$this->db->select($column_list);
		$query = $this->db->get_where($query_table_name, array($app_column=>$transaction_data['app_id']));
		foreach($query->result() as $row)
		{
			$transaction_data['response_url']			= $row->response_url;
			$transaction_data['custom_response_page']	= $row->custom_response_page;
			if(($transaction_data['app_used']=='JS') || ($transaction_data['app_used']=='PAY_BY_SHMART'))
			{
				$transaction_data['secretkey']			= $this->encrypt->decode($row->secretkey);
			}
		}
		if(($transaction_data['transaction_mode'] == 'PG') || ($transaction_data['transaction_mode'] == 'HY'))
		{
			$this->db->select('cardType, cardProvider, name_on_card');
			$query = $this->db->get_where($this->pg_transactions_table, array('shmart_refID'=>$shmart_refID));
			foreach($query->result() as $row)
			{
				$transaction_data['cardType']			= $row->cardType;
				$transaction_data['cardProvider']		= $row->cardProvider;
				$transaction_data['name_on_card']		= $row->name_on_card;
			}
		}
		if(($transaction_data['transaction_mode'] == 'W') || ($transaction_data['transaction_mode'] == 'HY'))
		{
			$this->db->select('wallet_trans_type');
			$query = $this->db->get_where($this->wallet_transactions_table, array('shmart_refID'=>$shmart_refID));
			foreach($query->result() as $row)
			{
				$transaction_data['wallet_trans_type']	= $row->wallet_trans_type;
			}
		}
		return $transaction_data;
	}
	
	function getIframeTransactionData($shmart_refID)
	{
		$this->db->select('transaction_mode, status, email, total_amount, trans_amount_pg, app_used, app_id, merchant_refID, merchant_profile.merchant_id, response_url, checksum_method, secretkey');
		$this->db->from($this->main_transactions_table);
		$this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = transactions.user_id');
		$this->db->join($this->iframe_rurl_transaction_table, 'transactions_iframe_rurl.shmart_refID = transactions.shmart_refID');
		$this->db->join($this->iframe_table, 'developer_api_iframe.user_id = transactions.user_id');
		$this->db->where('transactions.shmart_refID', $shmart_refID);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$transaction_data['transaction_mode']	= $row->transaction_mode;
			$transaction_data['status']				= $row->status;
			$transaction_data['email']				= $row->email;
			$transaction_data['total_amount']		= $row->total_amount;
			$transaction_data['trans_amount_pg']	= $row->trans_amount_pg;
			$transaction_data['app_used']			= $row->app_used;
			$transaction_data['app_id']				= $row->app_id;
			$transaction_data['merchant_refID']		= $row->merchant_refID;
			$transaction_data['merchant_id']		= $row->merchant_id;
			$transaction_data['response_url']		= $row->response_url;
			$transaction_data['checksum_method']	= $row->checksum_method;
			$transaction_data['secretkey']			= $this->encrypt->decode($row->secretkey);
		}
		
		$this->db->select('mobileNo');
		$temp_table_query = $this->db->get_where($this->temp_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($temp_table_query->result() as $temp_table_row)
			$transaction_data['mobileNo']			= $temp_table_row->mobileNo;
		
		$this->db->select('cardType, cardProvider, name_on_card');
		$query = $this->db->get_where($this->pg_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
		{
			$transaction_data['cardType']			= $row->cardType;
			$transaction_data['cardProvider']		= $row->cardProvider;
			$transaction_data['name_on_card']		= $row->name_on_card;
		}
		$transaction_data['shmart_refID']			= $shmart_refID;
		return $transaction_data;
	}
	
	function getMerchantContactTelephone($merchant_id)
	{
		$this->db->select('email_to_be_sent_to as merchant_email, contact_number as merchant_telephone');
		$this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = merchant_notifications_settings.user_id');
		$this->db->where('merchant_profile.merchant_id', $merchant_id);
		$query = $this->db->get($this->merchant_notifications_settings_table);
		foreach($query->result() as $row)
		{
			$merchant_telephone = $row->merchant_telephone;
			$merchant_email = $row->merchant_email;
			return $merchant_email."*&&&*".$merchant_telephone;
		}
		return 0;
	}
	
	function getMerchantContactData($merchant_id, $col)
	{
		if($col=='EMAIL')
			$column_name = 'p_Email';
		else if($col=='TELEPHONE')
			$column_name = 'p_Telephone';
		$this->db->select($column_name);
		$this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = merchant_contact_details.user_id');
		$this->db->where('merchant_profile.merchant_id', $merchant_id);
		$query = $this->db->get($this->merchant_contact_details_table);
		foreach($query->result() as $row)
			return $row->$column_name;
		return 0;
	}
	
	function getMerchantWebsite($merchant_id)
	{
		$this->db->select('b_website');
		$this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = merchant_business_details.user_id');
		$this->db->where('merchant_profile.merchant_id', $merchant_id);
		$query = $this->db->get($this->merchant_business_details_table);
		foreach($query->result() as $row)
			return $row->b_website;
		return 0;
	}
	
	function getConsumerID($shmart_refID)
	{
		$this->db->select('consumer_id');
		$query = $this->db->get_where($this->main_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
			return $row->consumer_id;		
	}
	
	function getGcmDeviceID($shmart_refID)
	{
		$this->db->select('user_id');
		$query = $this->db->get_where($this->main_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
			$user_id = $row->user_id;
		$this->db->select('gcm_device_id');
		$query1 = $this->db->get_where($this->user_gcm_link, array('user_id'=>$user_id));
		foreach($query1->result() as $row)
			return $row->gcm_device_id;
		
	}
	
	function updateTransParams($data)
	{
		$this->db->where('merchant_id', $data['merchant_id']);
		$this->db->set('todays_number_of_transactions', 'todays_number_of_transactions+1', FALSE);
		$this->db->update($this->merchant_profile_table);
		
		$todays_total_transaction_amount = 0;
		$this->db->select('todays_total_transaction_amount');
		$this->db->where('merchant_id', $data['merchant_id']);
		$query = $this->db->get($this->merchant_profile_table);
		foreach($query->result() as $row)
			$todays_total_transaction_amount = $row->todays_total_transaction_amount;
			
		$todays_total_transaction_amount += $data['total_amount'];
		$this->db->where('merchant_id', $data['merchant_id']);
		$this->db->set('todays_total_transaction_amount', $todays_total_transaction_amount, FALSE);
		$this->db->update($this->merchant_profile_table);
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */