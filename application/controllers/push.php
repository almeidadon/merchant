<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Push extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('android_push_notification');
    }

    function index()
    {
		$this->load->view('tokenex');
	}
	function tokenex()
	{
		$p_data['encrypted_data'] = $this->input->post('shmart_cipherText');
		
		$tokenex_id =  "5657631072154893"; // "3960164022169120"; //Tokenex ID
		$api_key = "ID1mzx3OuyoxfAd1IsDA";//"bhemmC3p9AdxRrZcm8s1";	//Tokenex apikey
		//Payload to be sent to tokenex
		$data = array(
			'TokenExID' => $tokenex_id,
			'APIKey' => $api_key,
			'EcryptedData' => $p_data['encrypted_data'],
			'TokenScheme' => 4
		);
	   //convert to JSON
		$json = json_encode($data);
		print_r(	$json);
	  //curl config
		$ch = curl_init("https://api.tokenex.com/TokenServices.svc/REST/TokenizeFromEncryptedValue");
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
		//echo "URL error: ",curl_error($ch),PHP_EOL; 
		if(curl_errno($ch)){
		$output = curl_error($ch);
		} else {
		 $tokenex = json_decode($outputjson);
		}		   
		$tokenex_data['error'] = $tokenex->{'Error'};
		$tokenex_data['success'] = $tokenex->{'Success'};
		$tokenex_data['token'] =  $tokenex->{'Token'}; 
		$tokenex_data['token_refNo'] = $tokenex->{'ReferenceNumber'};
		print_r($outputjson);die;
		$this->ci->logging_model->tokenexLogging($tokenex_data);
		if($tokenex_data['success']=='1')
		{
			return $tokenex_data['token'];
		} else {
			return 0;
		}
	}
}