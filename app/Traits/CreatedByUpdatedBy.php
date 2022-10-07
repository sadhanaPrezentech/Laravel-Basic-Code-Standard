<?php

namespace App\Traits;

use App\Helpers\FunctionHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait CreatedByUpdatedBy
{
    public static function bootCreatedByUpdatedBy()
    {
        static::creating(function ($model) {
            $user = Auth::user();
            if (!empty($user)) {
                $createdBy = empty($model->created_by) ? $user->id : $model->created_by;
                $attributes = $model->getFillable();
                if (in_array('created_by', $attributes)) {
                    $model->created_by = $createdBy;
                }
                if (in_array('updated_by', $attributes)) {
                    $model->updated_by = $createdBy;
                }
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if (!empty($user)) {
                $attributes = $model->getFillable();
                if (in_array('updated_by', $attributes)) {
                    $model->updated_by = empty($model->updated_by) ? $user->id : $model->updated_by;
                }
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdByUserWithActivePackage()
    {
        return $this->belongsTo(User::class, 'created_by')->whereHas('activeUserPackage');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getPerformedByOnInfo($action = 'created', $return_string = false)
    {
        $result = ['performed_by' => '', 'performed_at' => ''];

        switch ($action) {
            case 'created':
            case 'request_created':
                $result['performed_by'] = !empty($this->createdByUser) ? $this->createdByUser->name : null;
                $result['performed_at'] = !empty($this->created_at) ? FunctionHelper::fromSqlDateTime($this->created_at->toDateTimeString()) : null;
                break;
            case 'updated':
            case 'restored':
            case 'approved':
            case 'rejected':
            case 'cancelled':
            case 'marked_paid':
            case 'marked_sent':
                $result['performed_by'] = !empty($this->updatedByUser) ? $this->updatedByUser->name : null;
                $result['performed_at'] = !empty($this->updated_at) ? FunctionHelper::fromSqlDateTime($this->updated_at->toDateTimeString()) : null;
                break;
            case 'archived':
            case 'deleted':
                $result['performed_by'] = !empty($this->updatedByUser) ? $this->updatedByUser->name : null;
                $result['performed_at'] = !empty($this->deleted_at) ? FunctionHelper::fromSqlDateTime($this->deleted_at->toDateTimeString()) : null;
                break;
        }

        return $return_string ? "by {$result['performed_by']} on {$result['performed_at']}" : $result;
    }

    public function scopeCurrentUser($query)
    {
        return $query->where(self::getTable() . '.created_by', auth()->user()->id);
    }

    public function scopeJoinCreatedBy($query)
    {
        return $query->join('users', function ($query) {
            $query->on('users.id', '=', self::getTable() . '.created_by');
        });
    }

    public function scopeActiveCreatedBy($query, $table = '', $includeActivePackage = true)
    {
        $query = $query->join('users', function ($query) use ($table) {
            $query->on('users.id', '=', $table . '.created_by')->whereNull('users.deleted_at');
        });

        if ($includeActivePackage) {
            return $query->aciveUserPackage();
        }
        return $query;
    }

    public function scopeAciveUserPackage($query, $table = 'users')
    {
        return $query->join('user_packages', function ($query) use ($table) {
            $query->on('user_packages.user_id', '=', $table . '.id')->whereIsActive(1)->whereNull('users.deleted_at');
        });
    }
}
