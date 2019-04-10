<?php

// Get SMS message properties JSON as a string
$json = file_get_contents('php://input');

// Get as an object
$message= json_decode($json);


// todo send the message using the BurstSMS API

// if all ok respond with message sent JSON

$data = ['message'=>'message sent'];
header('Content-Type: application/json');
echo json_encode($data);

?>