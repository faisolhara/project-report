<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;
use App\Model\Transaction\TaskProgress;
use App\Model\Transaction\Milestone;

class MilestoneProgress extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.milestone_progress';
    protected $primaryKey = 'milestone_progress_id';

    public $timestamps = false;

    public function taskProgress()
    {
        return $this->hasMany(TaskProgress::class, 'milestone_progress_id');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }
}
