<?php
/**
 * This file builds the Helper functions used for merchant integration.
 * The file is responsible for validation and verification of certain fields like email, mobile no etc.
 *
 * @category   General
 * @package    Helper Functions
 * @author     Nijil Vijayan <nijil.vijayan@transerv.co.in>
 * @version    1.0
 * @since      Initial release
 */

//Validates Email
function validateEmail($email)
	{
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		return (filter_var($email, FILTER_VALIDATE_EMAIL))?1:0;
	}
	
//Validates Mobile Number	
function validateMobileNo($mobileNo)
	{
		return (preg_match('/^[0-9]{10}$/',$mobileNo))?1:0;
	}
	
//Validates Currency Code	
function validateCurrencyCode($currency)
	{
	 if($currency == 'INR')
		{	
			return 1;
		}else
		{
			return 0;
		}
	}
	
	//Validates Currency Code	
function validateCurrency_code($currency)
	{
	 if($currency == 'INR')
		{	
			return 1;
		}else
		{
			return 0;
		}
	}

//Validates Checksum Method	
function validateChecksumMethod($checksum_method)
	{
	 if($checksum_method == 'HMAC'|| $checksum_method == 'MD5' || $checksum_method == 'SHA256')
		{	
			return 1;
		}else
		{
			return 0;
		}
	}
//Validates Checksum Method	
function validateChecksum_method($checksum_method)
	{
	 if($checksum_method == 'HMAC'|| $checksum_method == 'MD5' || $checksum_method == 'SHA256')
		{	
			return 1;
		}else
		{
			return 0;
		}
	}
	
//Validates Zip Code	
function validateZipCode($zip)
	{
		return (preg_match('/^[0-9]{6}$/', $zip))?1:0;
	}
// function validateZipcode($zip)
	// {
		// return (preg_match('/^[0-9]{6}$/', $zip))?1:0;
	// }
	function validateShipping_zipcode($zip)
	{
		return (preg_match('/^[0-9]{6}$/', $zip))?1:0;
	}

//Validates Name	
function validateAlphaString($string)
	{
		return (preg_match("/^[a-zA-Z'-]+$/", $string))?1:0;
	}
	
	//Validates Name	
function validateF_name($string)
	{
		return (preg_match("/^[a-zA-Z'-]+$/", $string))?1:0;
	}
	//Validates Name	
function validateL_name($string)
	{
		return (preg_match("/^[a-zA-Z'-]+$/", $string))?1:0;
	}
	
		//Validates Name	
function validateAddr($string)
	{
		return (preg_match("/^[a-z0-9- ,]+$/i", $string))?1:0;
	}
function validateShipping_addr($string)
	{
		return (preg_match("/^[a-z0-9- ,]+$/i", $string))?1:0;
	}
function validateShow_shipping_addr($string)
	{
		return (preg_match('/^[0-1]{1}$/', $string))?1:0;
	}
			//Validates Name	
function validateCity($string)
	{
		return (preg_match("/^[a-z0-9- ]+$/i", $string))?1:0;
	}
function validateShipping_city($string)
	{
		return (preg_match("/^[a-z0-9- ]+$/i", $string))?1:0;
	}
	function validateShipping_mobileNo($string)
	{
		return (preg_match('/^[0-9]{10}$/',$string))?1:0;
	}
			//Validates Name	
function validateState($string)
	{
		return (preg_match("/^[a-z0-9- ]+$/i", $string))?1:0;
	}
	function validateShipping_state($string)
	{
		return (preg_match("/^[a-z0-9- ]+$/i", $string))?1:0;
	}
			//Validates Name	
function validateCountry($string)
	{
		return (preg_match("/^[a-z0-9- ]+$/i", $string))?1:0;
	}
function validateShipping_country($string)
	{
		return (preg_match("/^[a-z0-9- ]+$/i", $string))?1:0;
	}
	
//Verifies passed info is 1/0
function verifyBinary($BinValue)
	{
		return (preg_match('/^[0-1]{1}$/', $BinValue))?1:0;
	}
//Verifies passed amount is a valid amount and converts it into rupees
function verifyAmount($amount)
	{
		if (preg_match('/^[1-9][0-9]{0,10}$/', $amount)) {
				return number_format((float)($amount)/100, 2, '.', '');   
			} else {
				return 0;
			}
	}
