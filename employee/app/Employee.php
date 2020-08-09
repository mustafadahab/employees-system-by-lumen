<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Employee extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'emp_no';

    protected $fillable = ['first_name','last_name','gender','birth_date','hire_date'];

    protected $dates = ['deleted_at'];
    /**
     * Get the employee Salaries.
     */
    public function user()
    {
        return $this->hasOne('App\Department', 'user_id', 'user_id');
    }

}
