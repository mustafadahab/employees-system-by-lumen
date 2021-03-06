<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/key_gen', function () {
    return \Illuminate\Support\Str::random(32);

});
$router->group(['prefix' => 'api/users'], function () use ($router) {


    $router->post('/register','UsersController@register');
    $router->post('/login','UsersController@loginEmail');
});



$router->group(['prefix' => 'api/departments','middleware' => 'auth:api'], function () use ($router) {
    $router->post('/', ['uses' => 'DepartmentController@createDepartment']);

    $router->get('/', ['uses' => 'DepartmentController@departmentsList']);

    $router->Patch('/{id}', ['uses' => 'DepartmentController@update']);

    $router->delete('/{id}', ['uses' => 'DepartmentController@delete']);
});

// unsecured routes
$router->group(['prefix' => 'api/employees'], function () use ($router) {
    $router->get('/top_paid_employees', ['uses' => 'EmployeeController@employeesTopPaid']);

    $router->get('/employees_salary_by_age', ['uses' => 'EmployeeController@employeesArgSalary']);

});


$router->group(['prefix' => 'api/employees','middleware' => 'auth:api'], function () use ($router) {

    $router->post('/', ['uses' => 'EmployeeController@createEmployee']);

    $router->get('/', ['uses' => 'EmployeeController@employeesList']);



    $router->put('/{id}', ['uses' => 'EmployeeController@update']);

    $router->delete('/{id}', ['uses' => 'EmployeeController@delete']);

});

$router->group(['prefix' => 'api/salaries','middleware' => 'auth:api'], function () use ($router) {

    $router->post('/', ['uses' => 'SalaryController@addSalary']);

    $router->get('/', ['uses' => 'SalaryController@salariesList']);

    $router->put('/{id}', ['uses' => 'SalaryController@update']);

    $router->delete('/{id}', ['uses' => 'SalaryController@delete']);

});

$router->group(['prefix' => 'api/dept_emp','middleware' => 'auth:api'], function () use ($router) {

    $router->post('/', ['uses' => 'DeptEmpController@add']);

    $router->get('/', ['uses' => 'DeptEmpController@list']);

    $router->put('/{id}', ['uses' => 'DeptEmpController@update']);

    $router->delete('/{id}', ['uses' => 'DeptEmpController@delete']);

});
