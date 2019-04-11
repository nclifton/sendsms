<?php

require __DIR__ . '/vendor/autoload.php';

// Get SMS message properties JSON as a string
$json = file_get_contents('php://input');

// Get as an object
$message = json_decode($json);

if ($message->to && $message->message){
    // prepare and send the message using the BurstSMS API
    try {
        $service = \SendSMS\Service::message($message->to, $message->message);
        // if all ok respond with message sent JSON
        if ($service->shortenUrls()->send()) {
            $data = ['message' => 'message sent'];
        } else {
            $data = $service->getError();
            http_response_code(400);

        }
        header('Content-Type: application/json');
        echo json_encode($data);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getmessage,'request'=>$message]);

    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'invalid request','request'=>$message]);

}


?>
