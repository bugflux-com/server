<?php


namespace App\Http\ViewComposers;


use App\Models\ErrorDuplicate;
use Illuminate\View\View;

class ErrorMetadataViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $error = $view->getData()['error'];

        $comments_count = $error->comments()->count();
        view()->share('comments_count', $comments_count);

        $duplicates_count = ErrorDuplicate::duplicatesOf($error->id)->count();
        view()->share('duplicates_count', $duplicates_count);
    }
}