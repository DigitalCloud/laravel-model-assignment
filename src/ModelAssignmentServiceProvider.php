<?php

namespace DigitalCloud\ModelAssignment;

use DigitalCloud\ModelAssignment\Models\Assignment;
use Illuminate\Support\ServiceProvider;
use DigitalCloud\ModelAssignment\Exceptions\InvalidAssignmentModel;

class ModelAssignmentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations/');
        }

        if (! class_exists('CreateAssignmentsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_assignments_table.php.stub' => database_path('migrations/'.$timestamp.'_create_assignments_table.php'),
            ], 'migrations');
        }

        $this->publishes([
            __DIR__.'/../config/model-assignment.php' => config_path('model-assignment.php'),
        ], 'config');

        $this->guardAgainstInvalidAssignmentModel();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/model-assignment.php', 'model-assignment');
    }

    public function guardAgainstInvalidAssignmentModel()
    {
        $modelClassName = config('model-assignment.assignment_model');

        if (! is_a($modelClassName, Assignment::class, true)) {
            throw InvalidAssignmentModel::create($modelClassName);
        }
    }
}
