<?php


namespace App\Http\ViewComposers;


use Illuminate\View\View;

class MasterViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if(empty(view()->shared('master_view'))) {
            // Set first called view path to master_view variable
            // (used to mark links as current/active).
            view()->share('master_view', $view->getName());
        }
    }
}