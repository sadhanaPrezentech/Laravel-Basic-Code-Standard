<?php

namespace App\Traits;

use App\Helpers\FunctionHelper;

trait ToSqlDate
{
    public function setAttribute($field, $value)
    {
        // to convert sql date
        $castFields = array_keys(self::getCasts(), 'date');
        if (in_array($field, $castFields) && !empty($value)) {
            $value = FunctionHelper::toSqlDate($value, true);
        }
        // to convert sql datetime
        $castFields = array_keys(self::getCasts(), 'datetime');
        $exceptFields = $this->except_datetime ?? [];
        if (in_array($field, $castFields) && !in_array($field, $exceptFields) && !empty($value)) {
            $value = FunctionHelper::toSqlDateTime($value, true);
        }
        return parent::setAttribute($field, $value);
    }
}
