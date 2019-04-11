<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;

class MeetingProject extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.meeting_project';
    protected $primaryKey = 'meeting_project_id';

    public $timestamps = false;

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function project()
    {
    	return $this->belongsTo(Project::class, 'project_id');
    }

}
