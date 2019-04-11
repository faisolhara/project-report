<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;
use App\Model\Transaction\Task;

class Milestone extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.milestone';
    protected $primaryKey = 'milestone_id';

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function milestoneProgress()
    {
        return $this->hasMany(MilestoneProgress::class, 'milestone_id');
    }

    public function getLastMilestoneProgress()
    {
        return $this->milestoneProgress()
                    ->orderBy('created_date', 'desc')
                    ->orderBy('milestone_progress_id', 'desc')
                    ->first();
    }

    public function getLastValidatedMilestoneProgress()
    {
        return $this->milestoneProgress()
                    ->whereNotNull('validated_date')
                    ->orderBy('created_date', 'desc')
                    ->orderBy('milestone_progress_id', 'desc')
                    ->first();
    }
}
