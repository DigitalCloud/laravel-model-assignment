<?php

namespace DigitalCloud\ModelAssignment\Traits;

use DigitalCloud\ModelAssignment\Events\NewAssignment;
use DigitalCloud\ModelAssignment\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Spatie\ModelStatus\Events\StatusUpdated;
use Spatie\ModelStatus\Exceptions\InvalidStatus;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait Assignable
{
    public function assignments(): MorphMany
    {
        return $this->morphMany(
            $this->getAssignmentModelClassName(),
            'model',
            'model_type',
            'model_id'
        )->latest('id');
    }

    public function assignment(): ?Assignment
    {
        return $this->latestAssignment();
    }

    public function assignTo($userId, ?string $note = null): self
    {
        $oldAssignment = $this->latestAssignment();

        if($oldAssignment->user_id == $userId) {
            return $this;
        }

        $newAssignment = $this->assignments()->create([
            'user_id'   => $userId,
            'note' => $note,
        ]);

        event(new NewAssignment($oldAssignment, $newAssignment, $this));

        return $this;
    }

    public function latestAssignment(): ?Assignment
    {
        $assignments = $this->relationLoaded('assignments') ? $this->assignments : $this->assignments();
        return $assignments->first();
    }

    public function getAssignmentAttribute(): string
    {
        return (string) $this->latestAssignment();
    }


    protected function getAssignmentTableName(): string
    {
        $modelClass = $this->getAssignmentModelClassName();

        return (new $modelClass)->getTable();
    }

    protected function getAssignmentModelClassName(): string
    {
        return config('model-assignment.assignment_model');
    }

    protected function getAssignmentModelType(): string
    {
        return array_search(static::class, Relation::morphMap()) ?: static::class;
    }

    public function getAssignee() {

    }
}
