<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * This file builds the version 1.0 of the Checkout API used for merchant integration.
 * The file is responsible for parsing the POST request , request validation and authentication
 *
 * @category   API
 * @package    Checkout API
 * @author     Ajeesh Achuthan <ajeesh.achuthan@transerv.co.in>
 * @version    1.0
 * @since      Initial release
 */

/**
 * Load the helpers 'url' , payment_gateway-helper
 * Load the Library 'paymentgateway'
 */

/**
 * This class contains two functions
 *
 * transactions() : Creates a URL format v1/transactions with CI configuration.
 * Validates all the external API requests , authenticate and authorises access to API
 * process() : Renders the payment page after all the validation and authorisation processes.
 */

/**
Helper functions used in this controller
----------------------------------------
- payment_gateway_helper.php
 *validateEmail() - email
 *validateMobileNo() - mobileNo
 *validateCurrencyCode() - currency
 *validateChecksumMethod() - checksum_method
 *validateZipCode() - zip
 *validateAlphaString() - string
 *verifyAmount() - amount is in paisa or not.Should contain only numbers
 *cleanUrl() - incoming_url
 *verifyBinary() - check if 1/0

Library functions used
----------------------
- Paymentgateway.php
 *generateRefID()
 *generateChecksum - $data['input_string'] , $data['checksum_method'] , $data['apikey'] , $data['app_used'] , $data['merchant_checksum']
 *isValidMerchantIP() - $data['merchant_id'] , $data['ip_address']
 *isValidCheckoutApiKey() - $data['merchant_id'] , $data['apikey']
 *isMerchantActive() - $data['merchant_id']
 *getMerchantRiskLevel() - $data['merchant_id']
 *getTransactionLimit() - $data['merchant_id']
 */

