<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config['protocol'] = "smtp";
$config['smtp_host'] = "smtp.mandrillapp.com";
$config['smtp_port'] = "587";
$config['smtp_user'] = "anish@transerv.co.in";//also valid  Google Apps Accounts 
$config['smtp_pass'] = "1Na0-RBfFKx7ktUOR7zErQ";
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */