<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedBy;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ToSqlDate;
use App\Traits\ModelState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, CreatedByUpdatedBy, SoftDeletes, ToSqlDate, ModelState, HasRoles;

    protected $guard_name = 'web';
    public $except_datetime = ['email_verified_at'];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_by',
        'updated_by',
        'email_verified_at',
        'is_deleted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'name' => 'string',
    ];

    public static $rules = [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ];


    public function isVerified()
    {
        return $this->isEmailVerified();
    }

    public function isEmailVerified()
    {
        return !empty($this->email_verified_at);
    }

    public function scopeWithRole($query)
    {
        return $query->leftJoin('model_has_roles', function ($query) {
            $query->on('users.id', '=', 'model_has_roles.model_id');
        });
    }
}
