<?php

class WhatsAppHelpers {
    private static $apiKey = '87zD9UdZDE-hsuXcR5S5';
    private static $apiUrl = 'https://api.fonnte.com/send';

    private static function sendRequest($data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . self::$apiKey
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            throw new Exception('Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

    public static function sendMessage($to, $message) {
        $data = array(
            'target' => $to,
            'message' => $message
        );

        try {
            $response = self::sendRequest($data);
            return json_decode($response, true);
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
}


// Usage Example
// $fonnte = new FonnteWhatsApp('YOUR_API_KEY');
// $response = $fonnte->sendMessage('recipient_phone_number', 'Hello from Fonnte!');
// print_r($response);
