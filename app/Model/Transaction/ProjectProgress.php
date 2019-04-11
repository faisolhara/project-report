<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;
use App\Model\Transaction\MilestoneProgress;

class ProjectProgress extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.project_progress';
    protected $primaryKey = 'project_progress_id';

    public $timestamps = false;

    public function milestoneProgress()
    {
        return $this->hasMany(MilestoneProgress::class, 'project_progress_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
