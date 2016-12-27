<?php


namespace App\Services\Models;


use App\Models\Comment;
use App\Models\Error;
use App\Models\NotificationTypeUser;
use App\Models\Report;
use App\Providers\AppServiceProvider;

class ErrorService
{

    private $reportService;

    /**
     * ErrorService constructor.
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * @param array $errors
     * @throws \Exception
     */
    public function deleteDependencies(array $errors)
    {
        $all_ids = $this->getDependencies($errors);

        $reports_ids = $all_ids['notificationTypeUsers_ids'];
        $comments_ids = $all_ids['comments_ids'];
        $notificationTypeUsers_ids = $all_ids['notificationTypeUsers_ids'];

        Comment::whereIn('id', $comments_ids)->delete();
        NotificationTypeUser::whereIn('id', $notificationTypeUsers_ids)->delete();
        Report::whereIn('id', $reports_ids)->delete();
    }

    public function getErrorsComments(array $errorsIds)
    {
        return Comment::where('commentable_type', array_search(Error::class, AppServiceProvider::$morphMap))
            ->whereIn('commentable_id', $errorsIds)->get()->all();
    }

    public function getErrorsNotificationTypeUsers(array $errorsIds)
    {
        return NotificationTypeUser::where('wantable_type', array_search(Error::class, AppServiceProvider::$morphMap))
            ->whereIn('wantable_id', $errorsIds)->get()->all();
    }

    /**
     * @param array $errors
     * @return mixed
     */
    public function getDependencies(array $errors)
    {
        $errors_ids = array_map(function($o) { return $o->id; }, $errors);

        $errors_comments_array = $this->getErrorsComments($errors_ids);
        $errors_notificationTypeUsers_array = $this->getErrorsNotificationTypeUsers($errors_ids);
        $errors_reports_array = Report::whereIn('error_id', $errors_ids)->get()->all();

        $errors_comments_ids = array_map(function($o) { return $o->id; }, $errors_comments_array);
        $errors_notificationTypeUsers_ids = array_map(function($o) { return $o->id; }, $errors_notificationTypeUsers_array);
        $errors_reports_ids = array_map(function($o) { return $o->id; }, $errors_reports_array);

        $reports_commentsAndNotificationTypeUsersIds = $this->reportService->getDependencies($errors_reports_array);
        $reports_comments_ids = $reports_commentsAndNotificationTypeUsersIds['comments_ids'];
        $reports_notificationTypeUsers_ids = $reports_commentsAndNotificationTypeUsersIds['notificationTypeUsers_ids'];

        $comments_ids = array_merge($reports_comments_ids, $errors_comments_ids);
        $notificationTypeUsers_ids = array_merge($reports_notificationTypeUsers_ids, $errors_notificationTypeUsers_ids);

        return compact('errors_reports_ids', 'comments_ids', 'notificationTypeUsers_ids');
    }
}