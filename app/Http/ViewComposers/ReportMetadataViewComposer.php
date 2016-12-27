<?php


namespace App\Http\ViewComposers;


use App\Models\ErrorDuplicate;
use Illuminate\View\View;

class ReportMetadataViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $report = $view->getData()['report'];

        $comments_count = $report->comments()->count();
        view()->share('comments_count', $comments_count);
    }
}