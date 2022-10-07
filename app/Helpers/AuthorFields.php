<?php

namespace App\Helpers;

class AuthorFields
{
    public function AddAuthorFieldsToDataTable($dataTable, $rawField = [])
    {
        $addFields = ['created_by_name', 'updated_by_name'];

        $finalFields = array_merge($rawField, $addFields);

        $dataTable->editColumn('created_by_name', function ($model) {
            return $model->created_by_name ? $model->created_by_name : '';
        });

        $dataTable->editColumn('updated_by_name', function ($model) {
            return $model->updated_by_name ? $model->updated_by_name : '';
        });

        $dataTable->filterColumn('created_by_name', function ($query, $keyword) {
            $query->whereRaw("created_user.name like ?", ["%{$keyword}%"]);
        });

        $dataTable->filterColumn('updated_by_name', function ($query, $keyword) {
            $query->whereRaw("updated_user.name like ?", ["%{$keyword}%"]);
        });

        $dataTable->rawColumns($finalFields);

        return $dataTable;
    }

    public function AddAuthorJoinsTOQuery($query, $table)
    {
        $tableFieldCreate = $table . '.created_by';
        $tableFieldUpdate = $table . '.updated_by';

        $query->selectRaw('created_user.name as created_by_name, updated_user.name as updated_by_name');

        $query->leftJoin('users AS created_user', function ($table) use ($tableFieldCreate) {
            $table->on($tableFieldCreate, '=', 'created_user.id');
        });

        $query->leftJoin('users AS updated_user', function ($table) use ($tableFieldUpdate) {
            $table->on($tableFieldUpdate, '=', 'updated_user.id');
        });

        return $query;
    }
}
