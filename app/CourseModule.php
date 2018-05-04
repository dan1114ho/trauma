<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model {
    protected $guarded  = array('id');

    public $timestamps = false;

    public function documents()
    {
        return $this->hasMany('App\CourseModuleDocument');
    }
}
