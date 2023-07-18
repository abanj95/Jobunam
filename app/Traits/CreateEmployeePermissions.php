<?php

namespace App\Traits;

use App\Definitions\UserTypes;
use function redirect;
use function request;

trait CreateEmployeePermissions
{
    public static function bootCreateEmployeePermissions()
    {
        static::creating(function ($model) {
            if (empty(request()->user_type) === false) {
                $model->user_type = request()->user_type;
                $model->active = request()->user_type == UserTypes::EMPLOYER ? 0 : 1;
            }
        });

        static::created(function ($model) {
            if (intval($model->user_type) === UserTypes::EMPLOYER) {
                $model->delete();
            }
        });
    }
}
