<?php

namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;

class AccessControl extends Model
{
    protected $connection = 'chardonnay';
    protected $table      = 'iapsys.dpr_access_control';
    protected $primaryKey = 'dpr_access_control_id';

    public $timestamps = false;

    public function canAccess($resource, $privilege){
            if ($this->resources == $resource && $this->privilege == $privilege) {
                return true;
            }
        return false;
    }

}
