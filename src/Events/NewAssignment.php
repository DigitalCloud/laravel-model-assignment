<?php

namespace DigitalCloud\ModelAssignment\Events;

use DigitalCloud\ModelAssignment\Models\Assignment;
use Illuminate\Database\Eloquent\Model;

class NewAssignment
{
    /** @var \DigitalCloud\ModelAssignment\Models\Assignment|null */
    public $oldAssignment;

    /** @var \DigitalCloud\ModelAssignment\Models\Assignment */
    public $newAssignment;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model;

    public function __construct(?Assignment $oldAssignment, Assignment $newAssignment, Model $model)
    {
        $this->oldAssignment = $oldAssignment;

        $this->newAssignment = $newAssignment;

        $this->model = $model;
    }
}
