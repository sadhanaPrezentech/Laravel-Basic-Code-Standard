<?php

namespace App\Mixins;

use App\Helpers\FunctionHelper;
use App\Models\Configuration;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Illuminate\Support\Str;
use Throwable;

class StrMixin
{
    /**
     * getNextNumber function
     *
     * @param string $field: name of field to get/use to store.
     * @param string $next_id: next number to get auto generated and to be formatted.
     * @param string $client_id / null: Client Code that should be included in some modules[invoice,quote,etc..]
     * @param string $pattern / null: before saving configuration to show as an example.
     *
     * @return void
     */
    public function getNextNumber()
    {
        return function ($field, $next_id, $pattern = null) {
            if (empty($field)) {
                return null;
            }

            try {
                // apply padding to $counter
                $counter = FunctionHelper::padNumber($next_id);

                $configuration = Configuration::patternOfModule($field)->first();

                $type = 'pattern';
                // $pattern will be used only while doing configure field "Example" value.
                if (!empty($configuration) && empty($pattern)) {
                    $pattern = $configuration->setting_value;
                    $type = $configuration->setting_type;
                }

                if (empty($configuration)) {
                    return $counter;
                } elseif ($type == config('constants.configuration_setting_type.1', 'prefix')) {
                    return $pattern . $counter;
                } else {
                    return FunctionHelper::replaceValuesInPattern($counter, $pattern, $field);
                }
            } catch (Throwable $e) {
                // dd($e);
                return null;
            }
        };
    }

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

    public function encryptVerificationData()
    {
        return function ($data) {
            return encrypt(json_encode($data));
        };
    }

    public function decryptVerificationData()
    {
        return function (string $data) {
            return json_decode(decrypt($data));
        };
    }

    public function formatePhoneNumber()
    {
        return function ($request) {
            $prefix = config('constants.phone_prefix');

            if (is_string($request) && !empty($request)) {
                return $prefix . Str::removePhonePrefix($request);
            } elseif (!empty($request['phone_number'])) {
                $request['phone_number'] = $prefix . Str::removePhonePrefix($request['phone_number']);
            }

            return $request;
        };
    }

    public function removePhonePrefix()
    {
        return function ($phoneNumber) {
            return str_replace(config('constants.phone_prefix'), '', $phoneNumber);
        };
    }

    public function base64ImageSrc()
    {
        return function ($mime, $file) {
            if (empty($file) || empty($mime)) {
                return '';
            }
            return 'data:' . $mime . ';base64,' . $file;
        };
    }
}
