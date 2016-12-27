<?php


namespace App\Facades;


use App\Services\FormBuilder;
use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FormBuilder::class;
    }
}