class V1 extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $additional_params_list = array();
        $additional_params      = array();
        $data                   = array();
        $get_wallet_balance_var = array();
        $this->load->helper(array(
            'url',
            'payment_gateway_helper',
            'basic_helper',
            'form'
        ));
        $this->load->library('paymentgateway');
        $this->load->library('wallet_shmart');
        $this->load->library('encrypt');
        $this->load->library('notification_lib');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
    }

    function transactions() //Function validates the incoming requests
    {
        //If the request is POST
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data                        = $_POST;
            $data['input_string']        = $data['merchant_id'] . '|' . $data['apikey'] . '|' . $data['ip_address'] . '|' . $data['merchant_refID'] . '|' . $data['currency_code'] . '|' . $data['amount'] . '|' . $data['checksum_method'].'|'.$data['authorize_user'];
            $data['app_used']            = 'PAY_BY_SHMART'; //Hard code in each API
            $data['app_id']            = $data['apikey']; //APIKEY is app_id
            $data['ip_address_customer'] = $_SERVER['REMOTE_ADDR'];
            $limits = $this->paymentgateway->getTransactionLimit($data['merchant_id']);
            $data['amount'] = verifyAmount($data['amount']);
            if ($data['amount'] == 0) {
                $api_error = "Amount cannot be zero";
            } else if (!$this->paymentgateway->isValidMerchantIP($data)) {
                $api_error = "Request from invalid IP Address";
            } else if (!$this->paymentgateway->isMerchantActive($data['merchant_id']) ) {
                $api_error = "Not an active merchant";
            } else if (!$this->paymentgateway->isValidApiKey($data)) {
                $api_error = "Wrong apikey";
            } else if (!$this->paymentgateway->isValidChecksum($data)) {
                $api_error = "Checksum mismatch";
            } else if (($data['amount']) > ($limits['per_transaction_limit'])) {
                $api_error = "Your per transaction limit has reached";
            }
            else if (($limits['todays_number_of_transactions']) >= ($limits['daily_number_of_transactions_allowed'])) {
                $api_error = "Your number of transaction per day has exceeded";
            }
            else if (($limits['todays_total_transaction_amount']) >= ($limits['daily_transaction_limit'])) {
                $api_error = "Your daily transaction amount limit has reached";
            }
            /*
             * Validation of parameters . Mandatory and optional
             * */
            $dataMandatory = array('email'=>1,
                                    'mobileNo'=>1,
                                    'addr'=>1,
                                    'currency_code'=>1,
                                    'checksum_method'=>1,
                                    'zipcode'=>1,
                                    'f_name'=>1,
                                    'city'=>1,
                                    'state'=>1,
                                    'country'=>1,
                                    'amount'=>1,
                                    'show_shipping_addr'=>0,
                                    'l_name'=>0,
                                    'shipping_addr'=>0,
                                    'shipping_city'=>0,
                                    'shipping_state'=>0,
                                    'shipping_country'=>0,
                                    'shipping_email'=>0,
                                    'shipping_mobileNo'=>0,
                                    'shipping_zipcode'=>0,
                                    'surl'=>0,
                                    'furl'=>0,
                                    'rurl'=>0,
                         );//array of mandatory field
            $transaction_error=array();//array to contain error
            foreach($data as $key=>$value){
                if (array_key_exists($key, $dataMandatory)) {
                    unset($dataMandatory[$key]);

                    if(!call_user_func('validateIncomingData', $key ,$value)){//i call valide method
                        $transaction_error[] = 'Invalid '.$key;
                    }
                }
            }
            if(count($dataMandatory)!==0){
                foreach($dataMandatory as $key=>$value){
                    if($value == 1)
                    {
                         $transaction_error[] = 'Mandatory Parameter missing : '.implode(',',array_flip ($dataMandatory));
                    }
                }
            }
            if(isset($api_error)) // IF there are API error pages are rendered
            {
                $api_error_array['api_error'] = $api_error;
                $this->render_api_errors($api_error_array);
            }
            else if (isset($transaction_error) AND $transaction_error != null) { //If there are transactional errors,user is redirected back to mercahnt response url
                $this->render_transactional_error($transaction_error);
            } else {
                $this->process($data); //Calls the process function when everything is done
            }
        } else {
            echo "Unauthorized access!";
        }
    }
    function process($request_data) // After input validation all the data comes here
    {
        $request_data['shmart_refID'] = $this->paymentgateway->generateRefID();
        /*Generates shmart_refID for the transaction*/
        $request_data['total_amount'] = $request_data['amount'];
        $additional_params_list       = array(
                                                'f_name',
                                                'l_name',
                                                'addr',
                                                'city',
                                                'state',
                                                'country',
                                                'zipcode',
                                                'shipping_addr',
                                                'shipping_city',
                                                'shipping_state',
                                                'shipping_country',
                                                'shipping_zipcode',
                                                'product_name',
                                                'product_description',
                                                'udf1',
                                                'udf2',
                                                'udf3',
                                                'udf4',
                                                'currency_code',
                                                'merchant_id',
                                                'shmart_refID',
                                                'checksum_method',
                                                'rurl',
                                                'surl',
                                                'furl',
                                            );
        $additional_params            = array_intersect_key($request_data, array_flip($additional_params_list));
        $this->paymentgateway->setTransactionAdditionalParameters($additional_params);
        $temp_var        = array(
            'shmart_refID',
            'email',
            'mobileNo',
            'total_amount',
            'merchant_id',
            'app_id',
            'app_used',
            'merchant_refID',
            'ip_address',
        );
        $temp_trans_data = array_intersect_key($request_data, array_flip($temp_var));
        $this->paymentgateway->setTempTransactionData($temp_trans_data);
        $this->paymentgateway->setInitiateMainTransactionData($temp_trans_data);

        $this->authenticateCustomerFetchData($request_data);
    }
