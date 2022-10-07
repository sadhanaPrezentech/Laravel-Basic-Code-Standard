<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use DateTime;
use DateTimeZone;

class FunctionHelper
{
    public static function getEntity($entity = null, $parameter = null)
    {
        if (empty($entity)) {
            if ((request()->is('admin/*')) && is_string(request()->segment(2))) {
                $resources = request()->segment(2);
            } else {
                $resources = request()->segment(1);
            }
        } else {
            $resources = $entity;
        }

        $model = !empty($resources) ? Str::singular($resources) : '';
        $view = !empty($resources) ? str_replace('-', '_', Str::snake($resources)) : '';
        $plural = str_replace('_', ' ', Str::title($view));
        $singular = Str::singular($plural);
        $urlPath = array_values(array_filter(explode('/', request()->getPathInfo()), 'strlen'));
        $prefix = array_shift($urlPath);
        $data = [
            'prefix' => $prefix ? $prefix : '',
            'url' => $resources,
            'targetModel' => $model,
            'view' => $view,
            'plural' => $plural,
            'singular' => $singular,
        ];
        return  $data[$parameter] ?? $data;
    }

    /**
     * format decimal number
     * @param $data
     * @return array
     */
    public static function formatDecimalNumber($number, $suffix = '', $addspace = true)
    {
        if (empty($number)) {
            return null;
        }
        $number = intval($number * 1e3) / 1e3;
        if ($suffix != '') {
            $number = $addspace ? $number . ' ' . $suffix : $number . $suffix;
        }
        return (string) $number;
    }

    public static function timestampToDateTimeString($timestamp)
    {
        $timezone = config('constants.display_date_timezone');
        $format = config('constants.format.datetime');

        return self::timestampToString($timestamp, $timezone, $format);
    }

    public static function timestampToDateString($timestamp)
    {
        $timezone = config('constants.display_date_timezone');
        $format = config('constants.format.date');

        return self::timestampToString($timestamp, $timezone, $format);
    }

    public static function dateToString($date, $changeFormat = true)
    {
        if (!$date) {
            return false;
        }
        if ($date instanceof DateTime) {
            $dateTime = $date;
        } else {
            $dateTime = new DateTime($date);
        }
        $timestamp = $dateTime->getTimestamp();
        $format = config('constants.format.date');
        return $changeFormat == true ? self::timestampToString($timestamp, false, $format) : self::timestampToString($timestamp, false);
    }

    public static function timestampToString($timestamp, $timezone, $format = null)
    {
        if (!$timestamp) {
            return '';
        }
        $date = Carbon::createFromTimeStamp($timestamp);
        if ($timezone) {
            $date->tz = $timezone;
        }
        if ($date->year < 1900) {
            return '';
        }
        return !empty($format) ? $date->format($format) : $date;
    }

    public static function toSqlDate($date, $formatResult = true)
    {
        if (!$date) {
            return;
        }

        $format = config('constants.format.date');
        $dateTime = DateTime::createFromFormat($format, $date);

        if (!$dateTime) {
            return $date;
        } else {
            return $formatResult ? $dateTime->format('Y-m-d') : $dateTime;
        }
    }

    public static function toSqlDateTime($date, $formatResult = true)
    {
        if (!$date) {
            return;
        }

        $format = config('constants.format.datetime');
        $dateTime = DateTime::createFromFormat($format, $date);

        if (!$dateTime) {
            return $date;
        } else {
            $dateTime->setTimeZone(new DateTimeZone('UTC'));
            return $formatResult ? $dateTime->format('Y-m-d H:i:s') : $dateTime;
        }
    }

    public static function fromSqlDate($date, $formatResult = true)
    {
        if (!$date || $date == '0000-00-00') {
            return '';
        }

        $format = config('constants.format.date');
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);

        if (!$dateTime) {
            return $date;
        } else {
            return $formatResult ? $dateTime->format($format) : $dateTime;
        }
    }

    public static function fromSqlDateTime($date, $formatResult = true, $customFormat = '')
    {
        if (!$date || $date == '0000-00-00 00:00:00') {
            return '';
        }

        $timezone = config('constants.display_date_timezone');
        $format = $customFormat != '' ? $customFormat : config('constants.format.datetime');

        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $dateTime->setTimeZone(new DateTimeZone($timezone));

        return $formatResult ? $dateTime->format($format) : $dateTime;
    }

    public static function fromDateTimeToTime($date, $formatResult = true, $customFormat = '')
    {
        if (!$date || $date == '0000-00-00 00:00:00') {
            return '';
        }

        $timezone = config('constants.display_date_timezone');
        $format = $customFormat != '' ? $customFormat : config('constants.format.time');

        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $dateTime->setTimeZone(new DateTimeZone($timezone));

        return $formatResult ? $dateTime->format($format) : $dateTime;
    }

    public static function formatTime($t)
    {
        $f = ':';
        return sprintf('%02d%s%02d%s%02d', floor($t / 3600), $f, ($t / 60) % 60, $f, $t % 60);
    }

    public static function today($formatResult = true, $toSqlDate = false, $includeTime = false, $timezone = null)
    {
        if ($timezone === null) {
            $timezone = config('constants.display_date_timezone');
        }
        $format = $includeTime ? config('constants.format.datetime') : config('constants.format.date');

        $date = date_create(null, new DateTimeZone($timezone));

        if ($formatResult) {
            return $date->format($format);
        } elseif ($toSqlDate) {
            if ($includeTime) {
                return $date->format('Y-m-d H:i:s');
            }
            return $date->format('Y-m-d');
        } else {
            return $date;
        }
    }

    public static function dateRangeOptions()
    {
        $yearStart = Carbon::parse(date('Y') . '-01-01');
        $month = $yearStart->month - 1;
        $year = $yearStart->year;
        $lastYear = $year - 1;

        $str = '{
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
            "This Year": [moment().date(1).month(' . $month . ').year(' . $year . '), moment()],
            "Last Year": [moment().date(1).month(' . $month . ').year(' . $lastYear . '), moment().date(1).month(' . $month . ').year(' . $year . ').subtract(1, "day")],
        }';

        return $str;
    }

    public static function getRepositoryByModule($module = null)
    {
        if (empty($module)) {
            $entity = self::getEntity();
            $module = $entity['toLower'];
        }
        $ucmodule = Str::ucfirst(Str::singular($module));
        $repo = "\App\Repositories\\" . "{$ucmodule}Repository";

        $repository = (new $repo(new \Illuminate\Container\Container));
        throw_if(is_null($repository), BadRequestHttpException::class, "Repostory for {$module} doesn't exist.");
        return $repository;
    }

    public static function permissionAllowed($data, $permission)
    {
        if (is_array($data) && isset($data['allow_permission']) && !in_array($permission, $data['allow_permission'])) {
            return false;
        }
        return true;
    }
}
