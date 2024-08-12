<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class EmailDeliverability
{
    public function state(string $email): bool
    {
        try {
            $handle = curl_init();

            $apiKey = env('EMAILABLE_TEST_KEY', 'EMAILABLE_LIVE_KEY');

            $parameters = [
                'email' => $email,
                'api_key' => $apiKey
            ];

            $url = 'https://api.emailable.com/v1/verify?' . http_build_query($parameters);

            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

            $content = curl_exec($handle);

            $data = json_decode($content, true);

            if (isset($data['message'])) {
                Throw new Exception('Emailable: ' . $data['message']);
            }

            if ($data['state'] == 'deliverable') {
                return true;
            } else if ($data['state'] == 'unknown' && $data['score'] > 70) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $th) {
            Log::error($th->getMessage());

            return false;
        }
    }
}
