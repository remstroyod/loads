<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LoggableUserTrait
{

    use LogsActivity;

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at']);
    }

    /**
     * @return void
     */
    protected static function bootLoggableByRole()
    {

        $events = ['creating', 'created', 'updating', 'updated', 'saving', 'saved', 'deleting', 'deleted'];

        foreach ($events as $event)
        {

            static::$event(function ($model)
            {

                if (auth()->check() && auth()->user()->hasRole('manager'))
                {
                    $model->enableLogging();
                } else {
                    $model->disableLogging();
                }

            });
        }

    }

    /**
     * Boot the loggable user trait.
     */
    public static function bootLoggableUserTrait()
    {
        static::bootLoggableByRole();
    }

}
