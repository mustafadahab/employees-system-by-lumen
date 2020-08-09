<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Salary extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'salary_no';

    protected $fillable = ['emp_no','amount','from_date','to_date'];

    protected $dates = ['deleted_at'];

}
