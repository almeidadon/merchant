<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Payment_gateway_model extends CI_Model
{
    private $refID_unique_table = 'processor_ref_no_unique';
    private $merchant_profile_table = 'merchant_profile';
    private $checkout_table = 'developer_api_checkout';
    private $js_table = 'developer_api_js';
    private $pay_by_shmart_table = 'developer_api_pay_by_shmart';
    private $pay_via_stored_card_table = 'developer_api_pay_via_stored_card';
    private $buttn_table = 'payment_apps_buttn';
    private $invoiz_table = 'payment_apps_invoiz';
    private $collct_table = 'payment_apps_collct';
    private $webstore_table = 'payment_apps_webstore';
    private $temp_transaction_table = 'transactions_temp';
    private $additional_parameter_table = 'transactions_additional_parameters';
    private $otp_table = 'transactions_generated_otps';
    private $pg_transactions_table = 'transactions_pg';
    private $wallet_transactions_table = 'transactions_wallet';
    private $main_transactions_table = 'transactions';
    private $net_banking_list_table = 'net_banking_list';
    private $wallet_transactions_codes_table = 'transactions_wallet_status_codes';
    private $wallet_keepalive_sessions_table = 'wallet_keepalive_sessions';
    private $transactions_status_codes_table = 'transactions_status_codes';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $ci =& get_instance();
        $this->refID_unique_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->refID_unique_table;
        $this->merchant_profile_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->merchant_profile_table;
        $this->checkout_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->checkout_table;
        $this->js_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->js_table;
        $this->pay_by_shmart_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->pay_by_shmart_table;
        $this->pay_via_stored_card_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->pay_via_stored_card_table;
        $this->buttn_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->buttn_table;
        $this->invoiz_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->invoiz_table;
        $this->collct_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->collct_table;
        $this->webstore_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->webstore_table;
        $this->additional_parameter_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->additional_parameter_table;
        $this->temp_transaction_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->temp_transaction_table;
        $this->otp_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->otp_table;
        $this->pg_transactions_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->pg_transactions_table;
        $this->wallet_transactions_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_transactions_table;
        $this->main_transactions_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->main_transactions_table;
        $this->net_banking_list_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->net_banking_list_table;
        $this->wallet_transactions_codes_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_transactions_codes_table;
        $this->wallet_keepalive_sessions_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_keepalive_sessions_table;
        $this->transactions_status_codes_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->transactions_status_codes_table;
    }
    /* generates random unique transaction reference IDs */
    function generateRefID()
    {
        $cnt=0;
        do
        {
            $shmart_refID = mt_rand(100000,99999999999999);
            $query = $this->db->get_where($this->refID_unique_table, array('shmart_refID'=>$shmart_refID));
            if($query->num_rows() == 0)
                for($i=0;$i<3;$i++)
                {
                    $refID_data['shmart_refID'] = $shmart_refID;
                    $this->db->insert($this->refID_unique_table, $refID_data);
                    if($this->db->affected_rows() > 0)
                        return $shmart_refID;
                }
        }while($cnt==0);
    }

    function isValidMerchantIP($merchant_data)
    {
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_data['merchant_id'], 'ip_address'=>$merchant_data['ip_address']));
        return ($query->num_rows() > 0)?1:0;
    }

    function isMerchantActive($merchant_id)
    {
        $this->db->select('activation_status');
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
        foreach($query->result() as $row)
            $status = $row->activation_status;
        return ($status=='A'||$status=='PSA')?1:0;
    }

    function getMerchantRiskLevel($merchant_id)
    {
        $this->db->select('risk_level');
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
        foreach($query->result() as $row)
            return $row->risk_level;
    }

    function getTransactionLimit($merchant_id)
    {
        $this->db->select('per_transaction_limit, daily_transaction_limit, todays_total_transaction_amount, daily_number_of_transactions_allowed, todays_number_of_transactions');
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
        $transaction_limit_array = array();
        foreach($query->result() as $row)
        {
            $transaction_limit_array['per_transaction_limit'] = $row->per_transaction_limit;
            $transaction_limit_array['daily_transaction_limit'] = $row->daily_transaction_limit;
            $transaction_limit_array['todays_total_transaction_amount'] = $row->todays_total_transaction_amount;
            $transaction_limit_array['daily_number_of_transactions_allowed'] = $row->daily_number_of_transactions_allowed;
            $transaction_limit_array['todays_number_of_transactions'] = $row->todays_number_of_transactions;
        }
        return $transaction_limit_array;
    }

    function isValidApikey($key_data)
    {
        $this->db->select('user_id');
        $first_query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$key_data['merchant_id']));
        foreach($first_query->result() as $row)
            $user_id = $row->user_id;

        if($key_data['app_used']=='JS')
            $table_name = $this->js_table;
        else if($key_data['app_used']=='PAY_BY_SHMART')
            $table_name = $this->pay_by_shmart_table;
        else if($key_data['app_used']=='PAY_VIA_STORED_CARD')
            $table_name = $this->pay_via_stored_card_table;

        $query = $this->db->get_where($table_name, array('user_id'=>$user_id, 'apikey'=>$key_data['apikey']));
        return ($query->num_rows() > 0)?1:0;
    }
    function isValidAppId($key_data)
    {
        $this->db->select('user_id');
        $first_query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$key_data['merchant_id']));
        foreach($first_query->result() as $row)
            $user_id = $row->user_id;

        if($key_data['app_used']=='BUTTN')
        {
            $table_name = $this->buttn_table;
            $column_name = 'buttn_id';
        }
        else if($key_data['app_used']=='INVOIZ')
        {
            $table_name = $this->invoiz_table;
            $column_name = 'inv_no';
        }
        else if($key_data['app_used']=='COLLECT')
        {
            $table_name = $this->collct_table;
            $column_name = 'collct_id';
        }
		else if($key_data['app_used']=='WEBSTORE')
        {
            $table_name = $this->webstore_table;
            $column_name = 'product_name';
        }

        $query = $this->db->get_where($table_name, array('user_id'=>$user_id, $column_name=>$key_data['app_id']));
        return ($query->num_rows() > 0)?1:0;
    }

    function isValidButtnId($buttn_id)
    {
        $this->db->select('payment_apps_buttn.product_name, payment_apps_buttn.product_description, payment_apps_buttn.price, payment_apps_buttn.collect_shipping_addr, payment_apps_buttn.collect_billing_addr, payment_apps_buttn.cancel_url, payment_apps_buttn.user_id, merchant_profile.merchant_id');
        $this->db->from($this->buttn_table);
        $this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = payment_apps_buttn.user_id');
        $this->db->where('payment_apps_buttn.buttn_id', $buttn_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
            foreach($query->result() as $row)
            {
                $buttn_data['product_name'] 		= $row->product_name;
                $buttn_data['product_description'] 	= $row->product_description;
                $buttn_data['price'] 				= $row->price;
                $buttn_data['collect_shipping_addr']= $row->collect_shipping_addr;
                $buttn_data['collect_billing_addr'] = $row->collect_billing_addr;
                $buttn_data['user_id'] 				= $row->user_id;
                $buttn_data['merchant_id'] 			= $row->merchant_id;
                return $buttn_data;
            }
        return 0;
    }

    function isValidInvoiceId($inv_no)
    {
        $this->db->select('payment_apps_invoiz.inv_custName, payment_apps_invoiz.inv_custEmail, payment_apps_invoiz.inv_amount, payment_apps_invoiz.inv_payment_status, payment_apps_invoiz.user_id, payment_apps_invoiz.inv_payment_status, merchant_profile.merchant_id');
        $this->db->from($this->invoiz_table);
        $this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = payment_apps_invoiz.user_id');
        $this->db->where('payment_apps_invoiz.inv_no', $inv_no);
        $query = $this->db->get();
        if($query->num_rows() > 0)
            foreach($query->result() as $row)
            {
                $invoiz_data['inv_custName'] 		= $row->inv_custName;
                $invoiz_data['inv_payment_status'] 		= $row->inv_payment_status;
                $invoiz_data['inv_custEmail']		= $row->inv_custEmail;
                $invoiz_data['inv_amount']			= $row->inv_amount;
                $invoiz_data['inv_payment_status']	= $row->inv_payment_status;
                $invoiz_data['user_id']				= $row->user_id;
                $invoiz_data['merchant_id'] 		= $row->merchant_id;
                return $invoiz_data;
            }
        return 0;
    }

    function isValidCollctId($collct_id)
    {
        $this->db->select('payment_apps_collct.channel_of_request, payment_apps_collct.collct_payment_status , payment_apps_collct.email_or_mobileNo, payment_apps_collct.amount, payment_apps_collct.cancel_url, payment_apps_collct.user_id, merchant_profile.merchant_id');
        $this->db->from($this->collct_table);
        $this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = payment_apps_collct.user_id');
        $this->db->where('payment_apps_collct.collct_id', $collct_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
            foreach($query->result() as $row)
            {
                $collct_data['channel_of_request'] 	= $row->channel_of_request;
                $collct_data['collct_payment_status'] 	= $row->collct_payment_status;
                $collct_data['email_or_mobileNo']	= $row->email_or_mobileNo;
                $collct_data['amount']				= $row->amount;
                $collct_data['cancel_url']			= $row->cancel_url;
                $collct_data['user_id']				= $row->user_id;
                $collct_data['merchant_id'] 		= $row->merchant_id;
                return $collct_data;
            }
        return 0;
    }
	
	function isValidWebstoreCode($storeCode)
    {
        $this->db->select('payment_apps_webstore.product_name, payment_apps_webstore.product_description, payment_apps_webstore.product_price, payment_apps_webstore.cancel_url, payment_apps_webstore.user_id, merchant_profile.merchant_id');
        $this->db->from($this->webstore_table);
        $this->db->join($this->merchant_profile_table, 'merchant_profile.user_id = payment_apps_webstore.user_id');
        $this->db->where('payment_apps_webstore.product_name', $storeCode);
        $query = $this->db->get();
        if($query->num_rows() > 0)
            foreach($query->result() as $row)
            {
                $webstore_data['product_name'] 		= $row->product_name;
                $webstore_data['product_description']	= $row->product_description;
                $webstore_data['cancel_url']			= $row->cancel_url;
                $webstore_data['user_id']				= $row->user_id;
                $webstore_data['merchant_id'] 		= $row->merchant_id;
                return $webstore_data;
            }
        return 0;
    }

    function getSecretkey($key_data)
    {
        $this->db->select('user_id');
        $first_query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$key_data['merchant_id']));
        foreach($first_query->result() as $row)
            $user_id = $row->user_id;

        if($key_data['app_used']=='CHECKOUT')
            $table_name = $this->checkout_table;
        else if($key_data['app_used']=='JS')
            $table_name = $this->js_table;
        else if($key_data['app_used']=='PAY_BY_SHMART')
            $table_name = $this->pay_by_shmart_table;
        $this->db->select('secretkey');
        $query = $this->db->get_where($table_name, array('user_id'=>$user_id, 'apikey'=>$key_data['apikey']));
        foreach($query->result() as $row)
            return $this->encrypt->decode($row->secretkey);
    }
	
	function setInitiateMainTransactionData($main_transaction_data)
	{
		$main_transaction_table_data['trans_startTime'] = date('Y-m-d H:i:s');
		$main_transaction_table_data_temp = array(
			'shmart_refID',
			'email',
			'mobileNo',
			'total_amount',
			'app_used',
			'app_id',
			'merchant_refID',
			'ip_address'
        );
        $main_transaction_table_data = array_intersect_key($main_transaction_data, array_flip($main_transaction_table_data_temp));
		$this->db->select('user_id');
        $first_query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$main_transaction_data['merchant_id']));
        foreach($first_query->result() as $row)
            $main_transaction_table_data['user_id'] = $row->user_id;
		$this->db->insert($this->main_transactions_table, $main_transaction_table_data);
	}

    function setMainTransactionData($main_transaction_table_data)
	{
		$shmart_refID	= $main_transaction_table_data['shmart_refID'];
		$main_transaction_table_data_temp = array(
			'transaction_mode',
            'trans_amount_pg',
            'trans_amount_wallet',
            'email',
            'mobileNo',
			'consumer_id'
        );
        $main_transaction_table_data = array_intersect_key($main_transaction_table_data, array_flip($main_transaction_table_data_temp));
		//if(($main_transaction_table_data['transaction_mode'] == 'HY') || ($main_transaction_table_data['transaction_mode'] == 'W'))
		if($main_transaction_table_data['transaction_mode'] == 'W')
		{
			$this->db->select('wallet_trans_status');
			$query = $this->db->get_where($this->wallet_transactions_table, array('shmart_refID'=>$shmart_refID));
			foreach($query->result() as $row)
				$wallet_transaction_status	= $row->wallet_trans_status;
			$this->db->select('status_msg');
			$query = $this->db->get_where($this->wallet_transactions_codes_table, array('status_code'=>$wallet_transaction_status));
			foreach($query->result() as $row)
				$main_transaction_table_data['status']	= $row->status_msg;
		}
		$main_transaction_table_data['total_amount']	= number_format(($main_transaction_table_data['trans_amount_pg'] + $main_transaction_table_data['trans_amount_wallet']), 2, '.', '');
		$this->db->where('shmart_refID', $shmart_refID);
        $this->db->update($this->main_transactions_table, $main_transaction_table_data);
        return ($this->db->affected_rows()!=0)?1:0;
	}
	
	function updateWalletMainTransactionStatus($shmart_refID, $wallet_transaction_status)
	{
		$this->db->select('wallet_trans_status');
		$query = $this->db->get_where($this->wallet_transactions_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
			$wallet_transaction_status	= $row->wallet_trans_status;
		// $this->db->select('status_msg');
		// $query = $this->db->get_where($this->wallet_transactions_codes_table, array('status_code'=>$wallet_transaction_status));
		// foreach($query->result() as $row)
			// $main_transaction_table_data['status']	= $row->status_msg;
		if($wallet_transaction_status != '0')
		{
			$main_transaction_table_data['status']				= 'Failed';
			$main_transaction_table_data['trans_updateTime']	= date('Y-m-d H:i:s');
			$this->db->where('shmart_refID', $shmart_refID);
			$this->db->update($this->main_transactions_table, $main_transaction_table_data);
		}
		return $wallet_transaction_status;
	}

    function setTempTransactionData($temp_trans_data)
    {
        $this->db->select('user_id');
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$temp_trans_data['merchant_id']));
        foreach($query->result() as $row)
            $temp_trans_data['user_id'] = $row->user_id;
        unset($temp_trans_data['merchant_id']);
        $query2 = $this->db->get_where($this->temp_transaction_table, array('shmart_refID'=>$temp_trans_data['shmart_refID']));
        if($query2->num_rows()==0)
            return ($this->db->insert($this->temp_transaction_table, $temp_trans_data)==1)?1:0;
        else
        {
            $update_trans_data['wallet_amount'] = $temp_trans_data['wallet_amount'];
            $this->db->where('shmart_refID', $temp_trans_data['shmart_refID']);
            return ($this->db->update($this->temp_transaction_table, $update_trans_data)==1)?1:0;
        }
    }

    function getTempTransactionData($shmart_refID)
    {
        $this->db->select('shmart_refID, use_wallet, wallet_amount, total_amount, email, mobileNo, app_used, app_id, merchant_refID, ip_address, user_id');
        $query = $this->db->get_where($this->temp_transaction_table, array('shmart_refID'=>$shmart_refID));
        if($query->num_rows() > 0)
            foreach($query->result() as $row)
            {
                $return_data['shmart_refID'] 	= $row->shmart_refID;
                $return_data['use_wallet'] 		= $row->use_wallet;
                $return_data['wallet_amount'] 	= $row->wallet_amount;
                $return_data['total_amount'] 	= $row->total_amount;
                $return_data['email'] 			= $row->email;
                $return_data['mobileNo'] 		= $row->mobileNo;
                $return_data['app_used'] 		= $row->app_used;
                $return_data['app_id'] 			= $row->app_id;
                $return_data['merchant_refID'] 	= $row->merchant_refID;
                $return_data['ip_address'] 		= $row->ip_address;
                $return_data['user_id'] 		= $row->user_id;
                return $return_data;
            }
        return 0;
    }
    function getWalletTransactionData($shmart_refID)
    {
        $this->db->select('shmart_refID, wallet_trans_type, general_wallet_txnNo, voucher_wallet_txnNo, trans_general_wallet_amount, trans_voucher_wallet_amount, wallet_trans_status');
        $query = $this->db->get_where($this->wallet_transactions_table, array('shmart_refID'=>$shmart_refID));
        if($query->num_rows() > 0)
            foreach($query->result() as $row)
            {
                $return_data['shmart_refID'] 	= $row->shmart_refID;
                $return_data['wallet_trans_type'] 		= $row->wallet_trans_type;
                $return_data['general_wallet_txnNo'] 	= $row->general_wallet_txnNo;
                $return_data['voucher_wallet_txnNo'] 	= $row->voucher_wallet_txnNo;
                $return_data['trans_general_wallet_amount'] 			= $row->trans_general_wallet_amount;
                $return_data['trans_voucher_wallet_amount'] 		= $row->trans_voucher_wallet_amount;
                $return_data['wallet_trans_status'] 		= $row->wallet_trans_status;
                return $return_data;
            }
        return 0;
    }
    function getNetBankingList()
    {
        $this->db->select('processor_net_banking_code, name_of_bank, processor');
		$this->db->order_by('name_of_bank', 'ASC');
        $query = $this->db->get($this->net_banking_list_table);
        return $query->result_array();
    }
	
	function setTransactionAdditionalParameters($parameter_data)
    {
        $this->db->select('user_id');
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$parameter_data['merchant_id']));
        foreach($query->result() as $row)
            $parameter_data['user_id'] = $row->user_id;
        unset($parameter_data['merchant_id']);
        $this->db->insert($this->additional_parameter_table, $parameter_data);
        return ($this->db->affected_rows()!=0)?1:0;
    }
	
    function setPgTransactionData($pg_trans_data)
    {
		$trans_data['trans_startTime'] = date('Y-m-d H:i:s');
        $temp_var = array(
            'shmart_refID',
            'email',
            'mobileNo',
            'pg_amount',
            'user_id',
            'name_on_card',
            'hybrid_or_not',
            'cardType',
            'cardProvider',
			'processor'
        );
        $trans_data = array_intersect_key($pg_trans_data, array_flip($temp_var));
        $this->db->insert($this->pg_transactions_table, $trans_data);
        return ($this->db->affected_rows()!=0)?1:0;
    }

    function setUseWallet($use_wallet, $shmart_refID)
    {
        $this->db->where('shmart_refID', $shmart_refID);
        $this->db->update($this->temp_transaction_table, array('use_wallet' => $use_wallet));
        return ($this->db->affected_rows()!=0)?1:0;
    }

    function generateOTP($otp_data)
    {
        $this->db->insert($this->otp_table, $otp_data);
        return ($this->db->affected_rows()!=0)?1:0;
    }

    function validateOTP($otp_data)
    {
        $this->db->select('otp');
        $this->db->where('mobileNo',$otp_data['mobileNo']);
        $this->db->where("id in (select max(id) from ".$this->otp_table." where mobileNo='".$otp_data['mobileNo']."')");
        $query = $this->db->get($this->otp_table);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                if($otp_data['otp'] == $row->otp)
                {
                    $this->db->delete($this->otp_table, array('mobileNo' => $otp_data['mobileNo']));
                    return 1;
                }
            return 0;
        }
        return 0;
    }
	
	function setTransactionWalletData($wallet_trans_data)
	{
		$this->db->select('user_id');
		$first_query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$wallet_trans_data['merchant_id']));
		foreach($first_query->result() as $row)
			$wallet_trans_data['user_id'] = $row->user_id;
		unset($wallet_trans_data['merchant_id'], $wallet_trans_data['mobileNo']);
		
		$second_query = $this->db->get_where($this->wallet_transactions_table, array('shmart_refID'=>$wallet_trans_data['shmart_refID']));
		if($second_query->num_rows()==0)
		{
			$wallet_trans_data['trans_startTime'] = date('Y-m-d H:i:s');
            return ($this->db->insert($this->wallet_transactions_table, $wallet_trans_data)==1)?1:0;
		}
        else
        {
			$shmart_refID = $wallet_trans_data['shmart_refID'];
			unset($wallet_trans_data['shmart_refID']);
            $wallet_trans_data['trans_updateTime'] = date('Y-m-d H:i:s');
            $this->db->where('shmart_refID', $shmart_refID);
            return ($this->db->update($this->wallet_transactions_table, $wallet_trans_data)==1)?1:0;
        }
	}
	
	function updateTransactionAsHybrid($shmart_refID)
	{
		$trans_data['hybrid_or_not'] = '1';
		$this->db->where('shmart_refID', $shmart_refID);
		$this->db->update($this->wallet_transactions_table, $trans_data);
		
		$this->db->where('shmart_refID', $shmart_refID);
		$this->db->update($this->pg_transactions_table, $trans_data);
	}
	
	function updateTransactionAsSavedCard($shmart_refID)
	{
		$trans_data['is_saved_card_or_not'] = '1';
		$this->db->where('shmart_refID', $shmart_refID);
		$this->db->update($this->pg_transactions_table, $trans_data);
	}
	
	function getAPIData($app_used, $apikey)
	{
		if($app_used=='JS')
            $table_name = $this->js_table;
        else if($app_used=='PAY_BY_SHMART')
            $table_name = $this->pay_by_shmart_table;
		
		$this->db->select('secretkey, user_id, response_url, custom_response_page');
		$query	= $this->db->get_where($table_name, array('apikey'=>$apikey));
		foreach($query->result() as $row)
		{
			$api_data['secretkey']				= $this->encrypt->decode($row->secretkey);
			$api_data['user_id']				= $row->user_id;
			$api_data['response_url']			= $row->response_url;
			$api_data['custom_response_page']	= $row->custom_response_page;
		}
		return $api_data;
	}
	
	function updateTransactionAsCancelled($shmart_refID)
	{
		$trans_data['status'] = 'Cancelled';
		$this->db->where('shmart_refID', $shmart_refID);
		return($this->db->update($this->main_transactions_table, $trans_data)==1)?1:0;
	}

    function updateAppPaymentStatus($app_used, $app_id, $payment_status)
    {
        if($app_used=='INVOIZ')
        {
            $query_table_name = $this->invoiz_table;
            $app_column = 'inv_no';
            $update_app_column = 'inv_payment_status';
        }
        else if($app_used=='WEBSTORE')
        {
            $query_table_name = $this->webstore_table;
            $app_column = 'webstoreCode';
            $update_app_column = 'webstore_payment_status';
        }
        else if($app_used=='COLLECT')
        {
            $query_table_name = $this->collct_table;
            $app_column = 'collct_id';
            $update_app_column = 'collct_payment_status';
        }

        $update_data[$update_app_column] = $payment_status;
        $this->db->where($app_column, $app_id);
        $this->db->update($query_table_name, $update_data);
    }
    function getWalletSession()
    {
        $this->db->select('SessionID');
        $query = $this->db->get($this->wallet_keepalive_sessions_table);
        $res = $query->result_array();
        foreach($query->result() as $row)
        {
            $SessionID			= $row->SessionID;
        }
		return  $SessionID	;
    }
    function insertNewWalletSession($data)
    {
        $data['creationTime'] = date('Y-m-d H:i:s');
        return ($this->db->insert($this->wallet_keepalive_sessions_table, $data)==1)?1:0;
    }
	
	function isWhitelabel($merchant_id)
	{
		$this->db->select('website, brand_name, user_id');
        $query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id, 'is_whitelabel'=>'1'));
        foreach($query->result() as $row)
        {
			$data['website']	= $row->website;
			$data['brand_name']	= $row->brand_name;
			$data['user_id']	= $row->user_id;
			
			return $data;
		}
        return 0;
	}
	
	function getMerchantName($merchant_id)
	{
		$this->db->select('website');
		$query = $this->db->get_where($this->merchant_profile_table, array('merchant_id'=>$merchant_id));
		foreach($query->result() as $row)
			return $row->website;
	}
	
	function get_distinct_trans_status()
	{
		$this->db->select('status_code, status_msg');
		$query = $this->db->get($this->transactions_status_codes_table);
		return $query->result_array();
	}
	
	function isNewTransaction($shmart_refID)
	{
		$query = $this->db->get_where($this->main_transactions_table, array('shmart_refID'=>$shmart_refID, 'status'=>'Initiated'));
        return ($query->num_rows() == 1) ? 1 : 0;
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */