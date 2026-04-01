<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendDocument(string $to, string $filePath, string $filename, string $caption = ''): array
    {
        $token = config('whatsapp.access_token');
        $phoneNumberId = config('whatsapp.phone_number_id');
        $version = config('whatsapp.api_version', 'v19.0');

        if (empty($token) || empty($phoneNumberId)) {
            return [
                'success' => false,
                'message' => 'WhatsApp config belum lengkap'
            ];
        }

        $mediaId = $this->uploadMedia($filePath, $filename, $version, $phoneNumberId, $token);
        if (!$mediaId) {
            return [
                'success' => false,
                'message' => 'Gagal upload media ke WhatsApp'
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'id' => $mediaId,
                'filename' => $filename
            ]
        ];

        if (!empty($caption)) {
            $payload['document']['caption'] = $caption;
        }

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->post($url, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('WhatsApp send message failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp send message exception: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Gagal mengirim pesan WhatsApp'
        ];
    }

    private function uploadMedia(string $filePath, string $filename, string $version, string $phoneNumberId, string $token): ?string
    {
        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/media";

        try {
            $response = Http::withToken($token)
                ->attach('file', file_get_contents($filePath), $filename)
                ->acceptJson()
                ->post($url, [
                    'messaging_product' => 'whatsapp'
                ]);

            if ($response->successful()) {
                $json = $response->json();
                return $json['id'] ?? null;
            }

            Log::error('WhatsApp upload media failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp upload media exception: ' . $e->getMessage());
        }

        return null;
    }
}
