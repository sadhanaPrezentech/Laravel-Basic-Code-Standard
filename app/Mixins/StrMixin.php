<?php

namespace App\Mixins;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Illuminate\Support\Str;

class StrMixin
{

    public function setModulePermission()
    {
        return function ($permission, $module) {
            if (empty($permission) || empty($module)) {
                return null;
            }
            return $permission . ' ' . $module;
        };
    }

    public function getModulePermission()
    {
        return function ($module_permission) {
            if (empty($module_permission)) {
                return null;
            }
            $data = explode(' ', $module_permission);
            return ['module' => $data[1] ?: null, 'permission' => $data[0] ?: null];
        };
    }

    public function getBaseNameByNamespace()
    {
        return function ($object) {
            if (empty($object)) {
                return null;
            }
            $reflection = new ReflectionClass($object);
            return $reflection->getShortName();
        };
    }

    public function getModuleByModel()
    {
        return function (Model $object) {
            if (empty($object)) {
                return null;
            }
            $reflection = new ReflectionClass(get_class($object));
            $model = $reflection->getShortName() == 'Quote' && $object->quote_type == 2 ? 'Invoice' : $reflection->getShortName();

            return config("model-module-mapping.{$model}", null);
        };
    }

    public function getModuleTitle()
    {
        return function (string $module = null, $platform = 'web', $singular = true) {
            if (empty($module)) {
                return null;
            }
            $moduleTitle = config("modules.list.{$platform}.{$module}", null);
            $title = is_array($moduleTitle) ? $moduleTitle['label'] : $moduleTitle;
            return $singular ? Str::singular($title) : Str::plural($title);
        };
    }

    public function getLabel()
    {
        return function (string $label = null) {
            if (empty($label)) {
                return null;
            }
            return trans("label.{$label}");
        };
    }
}
