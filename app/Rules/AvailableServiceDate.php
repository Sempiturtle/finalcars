<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\ServiceLog;
use Carbon\Carbon;

class AvailableServiceDate implements ValidationRule
{
    protected $requiredSlots;

    public function __construct($requiredSlots = 1)
    {
        $this->requiredSlots = $requiredSlots;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // Try multiple common formats to avoid ambiguity
        $date = null;
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y'];
        
        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                // If it's d/m/Y or m/d/Y, ensure we don't accidentally treat 10/05 as Oct 5 if we wanted May 10
                // For this app, let's prioritize Y-m-d and then d/m/Y (common in PH)
                break;
            } catch (\Exception $e) {
                continue;
            }
        }

        if (!$date) {
            try {
                $date = Carbon::parse($value);
            } catch (\Exception $e) {
                return; // Let standard date validation handle invalid formats
            }
        }
        $maxSlots = \App\Models\Setting::get('max_slots_per_day', 10);
        $restDays = array_map('intval', \App\Models\Setting::get('rest_days', [0]));

        // Check for rest days
        if (in_array((int)$date->dayOfWeek, $restDays)) {
            $fail("The selected date is a rest day. Please choose another day.");
            return;
        }

        // Check for past dates
        if ($date->isPast() && !$date->isToday()) {
            $fail("You cannot schedule a service in the past.");
            return;
        }

        // Check for capacity (weighted)
        $existingLogs = ServiceLog::whereDate('service_date', $date->toDateString())->get();
        
        $usedSlots = 0;
        foreach ($existingLogs as $log) {
            // Find the service type to get its weight
            $st = \App\Models\ServiceType::whereRaw('LOWER(name) = ?', [strtolower($log->service_type)])->first();
            $usedSlots += ($st->required_slots ?? 1);
        }

        if (($usedSlots + $this->requiredSlots) > $maxSlots) {
            $remaining = max(0, $maxSlots - $usedSlots);
            $fail("This day is almost full. Only {$remaining} slot(s) remaining, but this service requires {$this->requiredSlots} slot(s).");
        }
    }
}
