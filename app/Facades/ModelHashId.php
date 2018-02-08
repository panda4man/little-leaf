<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ModelHashId extends Facade
{
    protected static function getFacadeAccessor()
    {
        return self::class;
    }
}