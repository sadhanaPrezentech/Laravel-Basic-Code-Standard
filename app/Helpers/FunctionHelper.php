<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Throwable;
use PDF;

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

    /**
     * Get Currency Code
     * @param $data
     * @return array
     */
    public static function getCurrencyCode()
    {
        return config('constants.currency_symbol', 'BD');
    }

    /**
     * Format Price Value
     * @param $data
     * @return array
     */
    public static function formatPrice($price, $prefix = true, $isString = true, $separator = ' ')
    {
        try {
            if ($isString) {
                $price = $price != '' ? number_format($price, 2, '.', ',') : number_format(0, 2, '.', ',');
            } else {
                $price = $price != '' ? intval($price * 1e2) / 1e2 : intval(0 * 1e2) / 1e2;
            }
        } catch (Throwable $e) {
            return $price;
        }
        return $prefix == true ? self::getCurrencyCode() . $separator . $price : $price;
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
        $dateTime->setTimeZone(new DateTimeZone('UTC'));

        if (!$dateTime) {
            return $date;
        } else {
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
        // http://stackoverflow.com/a/3172665
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
        $module = Str::singular($module) == 'invoice' ? 'quote' : $module;
        $ucmodule = Str::ucfirst(Str::singular($module));
        $repo = "\App\Repositories\\" . "{$ucmodule}Repository";
        if ($module == 'documents') {
            $awsRepo = new \App\Repositories\AwsS3Repository(new FilesystemManager(app()));
            $repository = (new $repo(new \Illuminate\Container\Container, $awsRepo));
        } elseif ($module == 'awss3') {
            $repository = new \App\Repositories\AwsS3Repository(new FilesystemManager(app()));
        } else {
            $repository = (new $repo(new \Illuminate\Container\Container));
        }
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

    public static function generateRandomString($stringLength, $onlyDigits = false)
    {
        if ($onlyDigits) {
            return self::randomNumber($stringLength);
        } else {
            return substr(
                bin2hex(random_bytes($stringLength)),
                0,
                $stringLength
            );
        }
    }

    public static function randomNumber(int $length = 1)
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    public static function patternNumber()
    {
        return '#BM' . rand(1, 99999999);
    }

    public static function replaceValuesInPattern($counter, $pattern, $field)
    {
        $year = date('y');
        $search = ['{$year}', '{$counter}', config("constants.number_patterns.$field.0", '{$clientIdNumber}')];
        $replace = [$year, $counter];
        preg_match('/{\$date:(.*?)}/', $pattern, $matches);
        if (count($matches) > 1) {
            $format = $matches[1];
            $search[] = $matches[0];
            $date = Carbon::now(session('timezone', config('constants.display_date_timezone')))->format($format);
            $replace[] = str_replace($format, $date, $matches[1]);
        }
        $pattern = str_replace($search, $replace, $pattern);
        return $pattern;
    }

    public static function padNumber($number = null)
    {
        return $number !== null && $number !== ' ' ? str_pad($number, config('constants.pad_number', 4), '0', STR_PAD_LEFT) : $number;
    }

    public static function preparePdf($input, $view, $encode = true, $download = false, $name = '')
    {
        $options = [
            'orientation' => 'portrait',
            'encoding' => 'UTF-8',
            'margin-top' => '25',
            'margin-right' => '15',
        ];

        $pdf = PDF::loadView($view, ['input' => $input]);
        $pdf->setOptions($options);
        // dd($pdf);
        if ($download) {
            $name = empty($name) ? rand(999999, 1000000) . '.pdf' : $name;
            return $pdf->download($name);
        } else {
            return $encode == true ? base64_encode($pdf->inline()) : $pdf->inline();
        }
    }

    public static function totalCredit($credit)
    {
        $total = ($credit['profile'] ?? 0) +
            ($credit['sms'] ?? 0);
        return $total;
    }

    public static function addDuration($duration = 0, $from_date = null, $formatResult = false, $toSqlDate = false, $includeTime = false, $timezone = 'UTC')
    {
        $format = config('constants.format.date');
        $date = !empty($from_date) ? $from_date : self::today($formatResult, $toSqlDate, $includeTime, $timezone);
        $date = self::dateToString($date, false);
        if ($duration < 0) {
            $date = $date->subDays(abs($duration));
        } else {
            $date = $date->addDays($duration);
        }
        // dd($date->format('Y-m-d H:i:s'), $formatResult, $toSqlDate, $includeTime);
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

    public static function consumedCredits($total, $deduct, $html = false)
    {
        $profile = 0;
        $sms = 0;

        if ($total['profile'] > $deduct['profile']) {
            $profile = $total['profile'] - $deduct['profile'];
        }

        if ($total['sms'] > $deduct['sms']) {
            $sms = $total['sms'] - $deduct['sms'];
        }

        if ($html) {
            $data = trans('label.profile_credits') . ':' . $profile . '<br/>' . trans('label.sms_credits') . ':' . $sms;
        } else {
            $data = $profile + $sms;
        }
        return $data;
    }

    public static function averageScore($achieved_score, $total_score, $criteria_max_level)
    {
        $averageScore = 0;
        try {
            $averageScore = round(($achieved_score / $total_score) * ($criteria_max_level));
        } catch (Throwable $e) {
        }
        return $averageScore;
    }
}
