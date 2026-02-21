<?php

namespace App\Services\OTP;

use App\Contracts\SendSms;

class Bdbulksms implements SendSms {
    
    public function send($to, $from, $text, $template_id)
    {
        // Get configuration from .env
        $token = env('BDBULKSMS_TOKEN');
        $apiUrl = env('BDBULKSMS_API_URL', 'https://api.bdbulksms.net/api.php?json');
        $outputType = env('BDBULKSMS_OUTPUT_TYPE', 'json');
        
        // Format phone number - remove + sign if present, handle multiple numbers
        // bdbulksms accepts comma-separated numbers
        if (strpos($to, '+') === 0) {
            $to = substr($to, 1);
        }
        
        // Remove spaces and ensure proper formatting
        $to = str_replace([' ', '-'], '', $to);
        
        // Prepare data array
        $data = array(
            'to' => $to,
            'message' => $text,
            'token' => $token
        );
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // Execute request
        $smsresult = curl_exec($ch);
        
        // Get any errors
        $error = curl_error($ch);
        
        // Close cURL
        curl_close($ch);
        
        // Return result or error
        if ($error) {
            return json_encode(['error' => $error, 'success' => false]);
        }
        
        return $smsresult;
    }
}

