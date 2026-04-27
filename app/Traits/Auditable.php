<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAction('created');
        });

        static::updated(function ($model) {
            $model->logAction('updated');
        });

        static::deleted(function ($model) {
            $model->logAction('deleted');
        });
    }

    protected function logAction($action)
    {
        $description = $this->getAuditDescription($action);

        $oldValues = null;
        $newValues = null;

        if ($action === 'updated') {
            $changed  = $this->getChanges();
            $original = array_intersect_key($this->getOriginal(), $changed);
            $oldValues = $this->cleanAuditPayload($original);
            $newValues = $this->cleanAuditPayload($changed);
        } elseif ($action === 'created') {
            $newValues = $this->cleanAuditPayload($this->getAttributes());
        } elseif ($action === 'deleted') {
            $oldValues = $this->cleanAuditPayload($this->getAttributes());
        }

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model_type'  => get_class($this),
            'model_id'    => $this->id,
            'description' => $description,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    /**
     * Strip sensitive + noisy internal fields, then decode any JSON column blobs
     * so they render as nested objects instead of escaped strings in the audit modal.
     */
    protected function cleanAuditPayload(array $data): array
    {
        // Fields always stripped globally
        $globalHidden = [
            'password',
            'remember_token',
            // Noise — timestamps are already on the audit log row itself
            'updated_at',
            'created_at',
            // AI / notification internals
            'last_ai_message',
            'last_notification_at',
        ];

        // Per-model extra exclusions — define $auditHidden on a model to add more
        $modelHidden = property_exists($this, 'auditHidden') ? $this->auditHidden : [];

        $hidden  = array_merge($globalHidden, $modelHidden);
        $cleaned = array_diff_key($data, array_flip($hidden));

        // Decode any JSON-encoded string columns (e.g. the `services` array)
        // so the modal shows a proper nested object, not an escaped string
        foreach ($cleaned as $key => $value) {
            if (is_string($value)) {
                $trimmed = ltrim($value);
                if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $cleaned[$key] = $decoded;
                    }
                }
            }
        }

        return $cleaned;
    }

    protected function getAuditDescription($action)
    {
        $modelName = class_basename($this);
        return "{$modelName} was {$action}";
    }
}
