<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;
use App\Model\Transaction\Task;

class TaskProgress extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.task_progress';
    protected $primaryKey = 'task_progress_id';

    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
