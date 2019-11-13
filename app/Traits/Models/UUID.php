<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait UUID
{
	/*
	 * Boot function
	 *
	 * Hook into our model and listen for any Eloquent events
	*/
    protected static function bootUUID()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /*
	 * Disable autoincrementing on model
    */
    public function getIncrementing()
    {
        return false;
    }

    /*
	 * IDs on the table should be stored as strings.
    */
    public function getKeyType()
    {
        return 'string';
    }
}