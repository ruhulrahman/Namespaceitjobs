<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blood extends Model
{
    protected $table = 'blood';

    protected $fillable = [
        'blood_name', 'blood_name_bn',
    ];

    protected $primaryKey = 'id';
}
