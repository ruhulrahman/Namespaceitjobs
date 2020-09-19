<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $table = 'job_posts';
    protected $with = ['user'];

    protected $fillable = [
        'user_id','job_title','job_description', 'salary', 'location', 'country',
    ];

    protected $primaryKey = 'id';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applies()
    {
        return $this->hasMany(JobApply::class);
    }
}
