<?php

/**
 * Created by PhpStorm.
 * User: NijiL
 * Date: 24/10/14
 * Time: 11:44 AM
 */

class V1 extends CI_Controller {

    /**
    API for collct transaction
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
        $collct_id = $this->uri->segment(4);
        $data = $this->paymentgateway->isValidCollctId($collct_id);
        if($data AND $data['collct_payment_status'] != 'PC' )
        {
          
			$this->process($data);
        }
        else
        {
              echo "This collect is already used up / Invalid";
        }
    }
	function process($request_data)  // After input validation all the data comes here
	{
		$collct_id=$this->uri->segment(4);
        $request_data['merchant_refID']  = $this->paymentgateway->generateRefID();
        $request_data['app_used']     = 'COLLECT'; //Hard code in each API
        $request_data['app_id']       = $collct_id; //APIKEY is app_id
        $request_data['checksum_method']     = 'MD5';
        $request_data['secretkey'] = 'DlTLWFWAX0E8RCR10H933JwP4IIy7lzC';
        $request_data['currency_code'] = 'INR';
        $request_data['authorize_user'] = '1';
        $request_data['amount'] = ($request_data['amount']*100);
        $request_data['ip_address']   = $_SERVER['REMOTE_ADDR'];
        $data['input_string'] = $request_data['merchant_id'].'|'.$request_data['app_id'].'|'.$request_data['merchant_refID'].'|'.$request_data['currency_code'].'|'.$request_data['amount'].'|'.$request_data['checksum_method'].'|'.$request_data['authorize_user'] ;
        $request_data['checksum'] = md5($request_data['secretkey'].$data['input_string']);
        if ($request_data['channel_of_request']=='EMAIL')
        {
            $request_data['email'] = $request_data['email_or_mobileNo'];
            $request_data['mobileNo'] = '';
            $this->render_collect_mobileNo($request_data);
        }
        else
        {
            $request_data['mobileNo'] = trim($request_data['email_or_mobileNo']);
            $request_data['email'] = '';
            echo '<html>
                <body>
                <form id="pay" action="'.base_url().'collect/processCollect/transactions" method="POST" >
                    <input type="hidden" name="merchant_refID" value="'.$request_data['merchant_refID'].'" />
                    <input type="hidden" name="app_used" value="'.$request_data['app_used'].'" />
                    <input type="hidden" name="apikey" value="'.$request_data['app_id'].'" />
                    <input type="hidden" name="checksum_method" value="'.$request_data['checksum_method'].'" />
                    <input type="hidden" name="checksum" value="'.$request_data['checksum'].'" />
                    <input type="hidden" name="merchant_id" value="'.$request_data['merchant_id'].'" />
                    <input type="hidden" name="secretkey" value="'.$request_data['secretkey'].'" />
                    <input type="hidden" name="currency_code" value="'.$request_data['currency_code'].'" />
                    <input type="hidden" name="authorize_user" value="'.$request_data['authorize_user'].'" />
                    <input type="hidden" name="ip_address" value="'.$request_data['ip_address'].'" />
                    <input type="hidden" name="amount" value="'.$request_data['amount'].'" />
                    <input type="hidden" name="email" value="" />
                    <input type="hidden" name="mobileNo" value="'.$request_data['mobileNo'].'" />
                     <input type="hidden"name="f_name"   value="invoz"  />
                    <input type="hidden"name="addr"   value="invoiz"  />
                    <input type="hidden"name="city"   value="invoiz"  />
                    <input type="hidden"name="state"   value="invoiz"  />
                    <input type="hidden"name="country"   value="invoiz"  />
                    <input type="hidden"name="show_shipping_addr"   value="0"  />
                    <input type="hidden"name="zipcode"   value="111111"  />
                </form>
                <script>document.getElementById("pay").submit();</script>
                </body>
                </html>';
        }




	}
    function render_collect_mobileNo($request_data)
    {
        $this->load->view('collect_mobileNo/header');
        $this->load->view('collect_mobileNo/body',$request_data);
        $this->load->view('collect_mobileNo/footer');
    }
}