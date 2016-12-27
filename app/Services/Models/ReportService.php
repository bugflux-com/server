<?php


namespace App\Services\Models;


use App\Models\Comment;
use App\Models\NotificationTypeUser;
use App\Models\Report;
use App\Providers\AppServiceProvider;
use Illuminate\Database\Eloquent\Collection;

class ReportService
{
    /**
     * @param array $reports
     * @throws \Exception
     */
    public function deleteDependencies(array $reports)
    {
        $all_ids = $this->getDependencies($reports);

        $comments_ids = $all_ids['comments_ids'];
        $notificationTypeUsers_ids = $all_ids['notificationTypeUsers_ids'];

        Comment::whereIn('id', $comments_ids)->delete();
        NotificationTypeUser::whereIn('id', $notificationTypeUsers_ids)->delete();
    }

    public function getReportsIds(array $reports)
    {
        return array_map(function($o) { return $o->id; }, $reports);
    }

    public function getReportsComments(array $reportsIds)
    {
        return Comment::where('commentable_type', array_search(Report::class, AppServiceProvider::$morphMap))
            ->whereIn('commentable_id', $reportsIds)->get()->all();
    }

    public function getReportsNotificationTypeUsers(array $reportsIds)
    {
        return NotificationTypeUser::where('wantable_type', array_search(Report::class, AppServiceProvider::$morphMap))
            ->whereIn('wantable_id', $reportsIds)->get()->all();
    }

    /**
     * @param array $reports
     * @return mixed
     */
    public function getDependencies(array $reports)
    {
        $ids = $this->getReportsIds($reports);
        $comments = $this->getReportsComments($ids);
        $notificationTypeUsers = $this->getReportsNotificationTypeUsers($ids);

        $comments_ids = array_map(function($o) { return $o->id; }, $comments);
        $notificationTypeUsers_ids = array_map(function($o) { return $o->id; }, $notificationTypeUsers);

        return compact('comments_ids', 'notificationTypeUsers_ids');
    }
}