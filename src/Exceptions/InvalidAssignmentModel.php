<?php

namespace DigitalCloud\ModelAssignment\Exceptions;

use Exception;

class InvalidAssignmentModel extends Exception
{
    public static function create(string $model): self
    {
        return new self("The model `{$model}` is invalid. A valid model must extend the model \DigitalCloud\ModelAssignment\Models\Assignment.");
    }
}
