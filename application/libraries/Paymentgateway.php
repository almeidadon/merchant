<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
All payment gateway functions are included in this library
*TokenEx 
*SaveToken 
*/
class Paymentgateway {

	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('payment_gateway/logging_model');
		$this->ci->load->model('payment_gateway/payment_gateway_model');
		$this->ci->load->model('payment_gateway/consumer_model');
		$this->ci->load->model('payment_gateway/transaction_response_model');
		$this->ci->load->library('wallet_shmart');
	}
	
    public function generateToken($p_data)
    {	
		$data = array(
			'EcryptedData' => $p_data['encrypted_data']
		);
	   //convert to JSON
		$json = json_encode($data);
	  //curl config
		$ch = curl_init("https://zwitch.co/api/tokenex");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
									'Content-Type: application/json', //we are using json in this example, you could use xml as well
									'Content-Length: '.strlen($json),
									'Accept: application/json')       //we are using json in this example, you could use xml as well
									);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$outputjson = curl_exec($ch);   
		if(curl_errno($ch)){
		$output = curl_error($ch);
		} else {
		 $tokenex = json_decode($outputjson);
		}		   
		$tokenex_data['error'] = $tokenex->{'Error'};
		$tokenex_data['success'] = $tokenex->{'Success'};
		$tokenex_data['token'] =  $tokenex->{'Token'}; 
		$tokenex_data['token_refNo'] = $tokenex->{'ReferenceNumber'};
		$this->ci->logging_model->tokenexLogging($tokenex_data);
		if($tokenex_data['success']=='1')
		{
			return $tokenex_data['token'];
		} else {
			return 0;
		}
		// $tokenex_id =  "5657631072154893"; // "3960164022169120"; //Tokenex ID
		// $api_key = "1mzx3OuyoxfAd1IsDA";//"bhemmC3p9AdxRrZcm8s1";	//Tokenex apikey
		// //Payload to be sent to tokenex
		// $data = array(
			// 'TokenExID' => $tokenex_id,
			// 'APIKey' => $api_key,
			// 'EcryptedData' => $p_data['encrypted_data'],
			// 'TokenScheme' => 4
		// );
	   // //convert to JSON
		// $json = json_encode($data);
	  // //curl config
		// $ch = curl_init("https://api.tokenex.com/TokenServices.svc/REST/TokenizeFromEncryptedValue");
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
									// 'Content-Type: application/json', //we are using json in this example, you could use xml as well
									// 'Content-Length: '.strlen($json),
									// 'Accept: application/json')       //we are using json in this example, you could use xml as well
									// );
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// $outputjson = curl_exec($ch);      
		// //echo "URL error: ",curl_error($ch),PHP_EOL; 
		// if(curl_errno($ch)){
		// $output = curl_error($ch);
		// } else {
		 // $tokenex = json_decode($outputjson);
		// }		   
		// $tokenex_data['error'] = $tokenex->{'Error'};
		// $tokenex_data['success'] = $tokenex->{'Success'};
		// $tokenex_data['token'] =  $tokenex->{'Token'}; 
		// $tokenex_data['token_refNo'] = $tokenex->{'ReferenceNumber'};
		// print_r($outputjson);die;
		// $this->ci->logging_model->tokenexLogging($tokenex_data);
		// if($tokenex_data['success']=='1')
		// {
			// return $tokenex_data['token'];
		// } else {
			// return 0;
		// }
    } 
	
	function paymentProcessingEssecomCards($t_data)
	{
		$merchantid="ESSIZS0003"; // live ESSIZS0003  zwi : ESSIZMM000
		$Password="zypz3yqqby";
		$remoteIP='23.23.85.5';   //Remote IP whitelisted at essecom
		$currencyCode='356';
		$token = $t_data['token'];
		$securityCode = $t_data['cvv'];
		$expiryMonth = $t_data['cardExpiryMonth'];
		$expiryYear = $t_data['cardExpiryYear'];
		$cardType = $t_data['cardType'];
		$cardHolderName = $t_data['name_on_card'];
		$cardProvider = $t_data['cardProvider'];
		$mobileNo = $t_data['mobileNo'];
		$email = $t_data['email'];
		$amount = $t_data['pg_amount'];
		$trans_refNo = $t_data['shmart_refID'];
		$checksum = $merchantid."|".$amount."|".$trans_refNo;	
		$checksum = hash('sha256', $checksum);	
		$data='tokenNo='.$token.'&securityCode='.$securityCode.'&cardExpiryMonth='.$expiryMonth.'&cardExpiryYear='.$expiryYear.'&cardHolderName='.$cardHolderName.'&transactionAmount='.$amount.'&paymentMode='.$cardType.'&currencyCode='.$currencyCode.'&customerReferenceNo='.$trans_refNo.'&cardProvider='.$cardProvider.'&name='.$cardHolderName.'&mobileNo='.$mobileNo.'&email='.$email.'&password='.$Password.'&amount='.$amount.'&remoteIP='.$remoteIP.'&checkSum='.$checksum;
		// print_r($data);
		$encryption_key = "CE51E06875F7D964";
		$desEncryptedData = $this->encryptText_3des($data, $encryption_key);
		$desEncryptedData = urlencode($desEncryptedData);
		$url='https://payment.essecom.com/PGCCDCToken/TokenPayment.jsp?merchantId='.$merchantid.'&data='.$desEncryptedData;
		  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		  $auth = curl_exec($curl);
		 return $url;
	}
    function paymentProcessingNB($data)
    {
        if($data['processor'] == 'ESS')
        {
            $this->paymentProccessingEssecomNB($data);
        }
        else if ($data['processor'] == 'ATOM')
        {
            $this->paymentProccessingAtomNB($data);
        }
    }
    /*
     * amount
     * shmart_refID
     * BankId
     * */
    function paymentProccessingAtomNB($data)
    {
        $atom_merchant_id = '1659';
        $password = 'TRANSERV@123';
        $url="https://payment.atomtech.in/paynetz/epi/fts?login=".$atom_merchant_id."&pass=".$password."&ttype=NBFundTransfer&prodid=ECOMMERCE&amt=".$data['pg_amount']."&txncurr=INR&txnscamt=0&clientcode=VFJBTlNFUlY%3D&txnid=".$data['shmart_refID']."&date=".urlencode(date('d/m/Y H:i:s'))."&custacc=1234567890&ru=https://pay.shmart.in/response/processorResponseHandler&bankid=".$data['BankId'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_PORT , 443);
        $auth = curl_exec($curl);
        $response = curl_getinfo($curl);
        $xmlObj = new SimpleXMLElement($auth);
        $final_url = $xmlObj->MERCHANT->RESPONSE->url;
        $param = 'ttype=NBFundTransfer';
        $param .= '&tempTxnId='.$xmlObj->MERCHANT->RESPONSE->param[1];
        $param .= '&token='.$xmlObj->MERCHANT->RESPONSE->param[2];
        $param .= '&txnStage=1';
        $param .= '&bankid='.$data['BankId'];
        $url = $final_url.'?'.$param;
        echo '<html><body><form id="myForm" action="'.$url.'" method="POST">
							</form><script>document.getElementById("myForm").submit();</script></body></html>';
    }
    /*
     * amount
     * shmart_refID
     * BankId
     * name
     * email
     * mobileNo
     * */
    function paymentProccessingEssecomNB($data)
    {
        $merchantid="ESSIZS0003";
        $Password="zypz3yqqby";
        $remoteIP='23.23.85.5';   //Remote IP whitelisted at essecom
        $checksum_input = $merchantid."|".$data['pg_amount']."|".$data['shmart_refID'];
        $checksum = hash('sha256', $checksum_input);
        echo '<html><body><form id="myForm" action="https://payment.essecom.com/NetBanking/PayDirekt.jsp" method="POST">
								<input type="hidden" name="MerchantId" value="'.$merchantid.'"/>
								<input type="hidden" name="Password" value="'.$Password.'"/>
								<input type="hidden" name="ReferenceNo" value="'.$data['shmart_refID'].'"/>
								<input type="hidden" name="RemoteIP" value="'.$remoteIP.'"/>
								<input type="hidden" name="Amount" value="'.$data['pg_amount'].'"/>
								<input type="hidden" name="BankId" value="'.$data['BankId'].'"/>
								<input type="hidden" name="Checksum" value="'.$checksum.'"/>
								<input type="hidden" name="Name" value="'.$data['name_on_card'].'"/>
								<input type="hidden" name="MobileNo" value="'.$data['mobileNo'].'"/>
								<input type="hidden" name="Email" value="'.$data['email'].'"/>
							</form><script> document.getElementById("myForm").submit();</script></body></html>';
    }
	
	//Generates a unique reference ID for each transaction
	function generateRefID()
	{
		return $this->ci->payment_gateway_model->generateRefID();
	}
    function isValidTransaction($response)
    {
        return $this->ci->transaction_response_model->isValidTransaction($response);
    }
	function getNetBankingList()
    {
        return $this->ci->payment_gateway_model->getNetBankingList();
    }
    function getStatusMessage($status_code)
    {
        return $this->ci->transaction_response_model->getStatusMessage($status_code);
    }
    function updateTransactionStatus($data)
    {
        return $this->ci->transaction_response_model->updateTransactionStatus($data);
    }
    function setSettlementData($data)
    {
        return $this->ci->transaction_response_model->setSettlementData($data);
    }
    function isWalletUsed($shmart_refID)
    {
        return $this->ci->transaction_response_model->isWalletUsed($shmart_refID);
    }
    function isValidWebstoreCode($data)
    {
        return $this->ci->payment_gateway_model->isValidWebstoreCode($data);
    }

    //Generates checksum based on checksum method
	function isValidChecksum($data)
	{
        if(!isset($data['secretkey']))
            $data['secretkey'] = $this->ci->payment_gateway_model->getSecretKey($data);
		if($data['checksum_method'] == 'HMACMD5')
			{
				$hash_generated = hash_hmac('md5', $data['input_string'], $data['secretkey']);
			}
		else if($data['checksum_method'] == 'SHA256')
			{
				$hash_generated = hash('sha256', $data['secretkey'].$data['input_string']);
			}
		else if($data['checksum_method'] == 'MD5')
			{
				// echo $data['input_string'];
				// echo $data['secretkey'];
				// echo $hash_generated;
				$hash_generated = md5($data['secretkey'].$data['input_string']);
			}
		return ($hash_generated == $data['checksum'])?1:0;
	}
    function generateChecksum($data)
    {
        if($data['checksum_method'] == 'HMACMD5')
        {
            $hash_generated = hash_hmac('md5', $data['input_string'], $data['secretkey']);
        }
        else if($data['checksum_method'] == 'SHA256')
        {
            $hash_generated = hash('sha256', $data['secretkey'].$data['input_string']);
        }
        else if($data['checksum_method'] == 'MD5')
        {
            $hash_generated = md5($data['secretkey'].$data['input_string']);
        }
        return $hash_generated;
    }
	
	//Validate IP address of an incoming API request. This will check if the merchant_id is associated with the given IP address
	function isValidMerchantIP($data)
	{
		$valid_ip = filter_var($data['ip_address'], FILTER_VALIDATE_IP);
		if($valid_ip)
			return ($this->ci->payment_gateway_model->isValidMerchantIP($data))?1:0;
		return 0;
	}
	
	//Validates the ApiKey of an incoming API request. This will check the Apikey stored in DB is of the given merchant_id
	function isValidApikey($data)
	{
		return $this->ci->payment_gateway_model->isValidApikey($data);
	}
    function isValidAppId($data)
    {
        return $this->ci->payment_gateway_model->isValidAppId($data);
    }
	
	//Validates the Button ID of an incoming API request. This will check the Apikey stored in DB is of the given merchant_id
	function isValidButtnId($data)
	{
		return $this->ci->payment_gateway_model->isValidButtnId($data);	//If valid sent back all the buttn details
	}
	
	//Validates the invoice Id of an incoming API request. This will check the Apikey stored in DB is of the given merchant_id
	function isValidInvoiceId($data)
	{
		return $this->ci->payment_gateway_model->isValidInvoiceId($data);
	}

    function isValidCollctId($data)
    {
        return $this->ci->payment_gateway_model->isValidCollctId($data);
    }
	
	//Check if the merchant is active or not
	function isMerchantActive($data)
	{
		return $this->ci->payment_gateway_model->isMerchantActive($data);
	}
	
	//Gets the risk level of the merchant
	function getMerchantRiskLevel($data)
	{
		$risk_level = $this->ci->payment_gateway_model->getMerchantRiskLevel($data);
		return ($risk_level!=null)?$risk_level:0;
	}
	
	//Gets the per transaction limit set for the merchant
	function getTransactionLimit($data)
	{
		$per_transaction_limit = $this->ci->payment_gateway_model->getTransactionLimit($data);
		return ($per_transaction_limit!=null)?$per_transaction_limit:0;
	}
	
	//Checks if the user is a vaid user or not
	function getConsumerExists($data)
	{
		return $this->ci->consumer_model->getConsumerExists($data);
	}
	
	function setTransactionAdditionalParameters($data)
	{
		return $this->ci->payment_gateway_model->setTransactionAdditionalParameters($data); 
	}
	
	function setTempTransactionData($data)
	{
		return $this->ci->payment_gateway_model->setTempTransactionData($data); 
	}
	function setInitiateMainTransactionData($data)
    {
        return $this->ci->payment_gateway_model->setInitiateMainTransactionData($data);
    }
    function chargeWallet($data)
    {
        $customer_data	= $this->getConsumerExists($data);//Get the customers vouchers with the merchant
        $voucher_data	= $customer_data['voucher_data_array'];
        $make_new_voucher_array		= array();
        $used_voucher_array			= array();
        $used_voucher_array_index 	= 0;
		$voucher_balance 			= $customer_data['customer_total_voucher_balance'];	
		$general_wallet_balance		= '0.00';
		$temp_total_wallet_amount 		= $data['total_amount_to_be_charged_from_wallet'];
		$debit_general['shmart_refID']	= $debit_voucher['shmart_refID']=	$data['shmart_refID'];
		$debit_general['merchant_id']	= $debit_voucher['merchant_id']	=	$data['merchant_id'];
		$debit_general['mobileNo']		= $debit_voucher['mobileNo']	=	$data['mobileNo'];
		
		if($voucher_balance == '0.00') //Pure General Wallet Transaction
		{
            $debit_general['general_wallet_txnNo']			= $this->ci->consumer_model->generateWalletRefID();
            $debit_general['trans_general_wallet_amount']	= ($temp_total_wallet_amount*100); //Legacy wallet system needs amount to be in paisa
			$response_general 	= $this->ci->wallet_shmart->debitGeneralWallet($debit_general);
            $debit_general['wallet_trans_status']			= $debit_general['general_wallet_trans_status'] = $response_general['ResponseCode'];
			$debit_general['general_wallet_ackNo']			= $response_general['AckNo'];
			$debit_general['wallet_trans_type'] 			= 'GENERAL';
            $debit_general['trans_general_wallet_amount']	= number_format($temp_total_wallet_amount, 2); //Convert to two decimal places
            $this->ci->payment_gateway_model->setTransactionWalletData($debit_general);
			if($debit_general['general_wallet_trans_status']!='0')
                return 0;
			else
			{
				$this->ci->transaction_response_model->setWalletSettlementData($debit_general);
			}
			return 1;
		}
		else
		{			
			$debit_voucher['voucher_wallet_txnNo']			= $this->ci->consumer_model->generateWalletRefID();
			if($temp_total_wallet_amount < $voucher_balance)
				$debit_voucher['trans_voucher_wallet_amount']	= ($temp_total_wallet_amount*100);
			else
			{
				$debit_voucher['trans_voucher_wallet_amount']	= ($voucher_balance*100);
				$general_wallet_balance = number_format(($temp_total_wallet_amount - $voucher_balance),2);
			}
			$response_voucher = $this->ci->wallet_shmart->debitVoucherWallet($debit_voucher);
			$debit_voucher['wallet_trans_status']			= $debit_voucher['voucher_wallet_trans_status']	= $response_voucher['ResponseCode'];
			$make_new_voucher_array['new_ackNo']			= $debit_voucher['voucher_wallet_ackNo'] = $response_voucher['AckNo'];
			$debit_voucher['wallet_trans_type'] 			= 'VOUCHER';
			$debit_voucher['trans_voucher_wallet_amount']	= number_format(($debit_voucher['trans_voucher_wallet_amount']/100), 2);
			$this->ci->payment_gateway_model->setTransactionWalletData($debit_voucher);
			//$this->ci->transaction_response_model->setWalletVoucherSettlementData($debit_voucher);
			if($debit_voucher['voucher_wallet_trans_status']=='0')
			{
				if($general_wallet_balance=='0.00') //Pure Voucher transaction
				{
					//sorting the array based on expiry date
					for($i=0; $i<count($voucher_data); $i++)
					{
						$lowest_date_index=0;
						for($j=$i+1; $j<count($voucher_data); $j++)
						{
							if($voucher_data[$i]['expiry_date'] > $voucher_data[$j]['expiry_date'])
								$lowest_date_index=$j;
						}
						if($lowest_date_index>0)
						{
							$temp_data = $voucher_data[$i];
							$voucher_data[$i] = $voucher_data[$lowest_date_index];
							$voucher_data[$lowest_date_index] = $temp_data;
						}
					}
					//Make the vouchers as used and create new voucher from the voucher which is used partially
					for($i=0; ($i<count($voucher_data)) && ($temp_total_wallet_amount>0); $i++)
					{
						if($temp_total_wallet_amount < $voucher_data[$i]['voucher_amount'])
						{
							$make_new_voucher_array['new_voucher_amount'] 	= number_format(($voucher_data[$i]['voucher_amount'] - $temp_total_wallet_amount),2);
							$make_new_voucher_array['old_txnNo']			= $voucher_data[$i]['txnNo'];
							$make_new_voucher_array['new_txnNo'] 			= $this->ci->consumer_model->generateWalletRefID();
							//Make new voucher_data
							$this->ci->consumer_model->createNewVoucher($make_new_voucher_array);
						}
						$used_voucher_array[$used_voucher_array_index++] = $voucher_data[$i]['txnNo']; //Get the transactions number of the vouchers to be marked as used
						$temp_total_wallet_amount -= $voucher_data[$i]['voucher_amount'];
					}
					$this->ci->consumer_model->makeVoucherAsUsed($used_voucher_array,$data['shmart_refID']);
					return 1;
				}
				else //General Wallet and Voucher Wallet Hybrid Transaction
				{
					//Set used vouchers as Used
					for($i=0; $i<count($voucher_data); $i++)
						$used_voucher_array[$used_voucher_array_index++] = $voucher_data[$i]['txnNo'];
					$this->ci->consumer_model->makeVoucherAsUsed($used_voucher_array,$data['shmart_refID']);
					$debit_general['general_wallet_txnNo']	= $this->ci->consumer_model->generateWalletRefID();
					$debit_general['trans_general_wallet_amount']	= ($general_wallet_balance*100); //Legacy wallet system needs amount to be in paisa
					$response_general 	= $this->ci->wallet_shmart->debitGeneralWallet($debit_general);
					$debit_general['wallet_trans_status']			= $debit_general['general_wallet_trans_status'] = $response_general['ResponseCode'];					
					$debit_general['general_wallet_ackNo']			= $response_general['AckNo'];
					$debit_general['wallet_trans_type']				= 'BOTH';
					$debit_general['trans_general_wallet_amount']	= number_format($general_wallet_balance, 2); //Convert to two decimal places
					$this->ci->payment_gateway_model->setTransactionWalletData($debit_general);
					if($debit_general['general_wallet_trans_status']!='0')
						return 0;
					else
					{
						$this->ci->transaction_response_model->setWalletSettlementData($debit_general);
					}
					return 1;
				}
				return 0;
			}
			return 0;
		}
		return 0;
    }
	
	function getTempTransactionData($shmart_refID)
	{
		return $this->ci->payment_gateway_model->getTempTransactionData($shmart_refID); 
	}
    function getWalletTransactionData($shmart_refID)
    {
        return $this->ci->payment_gateway_model->getWalletTransactionData($shmart_refID);
    }
	
	function setUseWallet($use_wallet,$shmart_refID)
	{
		return $this->ci->payment_gateway_model->setUseWallet($use_wallet,$shmart_refID); 
	}
    function getTransactionData($shmart_refID)
    {
        return $this->ci->transaction_response_model->getTransactionData($shmart_refID);
    }
   function logApiRequest($app_used,$log_message,$level,$ip_address,$ip_address_customer)
    {
        $data['ip_address_merchant'] = $ip_address;
        $data['app_used'] = $app_used;
        $data['log_message'] = $log_message;
        $data['level'] = $level;
        $data['ip_address_customer'] = $ip_address_customer;
        return $this->ci->logging_model->logApiRequest($data);
    }
	
	function encryptText_3des($plainText, $key) {
		$key = hash("md5", $key, TRUE); 
		for ($x=0;$x<8;$x++) {
		$key = $key.substr($key, $x, 1);
		}
			$padded = $this->pkcs5_pad($plainText,
			mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC));
			$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_3DES, $key, $padded, MCRYPT_MODE_CBC));
			return $encrypted;
	}
	
	function pkcs5_pad ($text, $blocksize)   // WHERE NOT DEFINED
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
	
	function creditBackToWallet($data)
    {
		$wallet_amount_data = $this->ci->consumer_model->getWalletTransactionAmount($data['shmart_refID']);
		if($wallet_amount_data['general_wallet_amount_used'] > '0.00')
		{
            $credit_general['general_wallet_txnNo'] = $this->ci->consumer_model->generateWalletRefID();
            $credit_general['trans_general_wallet_amount'] = ($wallet_amount_data['general_wallet_amount_used']*100);
            $credit_general['mobileNo'] = $data['mobileNo'];
			  $this->ci->wallet_shmart->creditGeneralWallet($credit_general);
		}
		
		if($wallet_amount_data['voucher_wallet_amount_used'] > '0.00')
		{

            $credit_voucher['mobileNo'] = $data['mobileNo'];
            $make_vouchers = $this->ci->consumer_model->setVouchersAsUnused($data['shmart_refID']);
            foreach ($make_vouchers as $key => $voucher_details)
            {
                if($voucher_details['expiry_date'] == '2070-12-31')
                {
                    $credit_voucher['expiry_date'] = '';
                }
                $credit_voucher['voucher_wallet_txnNo'] = $this->ci->consumer_model->generateWalletRefID();
                $credit_voucher['trans_voucher_wallet_amount'] = ($voucher_details['voucher_amount']*100);
                $credit_voucher['voucher_id'] = $voucher_details['voucher_id'];
                $credit_voucher['voucher_type'] = $voucher_details['voucher_type'];
                $this->ci->wallet_shmart->creditVoucherWallet($credit_voucher);
            }
		}
    }
    function expiryDateToNumberOfDays($expiry_date)
    {
        $now = time(); // or your date as well
        $your_date = strtotime($expiry_date);
        $datediff = $your_date - $now;
         return floor($datediff/(60*60*24))+1;
    }
    function getAPIData($app_used, $apikey)
    {
        return $this->ci->payment_gateway_model->getAPIData($app_used, $apikey);
    }
    function updateTransactionAsCancelled($shmart_refID)
    {
        return $this->ci->payment_gateway_model->updateTransactionAsCancelled($shmart_refID);
    }
    function updateAppPaymentStatus($app_used,$app_id,$payment_status)
    {
        return $this->ci->payment_gateway_model->updateAppPaymentStatus($app_used,$app_id,$payment_status);
    }
	 function isWhitelabel($merchant_id)
    {
        return $this->ci->payment_gateway_model->isWhitelabel($merchant_id);
    }

	function getMerchantContactTelephone($merchant_id)
	{
		 return $this->ci->transaction_response_model->getMerchantContactTelephone($merchant_id);
	}
	
	function getMerchantContactData($merchant_id, $col)
	{
		 return $this->ci->transaction_response_model->getMerchantContactData($merchant_id, $col);
	}
	
	function getMerchantWebsite($merchant_id)
	{
		 return $this->ci->transaction_response_model->getMerchantWebsite($merchant_id);
	}	
	
	function getConsumerID($shmart_refID)
	{
		 return $this->ci->transaction_response_model->getConsumerID($shmart_refID);
	}
	
	function getConsumerEmail($shmart_refID)
	{
		 return $this->ci->consumer_model->getConsumerEmail($shmart_refID);
	}
	
	function getPendingTransferBalance($mobileNo)
	{
		return $this->ci->consumer_model->getPendingTransferBalance($mobileNo);
	}
	
	function getMerchantName($merchant_id)
	{
		return $this->ci->payment_gateway_model->getMerchantName($merchant_id);
	}
	
	function updateTransactionAsHybrid($shmart_refID)
	{
		return $this->ci->payment_gateway_model->updateTransactionAsHybrid($shmart_refID);
	}
	
	function updateWalletMainTransactionStatus($shmart_refID, $wallet_transaction_status)
	{
		$this->ci->payment_gateway_model->updateWalletMainTransactionStatus($shmart_refID, $wallet_transaction_status);
	}
	
	function updateSavedCardsForConsumer($mobileNo)
	{
		$this->ci->consumer_model->updateSavedCardsForConsumer($mobileNo);
	}
	function updateTransParams($data)
	{
		$this->ci->transaction_response_model->updateTransParams($data);
	}
	function getIframeTransactionData($shmart_refID)
	{
		return $this->ci->transaction_response_model->getIframeTransactionData($shmart_refID);
	}
}

/* End of file Someclass.php */