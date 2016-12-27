<?php


namespace App\Http\ViewComposers;

use App\Models\FlatReport;
use Illuminate\View\View;

class ProjectMetadataViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $project = $view->getData()['project'];
        $ur_count = FlatReport::where('project', $project->code)
            ->where('imported_at', null)->count();

        view()->share('unclassified_reports_count', $ur_count);
    }
}