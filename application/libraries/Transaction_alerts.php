<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Transaction_alerts
{
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('payment_gateway/logging_model');
        $this->ci->load->model('payment_gateway/payment_gateway_model');
        $this->ci->load->model('payment_gateway/consumer_model');
        $this->ci->load->library('email');
    }

    function customerTransactionEmailAlert($email_data)
    {
        $this->ci->email->from('no-reply@shmart.in', 'Shmart');
        $this->ci->email->to($email_data['merchant_email']);
        $this->ci->email->subject('Transaction alert');
        $emailBody = "<p>Hi Customer</p>
                                <p>A transaction of Rs ".$email_data['total_amount']." has been made</p>
                                <p>The details of the transaction are as follows</p>
                                <p>Merchant_refID : ".$email_data['merchant_refID']."</p>
                                <p>shmart_refID : ".$email_data['shmart_refID']." </p>
                                <p>Amount of transaction : ".$email_data['total_amount']." </p>
                                <p>Transaction status : ".$email_data['status_msg']."</p>
                                <p>Transaction mode : ".$email_data['transaction_mode']."</p>
                                <p>Card Type : ".$email_data['cardType']."</p>
                                <p>Card Provider : ".$email_data['cardProvider']."</p>
                                <p>Happy Transacting!</p>
                                <p>-Team Shmart</p>";
        $this->ci->email->message($emailBody);
        $this->ci->email->set_mailtype("html");
        $this->ci->email->send();
    }
    function merchantTransactionEmailAlert()
    {
        $this->ci->email->from('no-reply@shmart.in', 'Shmart');
        $this->ci->email->to($email_data['merchant_email']);
        $this->ci->email->subject('Transaction alert');
        $emailBody = "<p>Hi Merchant</p>
                                <p>A transaction of Rs ".$email_data['total_amount']." is made through your shmart account</p>
                                <p>The details of the transaction are as follows</p>
                                <p>Merchant_refID : ".$email_data['merchant_refID']."</p>
                                <p>shmart_refID : ".$email_data['shmart_refID']." </p>
                                <p>Email of customer : ".$email_data['email']."</p>
                                <p>Mobile number of customer : ".$email_data['mobileNo']."</p>
                                <p>Amount of transaction : ".$email_data['total_amount']." </p>
                                <p>Transaction status : ".$email_data['status_msg']."</p>
                                <p>Transaction mode : ".$email_data['transaction_mode']."</p>
                                <p>Card Type : ".$email_data['cardType']."</p>
                                <p>Card Provider : ".$email_data['cardProvider']."</p>
                                <p>Happy Transacting!</p>
                                <p>-Team Shmart</p>";
        $this->ci->email->message($emailBody);
        $this->ci->email->set_mailtype("html");
        $this->ci->email->send();
    }
    function customerSignUpEmail($email_data)
    {
        $this->ci->email->from('no-reply@shmart.in', 'Shmart');
        $this->ci->email->to($email_data['email']);
        $this->ci->email->subject('Your shmart wallet credentials');
        $emailBody = "<p>Hi Consumer,</p>
                        <p>Thank you for signing up for Shmart account.</p>
                        <p>Your Shmart account now make your online payments quicker, safer by enabling you to load money </p>
                        <p>to your prepaid account or save card details for faster express checkout or lets you instantly recieve </p>
                        <p>refunds and gifts. </p>
                        <p>Apart from enjoying safer and faster online payments,there is a lot more to your Shmart account.</p>
                        <p>It lets you send and recive money, Split payments with friends, Share gifts to friends or family using </p>
                        <p>the shmart digital wallet or Shop at over 2,00,000 online and offline merchants using the Shmart </p>
                        <p>Prepaid Visa Card. </p>
                        <p>Your shmart account details are as follows:</p>
                        <p>Login : ".$email_data['mobileNo']."</p>
                        <p>Shmart password : ".$email_data['password']."</p>
                        <p>You can use your Shmart password or Shmart OTP (6 digita OTP that recieves on your phone) to </p>
                        <p>transact across any merchant that accepts Shmart.</p>
                        <p>Please login to http://www.shmart.in to manage your Shmart account. </p>
                        <p>Happy Transactions,</p>
                        <p> - Team Shmart</p>";
        $this->ci->email->message($emailBody);
        $this->ci->email->set_mailtype("html");
        $this->ci->email->send();
//        echo $this->ci->email->print_debugger();

    }


}