<?php

class SMSified {
	protected $user_agent = 'YOUR APP NAME';
	
	// this will always contain the last post response.  If it was successful, it will be an array.
	public $response;
	
	// contains the curl error code (mainly for debugging)
	public $error_code;
	
	
	
	// Used to setup the class.  You only need to provide the username/password/number if you are sending.
	public function __construct($username='', $password='', $smsified_number='') {
		$this->username = $username;
		$this->password = $password;
		$this->smsified_number = $smsified_number;
	}
	
	// This is used for changing our phone number to others within our account (prior to a SendSMS, for example)
	public function ChangeSMSifiedNumber($phonenumber) {
		$this->smsified_number = $phonenumber;
	}
	
	// used to send an SMS
	// (string)			$number_from = The number assigned to you from SMSified
	// (string / array)	$number_to   = the full phone number(s) of the person you want to send an SMS to
	// (string)			$message     = the actual content of the SMS
	public function SendSMS($number_to, $message) {
		$smsified_url = 'https://api.smsified.com/v1/smsmessaging/outbound/' . $this->smsified_number . '/requests';

		return $this->SendSMSPost($smsified_url, array('address'=>$number_to, 'message'=>$message));
	}
	
	// This is used for recieving inbound SMS's and turning it into a useable array of info
	public function ReceiveSMS() {
		return json_decode(file_get_contents("php://input"), true);
	}
	
	private function SendSMSPost($url, $fields) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);  
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

		$this->error_code = curl_errno($ch);
		if (($this->error_code == 6) OR ($this->error_code == 7)) {
			$this->response = '';
			return false;
		}
		else {
			$response = curl_exec($ch);
			if (trim($response) == '') {
				$this->response = '';
				return false;
			}
			else {
				$response_decoded = json_decode($response, true);
				$this->response = $response_decoded;
				
				if (isset($response_decoded['resourceReference']['resourceURL'])) {
					return (string)end(explode('/',$response_decoded['resourceReference']['resourceURL']));
				}
				else {
					return false;
				}
			}
		}
		curl_close($ch);
	}
}

?>