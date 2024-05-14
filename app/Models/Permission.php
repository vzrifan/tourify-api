<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Permission extends SpatieRole
{

    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = false;
}
