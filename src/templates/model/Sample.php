<?php

namespace [name_space];

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class [class] extends Model
{
    use HasFactory;

    protected $table = '[table]';
    protected $fillable = [
        [fillable]
        'created_at'
    ];

     protected $casts = [
    [casts_field]
    ];

    //additional_methods//
}
