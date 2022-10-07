<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedBy;
use App\Traits\ModelState;
use App\Traits\ToSqlDate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role as ModelsRole;
use Illuminate\Support\Str;
use Throwable;

class Role extends ModelsRole
{
    use SoftDeletes;
    use CreatedByUpdatedBy;
    use ModelState;
    use ToSqlDate;

    public $table = 'roles';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'title',
        'guard_name',
        'created_by',
        'updated_by',
        'is_deleted'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'guard_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:roles,name,null,id,deleted_at,NULL',
        'title' => 'required',
        'guard_name' => 'required'
    ];

    public static $messages = [
        'name.required' => 'Role is required field',
        'name.unique' => 'Please enter different Title.<br/>The role :s is already exist, for the title :s and platform :s.',
        'title.required' => 'Title is required field',
        'guard_name.required' => 'Platform is required field'
    ];

    public static function getMessages($validationField, $paramers = [])
    {
        $message = isset(self::$messages[$validationField]) ? self::$messages[$validationField] : '';
        return Str::replaceArray(':s', $paramers, $message);
    }

    /**
     * cacheRoles function
     *
     * @return void
     */
    public static function cacheRoles()
    {
        $roles = self::get();
        Cache::put('roles', $roles);
        return $roles;
    }

    /**
     * getCachedRoles function
     *
     * @param string/array $name eg. manager / ['super-admin', 'manager']
     * @param boolean $first set false if you want collection in return
     * @return collection
     */
    public static function getCachedRoles($name = null, $first = true)
    {
        $roles = collect();
        if (Cache::has('roles')) {
            $roles = Cache::get('roles');
        } else {
            $roles = self::cacheRoles();
        }
        try {
            if (!empty($name)) {
                if (is_array($name)) {
                    $roles = $roles->whereIn('name', $name)->sortBy(function ($role) use ($name) {
                        return array_search($role->name, $name);
                    });
                } else {
                    $roles = $roles->where('name', $name);
                }
            }
            return $first ? $roles->first() : $roles->all();
        } catch (Throwable $e) {
            // dd($e);
            return null;
        }
    }
}
