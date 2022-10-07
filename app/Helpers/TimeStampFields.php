<?php

namespace App\Helpers;

class TimeStampFields
{
    public function AddTimeStampFieldsToDataTable($dataTable)
    {
        $dataTable->editColumn('created_at', function ($model) {
            return $model->created_at ? FunctionHelper::fromSqlDateTime($model->created_at->toDateTimeString()) : '';
        });

        $dataTable->editColumn('updated_at', function ($model) {
            return $model->updated_at ? FunctionHelper::fromSqlDateTime($model->updated_at->toDateTimeString()) : '';
        });

        return $dataTable;
    }
}
