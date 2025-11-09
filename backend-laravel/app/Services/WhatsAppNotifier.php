<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppNotifier
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.whatsapp_from');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    public function send(string $to, string $message): bool
    {
        if (!$this->client) {
            Log::warning('Twilio not configured. Skipping WhatsApp message.');
            return false;
        }

        try {
            $this->client->messages->create("whatsapp:{$to}", [
                'from' => "whatsapp:{$this->from}",
                'body' => $message,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
            return false;
        }
    }
}