/*
 * From the incoming request checks if customer exists
 * if exists check if he is logged in
 * if not show authentication page
 * If logged in show payment page
 * Input : email,mobileNo,shmart_refID
*/
    function authenticateCustomerFetchData($request_data) //Pass Email or Mobile number
    {
        $customer_details['shmart_refID'] = $request_data['shmart_refID'];
        $customer_details['merchant_id'] = $request_data['merchant_id'];
        $customer_data                = $this->paymentgateway->getConsumerExists($request_data);
        if ($customer_data) // Check if the customer is a shmart user.If he is then pull out all his details out
        {
            /* Check if the customer is having a voucher balance using the Shmart Wallet API */
        $voucher_balance = $this->wallet_shmart->voucherWalletBal($request_data);
        if($customer_data['customer_total_voucher_balance'] == $voucher_balance['AvailableBalance'] )
           {
               if($voucher_balance['AvailableBalance'] != '0.00')
                   {
                       /*IF customer has a balance,iterate through all his unexpired vouchers as in the customer_database/ vouchers table */
                       $voucher_amount_total = '0';
                       // foreach($customer_data['voucher_data_array'] as $voucher) {
                           // ($voucher_amount_total +=  $voucher['voucher_amount']);
                       // }
					   $voucher_amount_total = $customer_data['customer_total_voucher_balance'];

                   }
                else
                {
                    /* Else set the voucher amount as 0.00 */
                    $voucher_amount_total = '0.00';
                }
           }
            else
            {
                $voucher_amount_total = '0.00';
            }
            /* Check the customers general balance ; Balanced fetched using Shmart API */
            $general_balance_array = $this->wallet_shmart->generalWalletBal($request_data);
           if($general_balance_array['AvailableBalance'] != '0.00') //Get general balance
           {
               /* If yes , get his balance to an array */
               $general_balance = $this->wallet_shmart->generalWalletBal($request_data);
			   $pending_transfer_balance = $this->consumer_model->getPendingTransferBalance($request_data['mobileNo']);
			   $general_balance['AvailableBalance'] = ($general_balance_array['AvailableBalance'] - $pending_transfer_balance);
           }
           else
           {
                /* Else set the general balance as 0.00 */
               $general_balance['AvailableBalance'] = '0.00';
           }

            /* Adds voucher amounts with the current available general wallet balance */
                $request_data['wallet_amount'] = $voucher_amount_total + $general_balance['AvailableBalance'];
				 $request_data['wallet_amount'] =  number_format($request_data['wallet_amount'],2,'.','');
                $wallet_amount_arr        = array(
                    'shmart_refID',
                    'wallet_amount',
                    'merchant_id'
                );
                $set_wallet_amount = array_intersect_key($request_data, array_flip($wallet_amount_arr));
                $this->paymentgateway->setTempTransactionData($set_wallet_amount);
                if ($customer_data['saved_nb_array']) { //If customer has any saved net banking
                }
                if ($customer_data['saved_cards']) { //If  customer has any saved cards
                }
                if ($customer_data['saved_address_array']) { //If customer has any saved shipping address
                }
                //Show Shipping address page
                $customer_details['customer_data'] = $customer_data;
                $customer_details['authorize_user'] = '0';//$request_data['authorize_user'];
                if($this->tank_auth->get_username() == $request_data['mobileNo'])
                {
                    $customer_details['is_logged_in'] =1;
                }
                else
                {
                    $customer_details['is_logged_in'] =0;
                    $this->tank_auth->logout();
                }
                $this->render_payment_page($customer_details);

        } else {
            $request_data['customer_data'] = 0;
            $request_data['is_logged_in'] = 0;
            $this->render_payment_page($request_data);
        }
    }
    function render_payment_page($customer_details)
    {
        $net_banking_list = $this->paymentgateway->getNetBankingList();
        $payment_page_data                     = $this->paymentgateway->getTempTransactionData($customer_details['shmart_refID']);
        $payment_page_data['customer_details'] = $customer_details;
        $payment_page_data['net_banking_array'] = $net_banking_list;
        $payment_page_data['shmart_refID'] = $customer_details['shmart_refID'];
		$isWhitelabel = $this->paymentgateway->isWhitelabel($customer_details['merchant_id']);
		$payment_page_data['whitelabel'] =  $isWhitelabel;
		$payment_page_data['consumer_fund'] =  '0';
		$payment_page_data['merchant_name'] =  $this->paymentgateway->getMerchantName($customer_details['merchant_id']);
        $this->load->view('payment_page_consumer/header');
        $this->load->view('payment_page_consumer/body',$payment_page_data);
        $this->load->view('payment_page_consumer/footer');
    }
    function render_api_errors($api_error_array)
    {
        $this->load->view('custom_errors/checkout/header.php');
        $this->load->view('custom_errors/checkout/body.php',$api_error_array);
        $this->load->view('custom_errors/checkout/footer.php');
    }
    function render_transactional_error($transaction_error)
    {
        $transaction_error = implode(",", $transaction_error);
        $api_error_array['api_error'] = $transaction_error;
        $this->load->view('custom_errors/checkout/header.php');
        $this->load->view('custom_errors/checkout/body.php',$api_error_array);
        $this->load->view('custom_errors/checkout/footer.php');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */