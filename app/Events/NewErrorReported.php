<?php


namespace App\Events;


use App\Models\Error;

class NewErrorReported
{
    /**
     * New reported error.
     *
     * @var Error
     */
    public $error;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Error $error)
    {
        $this->error = $error;
    }
}