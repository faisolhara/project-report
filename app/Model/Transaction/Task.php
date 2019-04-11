<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.task';
    protected $primaryKey = 'task_id';

    public $timestamps = false;

    public function taskProgress()
    {
        return $this->hasMany(TaskProgress::class, 'task_id');
    }

    public function getLastTaskProgress()
    {
        return $this->taskProgress()
                ->orderBy('created_date', 'desc')
                ->orderBy('task_id', 'desc')
                ->first();
    }

    public function getLastValidatedTaskProgress()
    {
        return $this->taskProgress()
                ->whereNotNull('validated_date')
                ->orderBy('created_date', 'desc')
                ->orderBy('task_id', 'desc')
                ->first();
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

}
