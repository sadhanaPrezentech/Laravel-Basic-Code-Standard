<?php

namespace App\DataTables;

use App\Models\Blog;
use Yajra\DataTables\EloquentDataTable;
use App\Repositories\BlogRepository;
use App\Helpers\FunctionHelper;

class BlogDataTable extends BaseDataTable
{
    protected $repository;

    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
        $this->entity = FunctionHelper::getEntity();
    }

    public function getFormParams()
    {
        $this->orderColumnNo = 2;
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
            return $model->created_at ? FunctionHelper::fromSqlDateTime($model->created_at->toDateTimeString(), true, '') : '';
        });
        $dataTable->editColumn('created_by', function ($model) {
            return $model->created_by ? '<a href=' . route('users.show', $model->createdByUser->id) . '>' . $model->createdByUser->name . '</a>' : '';
        });

        $dataTable->rawColumns(['action', 'created_by']);
        $action_view = view()->exists('blogs.datatables_actions') ? 'blogs.datatables_actions' : 'components.datatables_actions';
        return $dataTable->addColumn('action', $action_view);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Blog $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Blog $model)
    {

        $query = $this->repository->allQuery();
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
            'name' => ['title' => 'Title'],
            'tag' => ['title' => 'Tag'],
            'created_by' => ['title' => 'Created By'],
            'created_at' => ['title' => 'Created', 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'blogs_datatable_' . time();
    }
}
