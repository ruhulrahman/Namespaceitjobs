<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $with = ['user'];

    protected $fillable = [
        'user_id','resume','skills',
    ];

    protected $primaryKey = 'id';


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
