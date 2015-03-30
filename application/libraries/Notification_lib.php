<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Notification_lib
{	
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('payment_gateway/logging_model');
		$this->ci->load->model('payment_gateway/payment_gateway_model');
		$this->ci->load->model('payment_gateway/consumer_model');
        $this->ci->load->library('email');
        $this->ci->load->library('tank_auth');
	}
	function sentSMS($data)																					//1
	{
        $ch = curl_init();
        $url = 'http://myvaluefirst.com/smpp/sendsms?username=transervhttp&password=transerv33&to=91'.$data['mobileNo'].'&from=SHMART&udh=0&text='.urlencode('Dear Customer, your Shmart OTP is'.$data['otp'].'. Happy Transacting');
        // $url = 'http://api2.myvaluefirst.com/smpp/sendsms?username=transervhttp&password=transerv33&to=91'.$data['mobileNo'].'&from=SHMART&text='.urlencode('Dear Customer, your Shmart OTP is'.$data['otp'].'. Happy Transacting');
       
	   curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //  curl_setopt($ch,CURLOPT_HEADER, false);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
	}
    function sentEmail($data)
    {
        $this->ci->email->from('no-reply@shmart.in', 'Shmart!');
        $this->ci->email->to($data['email']);
        $this->ci->email->subject($data['subject']);
        $this->ci->email->message($data['message']);
        if($this->ci->email->send())
        {
            $response['response'] = 'Email sent to'.$data['email'];
        } else {
            $response['response'] = 'Email NOT sent to'.$data['email'];
        }
        $this->ci->logging_model->emailLogging($response);
    }
	function signUpNotify($data)
	{
	
		$ch = curl_init();
        // $url = 'http://myvaluefirst.com/smpp/sendsms?username=transervhttp&password=transerv33&to=91'.$data['mobileNo'].'&from=SHMART&udh=0&text='.urlencode('Welcome to Shmart. Your account details: User Id:'.$data['mobileNo'].'Pwd:'.$data['password'].'Email:'.$data['email']);
        $url = 'http://myvaluefirst.com/smpp/sendsms?username=transervhttp&password=transerv33&to=91'.$data['mobileNo'].'&from=SHMART&udh=0&text='.urlencode("Welcome to Shmart! Now Pay or Share money on Mobile thru your own Shmart! Wallet. Pls note ID ".$data['mobileNo']." Password".$data['password']." Call 02263704948 for assist");
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //  curl_setopt($ch,CURLOPT_HEADER, false);
        $output=curl_exec($ch);
        curl_close($ch);
		return true;
	}
	
	function transactionConfirmationSMS($data)
	{
	
		$ch = curl_init();
        $url = 'http://myvaluefirst.com/smpp/sendsms?username=transervhttp&password=transerv33&to=91'.$data['mobileNo'].'&from=SHMART&udh=0&text='.urlencode("Hello, Your payment for Rs. ".$data['total_amount']." to ".$data['merchant_website']." confirmed. Thank you for using Shmart!. Card or Bank stmt will show payment to 'TranServ-Esscom'/'Atom'.");
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //  curl_setopt($ch,CURLOPT_HEADER, false);
        $output=curl_exec($ch);
		// print_r( $output);
        curl_close($ch);
		return true;
	}
	
	function generateOtp($mobileNo)
	{
	
	$otp_data['mobileNo']= $mobileNo;
	$otp_data['otp'] = mt_rand(100000, 999999);
	
		if( $this->ci->consumer_model->generateOtp($otp_data))
		{
			$this->sentSMS($otp_data);
		} else {
			return 0;
		}

	}
	function validateOtp($data)
	{
	return $this->ci->consumer_model->validateOtp($data);
	}
}