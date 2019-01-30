<?php

namespace DigitalCloud\ModelAssignment\Models;

use App\User;
use DigitalCloud\Blameable\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Assignment extends Model
{
    use Blameable;

    protected $guarded = [];

    protected $table = 'assignments';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString(): string
    {
        return $this->note?? '';
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
