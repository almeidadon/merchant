<?php

/**
 * Created by PhpStorm.
 * User: NijiL
 * Date: 24/10/14
 * Time: 11:44 AM
 */

class V1 extends CI_Controller {

    /**
    API for invoiz transaction
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
        $invoiz_id = $this->uri->segment(4);
		$data = $this->paymentgateway->isValidInvoiceId($invoiz_id);
        if($data AND $data['inv_payment_status'] != 'PC')
        {
			$this->process($data);
        }
        else
        {
			echo "Invalid or Invoiz payment made";
        }

    }
	function process($request_data)  // After input validation all the data comes here
	{
		$invoiz_id = $this->uri->segment(4);
        $request_data['merchant_refID']  = $this->paymentgateway->generateRefID();
		$request_data['amount'] = ($request_data['inv_amount']*100) ;
		$request_data['email'] 		  = $request_data['inv_custEmail'] ;
		$request_data['mobileNo'] 	  = '';
		$request_data['app_id']       = $invoiz_id; //APIKEY is app_id
        $request_data['ip_address']   = $_SERVER['REMOTE_ADDR'];
        $request_data['checksum_method']     = 'MD5';
        $request_data['secretkey'] = 'DlTLWFWAX0E8RCR10H933JwP4IIy7lzC';
        $request_data['currency_code'] = 'INR';
        $request_data['authorize_user'] = '1';
        $request_data['app_used'] = 'INVOIZ';
        $data['input_string'] = $request_data['merchant_id'].'|'.$request_data['app_id'].'|'.$request_data['merchant_refID'].'|'.$request_data['currency_code'].'|'.$request_data['amount'].'|'.$request_data['checksum_method'].'|'.$request_data['authorize_user'] ;
        $request_data['checksum'] = md5($request_data['secretkey'].$data['input_string']);
        $this->render_collect_mobileNo($request_data);
	}
    function render_collect_mobileNo($request_data)
    {
        $this->load->view('collect_mobileNo/header');
        $this->load->view('collect_mobileNo/body',$request_data);
        $this->load->view('collect_mobileNo/footer');
    }
}