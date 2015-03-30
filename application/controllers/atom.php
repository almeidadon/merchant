<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class Atom extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        define('ESS_MERCHANT_ID','ESSIZS0003');
        $this->load->helper(array('form', 'url', 'payment_gateway_helper'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->load->library('paymentgateway');
        $this->load->library('wallet_shmart');
        $this->load->library('notification_lib');
        $this->lang->load('tank_auth');
    }

    function processorResponseHandler()
    {
		print_r($_REQUEST);
	}
}