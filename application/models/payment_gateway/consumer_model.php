<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Consumer_model extends CI_Model
{
	private $users_table = 'users';
	private $consumer_profile_table = 'consumer_profile';
	private $users_ID_card_table = 'users_memberIdCardNo';
	private $cards_table = 'my_saved_cards';
	private $nb_table = 'my_saved_netbanking';
	private $address_table = 'my_saved_shipping_address';
	private $vouchers_table = 'vouchers';
    private $otp_table = 'transactions_generated_otps';
    private $wallet_refNo_unique_table = 'wallet_ref_no_unique';
    private $voucherID_unique_table = 'voucherID_unique_table';
    private $saved_card_unique_token_table = 'user_card_unique_token';
    private $transactions_vouchers_table = 'transactions_vouchers';
    private $wallet_table = 'transactions_wallet';
    private $new_saved_cards_table = 'new_consumer_saved_cards';
	private $wallet_transfers_holding_table = 'transactions_wallet_transfers_holding';

    function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
		$ci->consumer = $this->load->database('consumer', TRUE); //Uses the consumer database defined in the database.php
		$this->users_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->users_table;
		$this->users_ID_card_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->users_ID_card_table;
		$this->consumer_profile_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->consumer_profile_table;
		$this->cards_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->cards_table;
		$this->nb_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->nb_table;
		$this->address_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->address_table;
		$this->vouchers_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->vouchers_table;
        $this->otp_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->otp_table;
        $this->wallet_refNo_unique_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_refNo_unique_table;
        $this->voucherID_unique_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->voucherID_unique_table;
        $this->saved_card_unique_token_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->saved_card_unique_token_table;
        $this->transactions_vouchers_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->transactions_vouchers_table;
        $this->wallet_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_table;
        $this->new_saved_cards_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->new_saved_cards_table;
        $this->wallet_transfers_holding_table = $ci->config->item('db_table_prefix', 'paymentgateway').$this->wallet_transfers_holding_table;
    }
	
	function generateWalletRefID()
	{
		do
		{
			$wallet_ref_no_data['wallet_refID'] = mt_rand(100000000,999999999999);
			$query = $this->consumer->get_where($this->wallet_refNo_unique_table, array('wallet_refID'=>$wallet_ref_no_data['wallet_refID']));
		}while($query->num_rows() != 0);
		
		for($i=0;$i<3;$i++)
		{
			$this->consumer->insert($this->wallet_refNo_unique_table, $wallet_ref_no_data);
			if($this->consumer->affected_rows() > 0)
				return $wallet_ref_no_data['wallet_refID'];
		}
	}
    function generateVoucherID()
    {
        $cnt=0;
        do
        {
            $voucher_ID = "V".mt_rand(1000,999999999999);
            $query = $this->consumer->get_where($this->voucherID_unique_table, array('voucher_ID'=>$voucher_ID));
            if($query->num_rows() == 0)
                for($i=0;$i<3;$i++)
                {
                    $voucher_data['voucher_ID'] = $voucher_ID;
                    $this->consumer->insert($this->voucherID_unique_table, $voucher_data);
                    if($this->consumer->affected_rows() > 0)
                        return $voucher_ID;
                }
        }while($cnt==0);
    }
	
	function getPartnerRefNo($consumer_data)
	{
		$this->consumer->select('id');
		$first_query = $this->consumer->get_where($this->users_table, array('username'=>$consumer_data['mobileNo']));
		if($first_query->num_rows() > 0)
			foreach($first_query->result() as $row)
			{
				$consumer_id = $row->id;
				$this->consumer->select('par_ref_no');
				$second_query = $this->consumer->get_where($this->consumer_profile_table, array('consumer_id'=>$consumer_id));
				if($second_query->num_rows()!=0)
					foreach($second_query->result() as $row)
						return ($row->par_ref_no==null)?0:$row->par_ref_no;
				return 0;
			}
		return 0;
	}
    /*
     * consumer_id
     * par_ref_no
     * */
    function generatePartnerRefNo($mobileNo)
    {
        $this->consumer->select('id');
        $first_query = $this->consumer->get_where($this->users_table, array('username'=>$mobileNo));
        if($first_query->num_rows() > 0)
            foreach($first_query->result() as $row)
            {
                $consumer_id = $row->id;
            }
        else
            return 0;
        $cnt=0;
        do
        {
            $par_ref_no = "C".mt_rand(1000,99999999);
            $query = $this->consumer->get_where($this->consumer_profile_table, array('par_ref_no'=>$par_ref_no));
            if($query->num_rows() == 0)
                for($i=0;$i<3;$i++)
                {
                    $consumer_data['par_ref_no'] = $par_ref_no;
                    $consumer_data['consumer_id'] = $consumer_id;
                    $this->consumer->insert($this->consumer_profile_table, $consumer_data);
                        return $par_ref_no;
                }
        }while($cnt==0);
    }
	
	function generateUniqueTokenForCard()
	{
		do
		{
			$card_data['user_card_unique_token'] = mt_rand(100000,999999999999);
			$query = $this->consumer->get_where($this->saved_card_unique_token_table, array('user_card_unique_token'=>$card_data['user_card_unique_token']));
		}while($query->num_rows() != 0);
		
		for($i=0;$i<3;$i++)
		{
			$this->consumer->insert($this->saved_card_unique_token_table, $card_data);
			if($this->consumer->affected_rows() > 0)
				return $card_data['user_card_unique_token'];
		}
	}
	
	function getConsumerExists($consumer_data)
	{
		$this->consumer->select('id,email');
		$first_query = $this->consumer->get_where($this->users_table, array('username'=>$consumer_data['mobileNo']));
		if($first_query->num_rows() > 0)
			foreach($first_query->result() as $row)
			{
				$consumer_id = $row->id;
				$cust_data['email'] =  $row->email;
				$cust_data['mobileNo'] =  $consumer_data['mobileNo'];
				if($consumer_id==null)
					return 0;
				
				//Gets the member ID card number of the consumer
				$this->consumer->select('MemberIDCardNo');
				$second_query = $this->consumer->get_where($this->users_ID_card_table, array('consumer_id'=>$consumer_id));
				if($second_query->num_rows()==0)
					$MemberIDCardNo = 0;
				else
					foreach($second_query->result() as $row)
						$MemberIDCardNo = ($row->MemberIDCardNo==null)?'not set':$row->MemberIDCardNo;
				
				//Gets all saved cards
				$this->consumer->select('card_id_by_consumer, card_type, token, card_provider, expiry_month, expiry_year, cardholder_name, user_card_unique_token');
				$third_query = $this->consumer->get_where($this->cards_table, array('consumer_id'=>$consumer_id));
				$saved_cards_array = array();
				$cards_array = array();
				$i=0;
				if($third_query->num_rows()==0)
					$saved_cards_array[$i] = 0;
				else
					foreach($third_query->result() as $row)
					{
						$cards_array['card_id_by_consumer'] = $row->card_id_by_consumer;
						$cards_array['card_type'] = $row->card_type;
						$cards_array['token'] = $row->token;
						$cards_array['card_provider'] = $row->card_provider;
						$cards_array['expiry_month'] = $row->expiry_month;
						$cards_array['expiry_year'] = $row->expiry_year;
						$cards_array['cardholder_name'] = $row->cardholder_name;
						$cards_array['user_card_unique_token'] = $row->user_card_unique_token;
						$saved_cards_array[$i++] = $cards_array;
					}
				
				//Gets all saved netbanking
				$this->consumer->select('netbanking_id_by_consumer, bank_name');
				$fourth_query = $this->consumer->get_where($this->nb_table, array('consumer_id'=>$consumer_id));
				$saved_nb_array = array();
				$nb_array = array();
				$i=0;
				if($fourth_query->num_rows()==0)
					$saved_nb_array[$i] = 0;
				else
					foreach($fourth_query->result() as $row)
					{
						$nb_array['netbanking_id_by_consumer'] = $row->netbanking_id_by_consumer;
						$nb_array['bank_name'] = $row->bank_name;
						$saved_nb_array[$i++] = $nb_array;
					}
				
				//Gets all saved shipping address    SOME ISSUES HERE
				$this->consumer->select('address, city, state, country, pincode');
				$fifth_query = $this->consumer->get_where($this->address_table, array('consumer_id'=>$consumer_id));
				$saved_address_array = array();
				$address_array = array();
				$i=0;
				if($fifth_query->num_rows()==0)
					$saved_address_array[$i] = 0;
				else
					foreach($fifth_query->result() as $row)
					{
						$address_array['address'] = $row->address;
						$address_array['city'] = $row->city;
						$address_array['state'] = $row->state;
						$address_array['country'] = $row->country;
						$address_array['pincode'] = $row->pincode;
						$saved_address_array[$i++] = $address_array;
					}
				
				//Gets all vouchers of the consumer
				$today_date = strtotime("now");
				$today = date('Y-m-d', $today_date);
				$where_cond = "expiry_date >= '".$today."'";
				$this->consumer->select('voucher_id, voucher_type, voucher_amount, expiry_date, txnNo, post_expiry_to_shmart_or_not, narrations');
				$this->consumer->where('consumer_id', $consumer_id);
				$this->consumer->where('is_expired', '0');
				$this->consumer->where('is_used', '0');
				$this->consumer->where('is_deleted', '0');
				$this->consumer->where($where_cond);
				$sixth_query = $this->consumer->get($this->vouchers_table);
				$voucher_data_array = array();
				$v_data_array = array();
				$data['customer_total_voucher_balance'] = '0.00';
				$i=0;
				if($sixth_query->num_rows()==0)
					$voucher_data_array[$i] = 0;
				else
					foreach($sixth_query->result() as $row)
					{
						$v_data_array['voucher_id'] = $row->voucher_id;
						$v_data_array['voucher_type'] = $row->voucher_type;
						$v_data_array['voucher_amount'] = $row->voucher_amount;
						$v_data_array['txnNo'] = $row->txnNo;
						$v_data_array['expiry_date'] = $row->expiry_date;
						$v_data_array['post_expiry_to_shmart_or_not'] = $row->post_expiry_to_shmart_or_not;
						$v_data_array['narrations'] = $row->narrations;
						$data['customer_total_voucher_balance'] += $v_data_array['voucher_amount'];
						$voucher_data_array[$i++] = $v_data_array;
					}
				
				$today_date = strtotime("now");
				$today = date('Y-m-d', $today_date);
				$where_cond = "expiry_date >= '".$today."'";
				$this->consumer->select('sum(voucher_amount) as customer_merchant_voucher_balance');
				$this->consumer->where('consumer_id', $consumer_id);
				$this->consumer->where('merchant_id', $consumer_data['merchant_id']);
				$this->consumer->where('is_expired', '0');
				$this->consumer->where('is_used', '0');
				$this->consumer->where('is_deleted', '0');
				$this->consumer->where($where_cond);
				$seventh_query = $this->consumer->get($this->vouchers_table);
				$data['customer_merchant_voucher_balance'] = '0.00';
				if($seventh_query->num_rows()==0)
					$voucher_data_array[$i] = 0;
				else
					foreach($seventh_query->result() as $row)
					{
						$data['customer_merchant_voucher_balance'] = $row->customer_merchant_voucher_balance;
					}
				//Integrate all data and return
				$data['MemberIDCardNo'] = $MemberIDCardNo;
				$data['saved_cards'] = $saved_cards_array;
				$data['saved_nb_array'] = $saved_nb_array;
				$data['saved_address_array'] = $saved_address_array;
				$data['voucher_data_array'] = $voucher_data_array;
				$data['cust_data'] = $cust_data;
				return $data;
			}
		return 0;
	}
	
	function getSavedCardDataWithUniqueToken($user_card_unique_token)
	{
		$this->consumer->select('card_type, token, card_provider, expiry_month, expiry_year, cardholder_name');
		$query = $this->consumer->get_where($this->cards_table, array('user_card_unique_token'=>$user_card_unique_token));
		foreach($query->result() as $row)
		{
			$saved_card_info['cardType'] 		= $row->card_type;
			$saved_card_info['token'] 			= $row->token;
			$saved_card_info['cardProvider'] 	= $row->card_provider;
			$saved_card_info['cardExpiryMonth'] = $row->expiry_month;
			$saved_card_info['cardExpiryYear'] 	= $row->expiry_year;
			$saved_card_info['name_on_card'] 	= $row->cardholder_name;
		}
		return $saved_card_info;
	}
	
	function setNewSavedCardData($card_data)
	{
		$card_data_temp = array(
			'card_id_by_consumer',
            'token',
            'user_card_unique_token'
        );
        $card_info = array_intersect_key($card_data, array_flip($card_data_temp));
		$this->consumer->select('id');
		$query = $this->consumer->get_where($this->users_table, array('username'=>$card_data['mobileNo']));
			foreach($query->result() as $row)
				$card_info['consumer_id'] = $row->id;
		$card_info['creation_time'] = date('Y-m-d H:i:s');
		$card_info['card_type']		= $card_data['cardType'];
		$card_info['card_provider']	= $card_data['cardProvider'];
		$card_info['expiry_month']	= $card_data['cardExpiryMonth'];
		$card_info['expiry_year']	= $card_data['cardExpiryYear'];
		$card_info['cardholder_name']= $card_data['name_on_card'];
        $this->consumer->insert($this->cards_table, $card_info);
		
		$new_card_data['mobileNo'] = $card_data['mobileNo'];
		$new_card_data['user_card_unique_token'] = $card_data['user_card_unique_token'];
		$this->consumer->insert($this->new_saved_cards_table, $new_card_data);
        return ($this->consumer->affected_rows()!=0)?1:0;
	}
	
	function updateSavedCardsForConsumer($mobileNo)
	{
		$this->consumer->select('user_card_unique_token');
		$query = $this->consumer->get_where($this->new_saved_cards_table, array('mobileNo'=>$mobileNo));
		if($query->num_rows()>0)
		{
			$this->consumer->select('id');
			$inside_query = $this->consumer->get_where($this->users_table, array('username'=>$mobileNo));
			foreach($inside_query->result() as $row2)
				$consumer_data['consumer_id'] = $row2->id;
			
			foreach($query->result() as $row)
			{
				$this->consumer->where('user_card_unique_token', $row->user_card_unique_token);
				if($this->consumer->update($this->cards_table, $consumer_data)==1)
				{
					$this->consumer->where('user_card_unique_token', $row->user_card_unique_token);
					$this->consumer->delete($this->new_saved_cards_table);
				}
			}
			return 1;
		}
		return 0;
	}
	
    function generateOTP($otp_data)
    {
        $this->consumer->insert($this->otp_table, $otp_data);
        return ($this->consumer->affected_rows()!=0)?1:0;
    }
	
    function createNewVoucher($voucher_data)
    {
		$this->consumer->select('merchant_id, voucher_id, voucher_type, expiry_date, post_expiry_to_shmart_or_not, narrations, consumer_id');
		$query = $this->consumer->get_where($this->vouchers_table, array('txnNo'=>$voucher_data['old_txnNo']));
		$v_data_array = array();
		foreach($query->result() as $row)
		{
			$v_data_array['merchant_id']	= $row->merchant_id;
			$v_data_array['voucher_id']		= $row->voucher_id;
			$v_data_array['voucher_type']	= $row->voucher_type;
			$v_data_array['voucher_amount']	= $voucher_data['new_voucher_amount'];
			$v_data_array['expiry_date']	= $row->expiry_date;
			$v_data_array['txnNo']			= $voucher_data['new_txnNo'];
			$v_data_array['ackNo']			= $voucher_data['new_ackNo'];
			$v_data_array['post_expiry_to_shmart_or_not']			= $row->post_expiry_to_shmart_or_not;
			$v_data_array['narrations']		= $row->narrations;
			$v_data_array['consumer_id']	= $row->consumer_id;
		}
		$v_data_array['creationTime']		= date('Y-m-d H:i:s');
        return ($this->consumer->insert($this->vouchers_table, $v_data_array)==0)?0:1;
    }
	
	function makeVoucherAsUsed($voucher_txnNo_data, $shmart_refID)
    {
		$voucher_transaction_data['shmart_refID']	= $shmart_refID;
		$voucher_used_data['updateTime'] 			= $voucher_transaction_data['creationTime']	= date('Y-m-d H:i:s');
		$voucher_used_data['is_used'] 				= '1';
		for($i=0; $i<count($voucher_txnNo_data); $i++)
		{
			$this->consumer->where('txnNo', $voucher_txnNo_data[$i]);
			$this->consumer->update($this->vouchers_table, $voucher_used_data);
			
			$voucher_transaction_data['voucher_wallet_txnNo'] = $voucher_txnNo_data[$i];
			$this->consumer->insert($this->transactions_vouchers_table, $voucher_transaction_data);
		}
	}
	
	function createNewVoucherForApi($voucher_data)
    {
		$this->consumer->select('id');
		$first_query = $this->consumer->get_where($this->users_table, array('username'=>$voucher_data['mobileNo']));
		if($first_query->num_rows() > 0)
			foreach($first_query->result() as $row)
				$voucher_data['consumer_id'] = $row->id;
		if($voucher_data['expiry_date']==null)
			unset($voucher_data['expiry_date']);
		if(isset($voucher_data['post_expiry_to_shmart_or_not']))			
			$voucher_data['post_expiry_to_shmart_or_not']	= '1';
		$voucher_data['creationTime']						= date('Y-m-d H:i:s');
		unset($voucher_data['mobileNo']);
        return ($this->consumer->insert($this->vouchers_table, $voucher_data)==0)?0:1;
    }

    function validateOTP($otp_data)
    {
        $this->consumer->select('otp');
        $this->consumer->where('mobileNo',$otp_data['mobileNo']);
        // $this->consumer->where("id in (select max(id) from ".$this->otp_table." where mobileNo='".$otp_data['mobileNo']."')");
        $query = $this->consumer->get($this->otp_table);
        if($query->num_rows()>0)
        {
            foreach($query->result() as $row)
                if($otp_data['otp'] == $row->otp)
                {
                    $this->consumer->delete($this->otp_table, array('mobileNo' => $otp_data['mobileNo']));
                    return 1;
                }
            return 0;
        }
        return 0;
    }
	
	function getWalletTransactionAmount($shmart_refID)
	{
		$this->db->select('trans_general_wallet_amount, trans_voucher_wallet_amount');
		$query = $this->db->get_where($this->wallet_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
		{
			$wallet_used_data['general_wallet_amount_used'] = ($row->trans_general_wallet_amount==null)?'0.00':$row->trans_general_wallet_amount;
			$wallet_used_data['voucher_wallet_amount_used'] = ($row->trans_voucher_wallet_amount==null)?'0.00':$row->trans_voucher_wallet_amount;
		}
		return $wallet_used_data;
	}
	
	function setVouchersAsUnused($shmart_refID)
	{
		$voucher_used_data['is_used'] = '0';
		$voucher_used_data['updateTime'] = date('Y-m-d H:i:s');
		$voucher_txnNo_list=null;
		$this->consumer->select('voucher_wallet_txnNo');
		$query = $this->consumer->get_where($this->transactions_vouchers_table, array('shmart_refID'=>$shmart_refID));
		foreach($query->result() as $row)
		{
			$this->consumer->where('txnNo', $row->voucher_wallet_txnNo);
			$this->consumer->update($this->vouchers_table, $voucher_used_data);
			$voucher_txnNo_list .= "'".$row->voucher_wallet_txnNo."',";
		}
		$txn_ids = implode(',', array_keys(array_flip(explode(',', $voucher_txnNo_list))));
		$txn_ids = rtrim($txn_ids, ',');
		$where_condition = "txnNo in (".$txn_ids.")";
		$this->consumer->select('voucher_id, voucher_type, voucher_amount, expiry_date');
		$this->consumer->where($where_condition);
		$second_query = $this->consumer->get($this->vouchers_table);
		return $second_query->result_array();
	}
	
	function getConsumerEmail($consumer_id)
	{
		$this->consumer->select('email');
		$first_query = $this->consumer->get_where($this->users_table, array('id'=>$consumer_id));
		foreach($first_query->result() as $row)
			return $row->email;
		return 0;
	}
	
	function getPendingTransferBalance($mobileNo)
	{
		$this->consumer->select('sum(amount) as total_balance');
        $query= $this->consumer->get_where($this->wallet_transfers_holding_table, array('mobileNo'=>$mobileNo));
		if($query->num_rows()>0)
			foreach($query->result() as $row)
				return ($row->total_balance==null)?'0':$row->total_balance;
		return '0';
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */