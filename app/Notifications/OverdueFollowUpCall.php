<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OverdueFollowUpCall extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Default to database log, but we'll trigger Twilio manually in our service
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'type' => 'automated_follow_up_call',
        ];
    }

    /**
     * Custom method to trigger Twilio call.
     */
    public function triggerCall($to)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.from');

        if (!$sid || !$token || !$from) {
            Log::info("Twilio Call Simulation to {$to}: {$this->message}");
            return 'SIMULATED_SID';
        }

        try {
            $twiml = "<Response><Pause length='1'/><Say voice='alice' language='en-US'>{$this->message}</Say></Response>";
            
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Calls.json", [
                    'To' => $to,
                    'From' => $from,
                    'Twiml' => $twiml,
                ]);

            if ($response->successful()) {
                Log::info("Twilio Call triggered successfully to {$to}. SID: " . $response->json('sid'));
                return $response->json('sid');
            } else {
                Log::error("Twilio Call Failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Twilio Exception: " . $e->getMessage());
        }
        
        return 'FAILED';
    }
}
