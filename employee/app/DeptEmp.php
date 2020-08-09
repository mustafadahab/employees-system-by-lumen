<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DeptEmp extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'dept_emp_no';

    protected $fillable = ['emp_no','dept_no','from_date','to_date'];

    protected $dates = ['deleted_at'];

}
