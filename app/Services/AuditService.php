<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log a create event for a model
     * 
     * @param Model $model
     * @return AuditLog
     */
    public function logCreated(Model $model): AuditLog
    {
        return $this->log($model, 'created', null, $model->getAttributes());
    }

    /**
     * Log an update event for a model
     * 
     * @param Model $model
     * @param array $oldValues
     * @return AuditLog
     */
    public function logUpdated(Model $model, array $oldValues): AuditLog
    {
        return $this->log($model, 'updated', $oldValues, $model->getAttributes());
    }

    /**
     * Log a delete event for a model
     * 
     * @param Model $model
     * @return AuditLog
     */
    public function logDeleted(Model $model): AuditLog
    {
        return $this->log($model, 'deleted', $model->getAttributes(), null);
    }

    /**
     * Log a restore event for a model (from soft delete)
     * 
     * @param Model $model
     * @return AuditLog
     */
    public function logRestored(Model $model): AuditLog
    {
        return $this->log($model, 'restored', null, $model->getAttributes());
    }

    /**
     * Create a general audit log entry
     * 
     * @param Model $model
     * @param string $event
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return AuditLog
     */
    protected function log(Model $model, string $event, ?array $oldValues = null, ?array $newValues = null): AuditLog
    {
        // Remove password fields from logged values for security
        if ($oldValues) {
            $this->removePasswordFields($oldValues);
        }
        
        if ($newValues) {
            $this->removePasswordFields($newValues);
        }
        
        // Create the log entry
        return AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
    
    /**
     * Remove password fields from an array for security
     * 
     * @param array &$values
     * @return void
     */
    protected function removePasswordFields(array &$values): void
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
        ];
        
        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $values)) {
                unset($values[$field]);
            }
        }
    }
}
