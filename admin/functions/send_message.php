<?php
function sendMessage($number, $message)
{
    $ch = curl_init();
    $parameters = array(
        'apikey' => '5fa6af02d001789f9ded2ed396fd3720',
        'number' => $number,
        'message' => $message,
        'sendername' => 'FATIMABHAUS',
    );
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);

    //Send the parameters set above with the request
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));

    // Receive response from server
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    //Show the server response
    return $output;
}