//Verifies url
function validateFurl($incoming_url)
	{
	   $url = filter_var($incoming_url, FILTER_SANITIZE_URL);
	   if (filter_var($url, FILTER_VALIDATE_URL)) {
		   return 1;				   
		} else  {
				if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
						   $valid_url = "http://".$url;
						   return $valid_url;
				}
		}
	} 
	function validateSurl($incoming_url)
	{
	   $url = filter_var($incoming_url, FILTER_SANITIZE_URL);
	   if (filter_var($url, FILTER_VALIDATE_URL)) {
		   return 1;				   
		} else  {
				if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
						   $valid_url = "http://".$url;
						   return $valid_url;
				}
		}
	}
	function validateRurl($incoming_url)
	{
	   $url = filter_var($incoming_url, FILTER_SANITIZE_URL);
	   if (filter_var($url, FILTER_VALIDATE_URL)) {
		   return 1;				   
		} else  {
				if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
						   $valid_url = "http://".$url;
						   return $valid_url;
				}
		}
	}
	
//Verifies url
function cleanUrl($incoming_url)
	{
	   $url = filter_var($incoming_url, FILTER_SANITIZE_URL);
	   if (filter_var($url, FILTER_VALIDATE_URL)) {
		   return 1;				   
		} else  {
				if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
						   $valid_url = "http://".$url;
						   return $valid_url;
				}
		}
	} 

//validates cvv , accepts only 3 digits or 4 digits
function validateCVV($cvv)
	{
		return (preg_match("/^[0-9]{3,4}$/",$cvv))?1:0;
	}	

//validates expiry month and year
function validateExpiry($data)
	{
		$expires = \DateTime::createFromFormat('my', $data['expiry_month'].$data['expiry_year']);
		$now     = new \DateTime();
		return ($expires >= $now)?1:0;
	}
	
//Validates Card Type	
function validateCardType($cardType)
	{
	 return (($cardType == 'CC') || ($cardType == 'DB'))?1:0 ;
	}
//Validates Card Provider
function validateCardProvider($cardProvider)
	{
	return (($cardProvider == 'VISA') || ($cardProvider == 'MC')|| ($cardProvider == 'MAEST')|| ($cardProvider == 'SBIME'))?1:0 ;
	}

function validateIncomingData($parameter , $value)
{
	switch ($parameter)
	{
		case 'f_name': return (preg_match("/^[a-zA-Z'-]+$/", $value))?1:0;
		break;
		case 'mobileNo': if($value!=null) {  return (preg_match('/^[0-9]{10}$/',$value))?1:0; }else { return 1;}
		break;
		case 'email': if($value!=null) { return (filter_var($value, FILTER_VALIDATE_EMAIL))?1:0;}else { return 1;}
		break;
		case 'addr': return 1;
		break;
		case 'city': return (preg_match("/^[a-z0-9- ]+$/i", $value))?1:0;
		break;
		case 'state': return (preg_match("/^[a-z0-9- ]+$/i", $value))?1:0;
		break;
		case 'country': return (preg_match("/^[a-z0-9- ]+$/i", $value))?1:0;
		break;
		case 'zipcode': return (preg_match('/^[0-9]{6}$/', $value))?1:0;
		break;
		case 'currency_code': return ($value == 'INR')?1:0;
		break;
		case 'checksum_method': return ($value == 'HMAC'|| $value == 'MD5' || $value == 'SHA256')?1:0;
		break;
		case 'show_shipping_addr': return (preg_match('/^[0-1]{1}$/', $value))?1:0;
		break;
		case 'shipping_email': return (filter_var($value, FILTER_VALIDATE_EMAIL))?1:0;
		break;
		case 'shipping_mobileNo':  if($value!=null) { return (preg_match('/^[0-9]{10}$/',$value))?1:0; } else { return 1;}
		break;
		case 'shipping_addr': if($value!=null) { return (preg_match("/^[a-z0-9- ,]+$/i", $value))?1:0; } else { return 1;} 
		break;
		case 'shipping_city': if($value!=null) { return (preg_match("/^[a-z0-9- ]+$/i", $value))?1:0; } else { return 1;} 
		break;
		case 'shipping_state': if($value!=null) { return (preg_match("/^[a-z0-9- ]+$/i", $value))?1:0; } else { return 1;} 
		break;
		case 'shipping_country': if($value!=null) { return (preg_match("/^[a-z0-9- ]+$/i", $value))?1:0; } else { return 1;} 
		break;
		case 'shipping_zipcode': if($value!=null) { return (preg_match('/^[0-9]{6}$/', $value))?1:0; } else { return 1;} 
		break;
		case 'furl':
		case 'surl':
		case 'rurl':
					if($value!=null) {
					$value = filter_var($value, FILTER_SANITIZE_URL);
					if (filter_var($value, FILTER_VALIDATE_URL)) {
					   return 1;				   
					} else  {
						return 0;
					}
					} else { return 1;}
		break;
		case 'l_name': if($value!=null) { return (preg_match("/^[a-zA-Z'-]+$/", $value))?1:0; } else { return 1;} 
		break;
		case 'amount': return 1;
		
	}
}

function convertPaisaToRupee($value) 
	{
		return number_format((float)($value)/100, 2, '.', '');   
	}

?>