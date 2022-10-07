<?php

namespace App\Http\View\Composers;

use App\Helpers\FunctionHelper;
use Illuminate\View\View;

class SearchComposer
{
    private $entity;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $type = isset($view->type) ? $view->type : null;
        $entity = FunctionHelper::getEntity($type);
        return $view->with([
            'entity' => $entity
        ]);
    }
}
