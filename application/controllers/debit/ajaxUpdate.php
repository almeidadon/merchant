<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class AjaxUpdate extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper( array('url', 'payment_gateway_helper', 'basic_helper') ); 
		$this->load->library('paymentgateway');
		$this->load->library('notification_lib');
		$this->load->library('tank_auth');
	}
	
	function useWallet()
	{
		echo $this->paymentgateway->setUseWallet($this->input->post('use_wallet'),$this->input->post('shmart_refID'));
	}
    function generateOtp()
    {
        echo $this->notification_lib->generateOtp($this->input->post('mobileNo'));
    }
    function validateOtp()
    {
        $data['mobileNo'] = $this->input->post('mobileNo');
        $data['otp'] = $this->input->post('otp');
        echo $this->notification_lib->validateOtp($data);
    }
    function loginUsingOtp()
    {
        $data['mobileNo'] = $this->input->post('mobileNo');
        $data['otp'] = $this->input->post('otp');
        echo $this->tank_auth->login_by_otp($data['mobileNo'], $data['otp']);
    }
    function loginUsingPassword()
    {
        $data['mobileNo'] = $this->input->post('mobileNo');
        $data['password'] = $this->input->post('password');
        echo $this->tank_auth->login($data['mobileNo'], $data['password']);
    }
    function getTempTransactionData()
    {
        $temp_data = $this->paymentgateway->getTempTransactionData($this->input->post('shmart_refNo'));
       echo json_encode($temp_data);
    }
    function getConsumerExists()
    {
        $data['mobileNo'] = $this->input->post('mobileNo');
        $data['email'] = $this->input->post('email');
        $cust_data = $this->paymentgateway->getConsumerExists($data);
        echo json_encode($cust_data);
    }
}