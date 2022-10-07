<?php

namespace App\DataTables;

use App\Helpers\FunctionHelper;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;

class UserDataTable extends BaseDataTable
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->entity = FunctionHelper::getEntity();
    }

    public function getFormParams()
    {
        $this->orderColumnNo = 3;
        $this->setFormParams();
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        $dataTable->editColumn('created_at', function ($model) {
            return $model->created_at ? FunctionHelper::fromSqlDateTime($model->created_at->toDateTimeString()) : '';
        });
        $dataTable->editColumn('name', function ($model) {
            return $model->name ? '<a href=' . route('users.show', $model->id) . '>' . $model->name . '</a>' : '';
        });

        $dataTable->editColumn('roles', function ($model) {
            return $model->getRoleNames()->count() > 0 ? implode(',', $model->roles->pluck('title')->toArray()) : '';
        });

        $dataTable->rawColumns(['action', 'name', 'roles']);

        $action_view = view()->exists('users.datatables_actions') ? 'users.datatables_actions' : 'components.datatables_actions';
        return $dataTable->addColumn('action', $action_view);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = $this->repository->allQuery([], null, null, [], [], 1);
        $query->withRole();
        $query->selectRaw('model_has_roles.*,users.*');

        // if (request()->has('search_form')) {
        //     parse_str(request()->get('search_form', ''), $search);

        //     if (isset($search['role']) && !empty($search['role'])) {
        //         if ($search['role'][0] == '0') {
        //         } else {
        //             $query->whereIn('role_id', $search['role']);
        //         }
        //     }
        // }
        $query->where('users.id', '<>', Auth::user()->id);
        $query->groupBy('users.id');
        $this->setSearchCriteria($query);
        return $query;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return [
            'name' => ['title' => 'Name'],
            'email' => ['title' => 'Email'],
            'roles' => ['searchable' => false, 'title' => 'Role', 'orderable' => false],
            'created_at' => ['title' => 'Created Date', 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users_' . time();
    }
}
