<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;

abstract class BaseDataTable extends DataTable
{
    abstract public function getColumns();

    abstract public function getFormParams();

    public $ajaxFormUrl;
    public $formDataScript;
    public $orderColumnNo = 2;
    public $drawCallback = null;
    public $orderBy = 'desc';

    protected function setFormParams()
    {
        $selector = "form#search-{$this->entity['targetModel']}";
        $this->formDataScript = $this->getFormDataScript($selector);

        $this->ajaxFormUrl = route("{$this->entity['url']}.index");
    }

    public function setSearchCriteria($query)
    {
        if (request()->has('search_form')) {
            $query->ofAll();
        }
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $this->getFormParams();

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax($this->ajaxFormUrl, $this->formDataScript)
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'searching' => false,
                'dom' => "<'d-flex justify-content-between'tr>" .
                    "<'d-flex justify-content-between'lip>",
                'stateSave' => false,
                'order' => [[$this->orderColumnNo, $this->orderBy]],
                'buttons' => [
                    ['extend' => 'excel', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
                'responsive' => true,
                'language' => [
                    'infoFiltered' => '(Total: _MAX_)',
                ],
                'drawCallback' => $this->drawCallback
            ]);
    }

    /**
     * Set ajax url with data added from form.
     *
     * @param string $url
     * @param string $formSelector
     * @return $this
     */
    public function getFormDataScript($formSelector)
    {
        $script = <<<CDATA
        var formData = $("{$formSelector}").find("input, select").serializeArray();
        data.search_form = $("{$formSelector}").serialize();
        $.each(formData, function(i, obj){
            data[obj.name] = obj.value;
        });
        CDATA;

        return $script;
    }

    /**
     * Set ajax url with data added from form.
     *
     * @param string $url
     * @return $this
     */
    public function setAjaxFormUrl($url)
    {
        $this->ajaxFormUrl = $url;
    }
}
