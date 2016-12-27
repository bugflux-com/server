<?php


namespace App\Events;


use App\Models\Permission;

class ChangedPrivilege extends Event
{
    /**
     * New permission given.
     *
     * @var Permission
     */
    public $permission;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
}