<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    protected $table = 'grades';
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
