<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Android_push_notification
{	
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('payment_gateway/logging_model');
		$this->ci->load->model('payment_gateway/payment_gateway_model');
		$this->ci->load->model('payment_gateway/consumer_model');
		$this->ci->load->model('payment_gateway/transaction_response_model');
        $this->ci->load->library('tank_auth');
        $this->ci->load->library('gcm');
	}
	function transaction_alert_merchant($response,$trans_data)																					//1
	{
		 $gcm_device_id = $this->ci->transaction_response_model->getGcmDeviceID($response['shmart_refID']);
    // simple adding message. You can also add message in the data,
    // but if you specified it with setMesage() already
    // then setMessage's messages will have bigger priority
        // $this->ci->gcm->setMessage('Hello hi');

    // add recepient or few
        $this->ci->gcm->addRecepient($gcm_device_id);
        // $this->ci->gcm->addRecepient('New reg id');

		if($response['status'] == '0')
			{
				$status = 'Success';
			}
			else 
			{
				$status = 'Failed';
			}
    // set additional data
        $this->ci->gcm->setData(array(
            'response_data' => $response['shmart_refID'].','.$response['total_amount'].','.$status.','.$trans_data['app_id'].','.$response['trans_completedTime'].','.$trans_data['request_channel']
        ));

    // also you can add time to live
        $this->ci->gcm->setTtl(500);
    // and unset in further
        $this->ci->gcm->setTtl(false);

    // set group for messages if needed
        // $this->ci->gcm->setGroup('Test');
    // or set to default
        $this->ci->gcm->setGroup(false);
		$this->ci->gcm->send();
    // // then send
        // if ($this->ci->gcm->send())
            // echo 'Success for all messages';
        // else
            // echo 'Some messages have errors';

    // // and see responses for more info
        // print_r($this->ci->gcm->status);
        // print_r($this->ci->gcm->messagesStatuses);

    // die(' Worked.');
		
		
	}
}