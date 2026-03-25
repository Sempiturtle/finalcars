<?php

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIReminderService
{
    /**
     * Generate a professional reminder using Gemini AI.
     */
    public function generateReminder(Vehicle $vehicle)
    {
        $apiKey = config('services.gemini.key');
        
        if (!$apiKey) {
            return "Professional Reminder: Your {$vehicle->make} {$vehicle->model} (Plate: {$vehicle->plate_number}) is due for maintenance on {$vehicle->next_service_date->format('M d, Y')}. Please schedule your service soon.";
        }

        try {
            $prompt = "Generate a very professional, friendly, and concise maintenance reminder for a car owner named {$vehicle->owner_name}. 
                       Vehicle: {$vehicle->make} {$vehicle->model} ({$vehicle->plate_number}). 
                       Due Date: {$vehicle->next_service_date->format('M d, Y')}. 
                       Status: " . ($vehicle->isCriticalOverdue() ? 'Critical Overdue' : 'Due Soon') . ".
                       Keep it under 150 characters.";

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                return trim($content);
            }
        } catch (\Exception $e) {
            Log::error("Gemini API Error: " . $e->getMessage());
        }

        return "Reminder: Your {$vehicle->make} is due for service on {$vehicle->next_service_date->format('M d, Y')}.";
    }
}
