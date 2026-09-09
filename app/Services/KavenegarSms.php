<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class KavenegarSms
{
    public function send(string $receptor, string $message): array
    {
        $apiKey = (string) config('services.kavenegar.api_key');
        if (!$apiKey) return [];

        $response = Http::asForm()->timeout(8)->retry(2, 200)->post("https://api.kavenegar.com/v1/{$apiKey}/sms/send.json", [
            'receptor' => $receptor,
            'sender' => config('services.kavenegar.sender'),
            'message' => $message,
        ])->throw()->json();

        if ((int) data_get($response, 'return.status') !== 200) throw new RuntimeException((string) data_get($response, 'return.message', 'Kavenegar error'));
        return (array) data_get($response, 'entries', []);
    }

    public function verifyLookup(string $receptor, string $token, ?string $template = null): array
    {
        $apiKey = (string) config('services.kavenegar.api_key');
        if (!$apiKey) return [];
        $response = Http::asForm()->timeout(8)->post("https://api.kavenegar.com/v1/{$apiKey}/verify/lookup.json", [
            'receptor' => $receptor,
            'token' => $token,
            'template' => $template ?: config('services.kavenegar.verify_template'),
        ])->throw()->json();
        return (array) data_get($response, 'entries', []);
    }
}
