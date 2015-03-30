<?php

/**
 * Created by PhpStorm.
 * User: NijiL
 * Date: 24/10/14
 * Time: 11:44 AM
 */

class V1 extends CI_Controller {

    /**
    API for buttn transaction
     */


    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->helper( array('url', 'payment_gateway_helper', 'basic_helper','form') );
        $this->load->library('paymentgateway');
        $this->load->library('wallet_shmart');
        $this->load->library('encrypt');
        $this->load->library('notification_lib');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $ip_user = $this->input->ip_address();

    }

    public function id()
    {
        $store_id = $this->uri->segment(4);
        if(!$data = $this->paymentgateway->isValidWebstoreCode($store_id))
        {
            echo "invalid Store Code";
        }
        else
        {
            $this->process($data);
        }
//print_r($data); die;
    }
	function process($request_data)  // After input validation all the data comes here
	{
	
		$store_id = $this->uri->segment(4);
        $request_data['merchant_refID']  = $this->paymentgateway->generateRefID();
		$request_data['amount'] = '0';
		$request_data['email'] 		  = '' ;
		$request_data['mobileNo'] 	  = '';
		$request_data['app_id']       = $store_id; //APIKEY is app_id
        $request_data['ip_address']   = $_SERVER['REMOTE_ADDR'];
        $request_data['checksum_method']     = 'MD5';
        $request_data['secretkey'] = 'DlTLWFWAX0E8RCR10H933JwP4IIy7lzC';
        $request_data['currency_code'] = 'INR';
        $request_data['authorize_user'] = '1';
        $request_data['app_used'] = 'WEBSTORE';
        $this->render_collect_mobileNo($request_data);
	}
    function render_collect_mobileNo($request_data)
    {
        $this->load->view('collect_mobileNo/header');
        $this->load->view('collect_mobileNo/body',$request_data);
        $this->load->view('collect_mobileNo/footer');
    }
}