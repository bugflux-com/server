<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'api/v1'], function () {
    Route::post('errors', 'API\v1\ErrorController@store');
});

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'ProjectController@index');

    // Authentication
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    // Password Reset
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    // Profile
    Route::get('profile', ['as' => 'profile.index', 'uses' => 'ProfileController@index']);
    Route::get('profile/edit', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile/password', ['as' => 'profile.update.password', 'uses' => 'ProfileController@updatePassword']);
    Route::put('profile/photo', ['as' => 'profile.update.photo', 'uses' => 'ProfileController@updatePhoto']);
    Route::delete('profile/photo', ['as' => 'profile.destroy.photo', 'uses' => 'ProfileController@destroyPhoto']);
    Route::put('profile/name', ['as' => 'profile.update.name', 'uses' => 'ProfileController@updateName']);
    Route::put('profile/email', ['as' => 'profile.update.email', 'uses' => 'ProfileController@sendEmailConfirmation']);
    Route::get('profile/email/{token}', ['as' => 'profile.confirm.newemail', 'uses' => 'ProfileController@confirmNewEmail']);
    Route::put('profile/email/{token}', ['as' => 'profile.confirm.newemail.update', 'uses' => 'ProfileController@confirmNewEmailUpdate']);
    Route::get('profile/permissions', ['as' => 'profile.permissions.index', 'uses' => 'Profile\PermissionController@index']);

    // Notifications
    Route::get('profile/notifications', ['as' => 'profile.notifications.index', 'uses' => 'Profile\NotificationsController@index']);
    Route::put('profile/notifications', ['as' => 'profile.update.notifications', 'uses' => 'ProfileController@updateNotifications']);
    Route::get('profile/notifications/edit', ['as' => 'profile.notifications.edit', 'uses' => 'Profile\NotificationsController@edit']);
    Route::get('profile/notifications/{id}', ['as' => 'profile.notifications.show', 'uses' => 'Profile\NotificationsController@showGroup']);
    Route::put('profile/notifications/errors/{id}', ['as' => 'profile.notifications.errors.update', 'uses' => 'Profile\NotificationsController@updateErrorsNotifications']);
    Route::put('profile/notifications/reports/{id}', ['as' => 'profile.notifications.reports.update', 'uses' => 'Profile\NotificationsController@updateReportsNotifications']);
    Route::put('profile/notifications/errors/comments/{id}', ['as' => 'profile.notifications.errors.comments.update', 'uses' => 'Profile\NotificationsController@updateErrorsCommentsNotifications']);
    Route::put('profile/notifications/reports/comments/{id}', ['as' => 'profile.notifications.reports.comments.update', 'uses' => 'Profile\NotificationsController@updateReportsCommentsNotifications']);

    // Users
    Route::resource('users', 'UserController');
    Route::resource('users.permissions', 'User\PermissionController', ['except' => ['show', 'update', 'destroy']]);
    Route::get('users/{id}/photos/{size}', ['as' => 'users.photo', 'uses' => 'UserController@photo']);

    //Systems
    Route::resource('systems', 'SystemController', ['except' => 'show']);

    //Languages
    Route::resource('languages', 'LanguageController', ['except' => 'show']);

    // Projects, assigned permissions, errors, reports and comments
    Route::resource('projects', 'ProjectController');
    Route::resource('projects.permissions', 'Project\PermissionController', ['except' => 'show']);
    Route::resource('projects.versions', 'Project\VersionController', ['except' => 'show']);
    Route::resource('projects.mappings', 'Project\MappingController', ['except' => 'show']);
    Route::resource('projects.tags', 'Project\TagController', ['except' => 'show']);
    Route::post('projects/{project_id}/errors/{error_id}/tags/create', ['as' => 'projects.errors.tags.store', 'uses' => 'Project\TagController@storeTag']);
    Route::delete('projects/{project_id}/errors/{error_id}/tags/{tag_id}', ['as' => 'projects.errors.tags.destroy', 'uses' => 'Project\TagController@destroyTag']);
    Route::get('projects/{project_id}/default-mappings', ['as' => 'projects.default-mappings', 'uses' => 'Project\MappingController@defaultMappingsIndex']);
    Route::resource('projects.rejection-rules', 'Project\RejectionRuleController', ['except' => 'show']);
    Route::resource('projects.errors', 'Project\ErrorController', ['except' => ['create', 'store']]);
    Route::resource('projects.flat-reports', 'Project\FlatReportController', ['only' => ['index', 'show', 'destroy']]);
    Route::post('projects/{project_id}/flat-reports/{flat_report_id}', ['as' => 'projects.flat-reports.redo', 'uses' => 'Project\FlatReportController@redoReport']);
    Route::resource('projects.errors.reports', 'Project\Error\ReportController', ['only' => ['index', 'show', 'destroy']]);
    Route::resource('projects.errors.comments', 'Project\Error\CommentController', ['except' => ['show', 'create']]);
    Route::resource('projects.errors.duplicates', 'Project\Error\DuplicateController', ['only' => ['index', 'create', 'store', 'destroy']]);
    Route::resource('projects.errors.reports.comments', 'Project\Error\Report\CommentController', ['except' => ['create', 'show']]);
});