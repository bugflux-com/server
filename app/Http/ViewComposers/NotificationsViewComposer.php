<?php


namespace App\Http\ViewComposers;


use Auth;
use Illuminate\View\View;

class NotificationsViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $user_groupings = (object)[
            'latest_items' => Auth::user()->notificationGroups()->with('type')->latest()->take(5)->get(),
            'unread_count' => Auth::user()->notificationGroups()->where('viewed_at', null)->count(),
        ];

        $view->with(compact('user_groupings'));
    }
}