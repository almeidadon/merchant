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

class ProcessInvoiz extends CI_Controller
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
        if ($this->input->server('REQUEST_METHOD') == 'GET' || $this->input->server('REQUEST_METHOD') == 'POST') {
            $data                        = $_POST;
            $data['input_string']        = $data['merchant_id'] . '|' . $data['apikey'] . '|' . $data['merchant_refID'] . '|' . $data['currency_code'] . '|' . $data['amount'] . '|' . $data['checksum_method'].'|'.$data['authorize_user'];
            $data['app_used']            = 'INVOIZ'; //Hard code in each API
            $data['app_id']            = $data['apikey']; //APIKEY is app_id
            $data['secretkey'] = 'DlTLWFWAX0E8RCR10H933JwP4IIy7lzC';
            $data['ip_address_customer'] = $_SERVER['REMOTE_ADDR'];
            $limits = $this->paymentgateway->getTransactionLimit($data['merchant_id']);
            $data['amount'] = verifyAmount($data['amount']);
            if ($data['amount'] == 0) {
                $api_error = "Amount should be in correct format";
            }else if (!$this->paymentgateway->isMerchantActive($data['merchant_id'])) {
                $api_error = "Not an active merchant";

            } else if (!$this->paymentgateway->isValidAppId($data)) {
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
            /* Validation of mandatory parameters starts here
             *email
             *mobileNo
             *currency_code
             *checksum_method
             *zipcode
             *f_name
             *addr - check if its null or not
             *city
             *state
             *country
             *show_shipping_addr- need to check
             *show_addr - need to check
             */
            $i = 0;
            if ($data['email'] AND array_key_exists('email', $data)) {
                if (!validateEmail($data['email'])) {
                    $transaction_error = 'Invalid email';
                }
            } else {
                $transaction_error = 'Parameter email is missing';
            }
            if (!($data['addr'] AND array_key_exists('addr', $data))) {
                $transaction_error= 'Parameter addr is missing';
            }
            if (array_key_exists('show_shipping_addr', $data)) {
                if (!verifyBinary($data['show_shipping_addr'])) {
                    $transaction_error = 'Invalid show_shipping_addr';
                }
            } else {
                $transaction_error = 'Parameter show_shipping_addr is missing';
            }
            if (array_key_exists('authorize_user', $data)) {
                if (!verifyBinary($data['authorize_user'])) {
                    $transaction_error = 'Invalid authorize_user';
                }
            } else {
                $transaction_error = 'Parameter authorize_user is missing';
            }
            if ($data['mobileNo'] AND array_key_exists('mobileNo', $data)) {
                if (!validateMobileNo($data['mobileNo'])) {
                    $transaction_error = 'Invalid mobileNo';
                }
            } else {
                $transaction_error = 'Parameter mobileNo is missing';
            }
            if ($data['currency_code'] AND array_key_exists('currency_code', $data)) {
                if (!validateCurrencyCode($data['currency_code'])) {
                    $transaction_error = 'Invalid currency_code';
                }
            } else {
                $transaction_error = 'Parameter currency_code is missing';
            }
            if ($data['checksum_method'] AND array_key_exists('checksum_method', $data)) {
                if (!validateChecksumMethod($data['checksum_method'])) {
                    $transaction_error = 'Invalid checksum_method';
                }
            } else {
                $transaction_error = 'Parameter checksum_method is missing';
            }
            if ($data['zipcode'] AND array_key_exists('zipcode', $data)) {
                if (!validateZipCode($data['zipcode'])) {
                    $transaction_error = 'Invalid zipcode';
                }
            } else {
                $errors[$i++] = 'Parameter zipcode is missing';
            }
            if ($data['f_name'] AND array_key_exists('f_name', $data)) {
                if (!validateAlphaString($data['f_name'])) {
                    $transaction_error = 'Invalid name';
                }
            } else {
                $transaction_error = 'Parameter f_name is missing';
            }
            if ($data['state'] AND array_key_exists('state', $data)) {
                if (!validateAlphaString($data['state'])) {
                    $transaction_error = 'Invalid state';
                }
            } else {
                $transaction_error = 'Parameter state is missing';
            }
            if ($data['country'] AND array_key_exists('country', $data)) {
                if (!validateAlphaString($data['country'])) {
                    $transaction_error = 'Invalid country';
                }
            } else {
                $transaction_error = 'Parameter country is missing';
            }
            if ($data['city'] AND array_key_exists('city', $data)) {
                if (!validateAlphaString($data['city'])) {
                    $transaction_error = 'Invalid city';
                }
            } else {
                $transaction_error = 'Parameter city is missing';
            }
            /* Validation of mandatory parameters ends here */
            /* Validation of optional parameters starts here
             *shipping_email
             *shipping_mobileNo
             *shipping_zipcode
             *l_name
             *shipping_addr 
             *shipping_city
             *shipping_state
             *shipping_country
             *surl
             *furl
             *rurl
             */
            if (array_key_exists('l_name', $data)) {
                if (!validateAlphaString($data['l_name'])) {
                    $transaction_error = 'Invalid l_name';
                }
            }
            if (array_key_exists('shipping_addr', $data)) {
                if (!$data['shipping_addr']) {
                    $transaction_error = 'Parameter shipping_addr is missing';
                }
            }
            if (array_key_exists('shipping_mobileNo', $data)) {
                if (!validateMobileNo($data['shipping_mobileNo'])) {
                    $transaction_error = 'Invalid shipping_mobileNo';
                }
            }
            if (array_key_exists('shipping_city', $data)) {
                if (!validateAlphaString($data['shipping_city'])) {
                    $transaction_error = 'Invalid shipping_city';
                }
            }
            if (array_key_exists('shipping_state', $data)) {
                if (!validateAlphaString($data['shipping_state'])) {
                    $transaction_error = 'Invalid shipping_state';
                }
            }
            if (array_key_exists('shipping_country', $data)) {
                if (!validateAlphaString($data['shipping_country'])) {
                    $transaction_error = 'Invalid shipping_country';
                }
            }
            if (array_key_exists('shipping_zipcode', $data)) {
                if (!validateZipCode($data['shipping_zipcode'])) {
                    $transaction_error = 'Invalid shipping_zipcode';
                }
            }
            if(isset($api_error)) // IF there are API error pages are rendered
            {
                $api_error_array['api_error'] = $api_error;
                $this->render_api_errors($api_error_array);
            }
            else if (isset($transaction_error)) { //If there are transactional errors,user is redirected back to mercahnt response url
                $this->redirect_transactional_error($data);
            } else {
                $this->process($data); //Calls the process function when everything is done
            }
        } else {
            echo "The request is NOT POST!";
        }
    }
    function process($request_data) // After input validation all the data comes here
    {
        $request_data['shmart_refID'] = $request_data['merchant_refID'];
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
        /*
         * Update payment inititated
         * */
        $this->paymentgateway->updateAppPaymentStatus($request_data['app_used'],$request_data['app_id'],'PI');

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
            $customer_details['authorize_user'] = $request_data['authorize_user'];
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
		$payment_page_data['whitelabel'] =  '0';
		$payment_page_data['merchant_name'] =  $this->paymentgateway->getMerchantName($customer_details['merchant_id']);
        $this->load->view('payment_page/header');
        $this->load->view('payment_page/body',$payment_page_data);
        $this->load->view('payment_page/footer');
    }
    function render_api_errors($api_error_array)
    {
        $this->load->view('custom_errors/checkout/header.php');
        $this->load->view('custom_errors/checkout/body.php',$api_error_array);
        $this->load->view('custom_errors/checkout/footer.php');
    }
    function redirect_transactional_error($data)
    {
        echo '<html>
                <body>
                <form id="pay" action="'.$data['response_url'].'" method="POST" >
                    <input type="hidden" name="status" value="-1" />
                    <input type="hidden" name="status_msg" value="'.$data['transaction_errors'].'" />
                    <input type="hidden" name="merchant_refID" value="'.$data['merchant_refID'].'" />
                    <input type="hidden" name="app_used" value="'.$data['app_used'].'" />
                    <input type="hidden" name="apikey" value="'.$data['apikey'].'" />
                </form>
                <script>document.getElementById("pay").submit();</script>
                </body>
                </html>';
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */