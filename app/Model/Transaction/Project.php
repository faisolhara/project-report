<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.v_project';
    protected $primaryKey = 'inventory_item_id';

    public $timestamps = false;

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'project_id');
    }

    public function projectProgress()
    {
        return $this->hasMany(ProjectProgress::class, 'project_id');
    }
}
