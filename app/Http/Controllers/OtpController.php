<?php
namespace App\Http\Controllers;

use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send OTP to the provided phone number.
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10',
        ]);

        $phone = $request->phone;
        $otp = rand(100000, 999999);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        $result = $this->smsService->sendOtp($phone, $otp);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to Globe/TM number.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP. Please try again later.'
        ], 500);
    }

    /**
     * Verify the provided OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|numeric',
        ]);

        $cachedOtp = Cache::get('otp_' . $request->phone);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            // Store a flag that this phone is verified for the next 15 mins
            Cache::put('verified_phone_' . $request->phone, true, now()->addMinutes(15));
            
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP.'
        ], 422);
    }
}
