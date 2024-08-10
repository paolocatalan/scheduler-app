<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;

class Timezone
{
    public function select()
    {
        if (Session::has('timezone')) {
            return Session::get('timezone');
        }

        $timezone = $this->lookupTimezone();
        Session::put('timezone', $timezone);

        return $timezone;
    }

    private function lookupTimezone()
    {
        try {
            $query = $this->remoteAddr();

            $parameters = [
                'fields' => 'status,message,timezone'
                ];

            $apiUrl = 'http://ip-api.com/json/' . $query . '?' . http_build_query($parameters);

            $jsonData = file_get_contents($apiUrl);

            $responseData = json_decode($jsonData);

            if ($responseData->status == 'fail') {
                throw new Exception('Timezone error: '. $responseData->message);
            }

            return $responseData->timezone;

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return config('app.timezone_display');
        }
    }

    private function remoteAddr()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        
        // $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_IPCOUNTRY"];

        $_SERVER['REMOTE_ADDR'] = '176.99.250.88';

        return $_SERVER['REMOTE_ADDR'];
    }

}