<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonTask extends Pivot
{
    public $incrementing = true;

    protected $fillable = ['id', 'person_id', 'task_id'];

    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
    }
}
