<?php
/*
	This is an example of how to receive an inbound SMS message via SMSified with this class.
*/

require('class.smsified.php');

// If you are only receiving inbound message in this script, you can omit the username, password, and phone number variables.
// It doesn't hurt to have them either. If you are sending and receiving, just use the same instance of the class.
$SMSified = new SMSified();
// $SMSified = new SMSified('YOUR_USERNAME', 'YOUR_PASSWORD', 'YOUR_ASSIGNED_PHONE_NUMBER');

// You just need to call one function to receive the SMS and convert it to an array.
$SMS_arr = $SMSified->ReceiveSMS();

// $SMS_arr has the SMS.
?>