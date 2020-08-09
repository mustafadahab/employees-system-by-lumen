<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Department extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'dept_no';

    protected $fillable = ['dept_name'];

    protected $dates = ['deleted_at'];

}
