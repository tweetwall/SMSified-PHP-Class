<?php
/*
	This is an example of how to send a SMS message via SMSified with this class.
*/

require('class.smsified.php');

$SMSified = new SMSified('YOUR_USERNAME', 'YOUR_PASSWORD', 'YOUR_ASSIGNED_PHONE_NUMBER');

$SMSified_send = $SMSified->SendSMS('11_DIGIT_PHONE_NUMBER_TO','This is where you put your test message.');
if ($SMSified_send === false) {
	echo 'SMS Sending Failed!';
}
else {
	echo 'SMS Sent!';
	
	// since $SMSified_send is not false, it should contain the $requestId assigned by SMSified. Store if needed.
	echo $SMSified_send;
}
?>