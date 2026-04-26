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
            $newValues = $this->getChanges();
            $oldValues = array_intersect_key($this->getOriginal(), $newValues);
        } elseif ($action === 'created') {
            $newValues = $this->getAttributes();
        } elseif ($action === 'deleted') {
            $oldValues = $this->getAttributes();
        }

        // Hide sensitive fields
        $hidden = ['password', 'remember_token'];
        if ($oldValues) {
            $oldValues = array_diff_key($oldValues, array_flip($hidden));
        }
        if ($newValues) {
            $newValues = array_diff_key($newValues, array_flip($hidden));
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function getAuditDescription($action)
    {
        $modelName = class_basename($this);
        return "{$modelName} was {$action}";
    }
}
