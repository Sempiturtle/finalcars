<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $token;
    protected $senderId;

    public function __construct()
    {
        $this->token = config('services.philsms.token');
        $this->senderId = config('services.philsms.sender_id', 'PhilSMS');
    }

    /**
     * Send an OTP to a recipient.
     *
     * @param string $phone
     * @param string $otp
     * @return array|bool
     */
    public function sendOtp($phone, $otp)
    {
        if (!$this->token) {
            Log::error('PhilSMS Error: API Token missing in configuration.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://dashboard.philsms.com/api/v3/sms/send', [
                'recipient' => $this->formatPhoneNumber($phone),
                'sender_id' => $this->senderId,
                'type' => 'plain',
                'message' => "Your FinalCars verification code is: $otp. Valid for 5 minutes.",
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PhilSMS API Response Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('PhilSMS Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure the phone number starts with 63.
     */
    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($phone, '0')) {
            $phone = '63' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '63')) {
            $phone = '63' . $phone;
        }

        return $phone;
    }
}
