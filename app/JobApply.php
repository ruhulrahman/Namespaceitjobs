<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    protected $table = 'job_applies';

    protected $with = ['user'];

    protected $fillable = [
        'job_id', 'user_id',
    ];

    protected $primaryKey = 'id';


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function applicant()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(JobPost::class);
    }
}
