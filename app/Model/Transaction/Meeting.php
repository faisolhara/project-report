<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.meeting';
    protected $primaryKey = 'meeting_id';

    public $timestamps = false;

    public function meetingProject()
    {
        return $this->hasMany(MeetingProject::class, 'meeting_id');
    }

}
