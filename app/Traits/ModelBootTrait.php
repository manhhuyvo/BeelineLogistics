<?php

namespace App\Traits;

trait ModelBootTrait
{
    public static function modelBoot()
    {
        parent::boot();

        static::creating(function ($newItem) {
            static::beforeCreated($newItem);
        });

        static::created(function ($newItem) {
            static::afterCreated($newItem);
        });

        static::updating(function ($newItem) {
            static::beforeUpdate($newItem);
        });

        static::updated(function ($newItem) {
            static::afterUpdated($newItem);
        });
    }

    public static function beforeCreated() {}
    
    public static function afterCreated() {}

    public static function beforeUpdated() {}

    public static function afterUpdated() {}
}