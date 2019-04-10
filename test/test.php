<?php

require __DIR__ . '/../vendor/autoload.php';

$message = new stdClass();
$message->to = '0419140683';
$message->message = 'The text you want to filter goes here. http://google.com and https://www.youtube.com/watch?v=K_m7NEDMrV0 and https://instagram.com/hellow/';

try{
    $sent = \SendSMS\Service::message($message->to,$message->message)->shortenUrls()->send();
} catch (Exception $e){
    $sent = false;
    http_response_code(500);
}