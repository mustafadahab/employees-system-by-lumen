<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Employee;


class EmployeeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Get all employees",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         description="page number",
     *         in="query",
     *         name="page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     example=1
     *     ),
     *     @OA\Parameter(
     *         description="limit of the item",
     *         in="query",
     *         name="limit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     example=5
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Could Not Find Resource",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function employeesList(Request $request)
    {
        $page=intval(1);$limit=intval(5);
        if ($request->has(['page', 'limit'])) {
            $page = intval($request->input('page'));
            $limit = intval($request->input('limit'));
        }
        $skip = ($page == 1 ? 0 : (($page - 1) * $limit));
        try {

            //$allEmployees=Employee::all();
            $allEmployees=DB::select(
                DB::raw("
                       SELECT employees.*,salaries.amount,departments.dept_name FROM employees
                        join salaries ON (salaries.emp_no = employees.emp_no)
                        join dept_emps ON (dept_emps.emp_no = employees.emp_no)
                        join departments ON (departments.dept_no = dept_emps.dept_no)
                        limit $skip,$limit

                        ")
            );

            if($allEmployees)return response(['success'=> true,'message'=> ["Employees list"],'data' => $allEmployees],200);

            return response(['success'=> true,'message'=> ["No employee has been found"],'data' => Employee::all()],404);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/employees/top_paid_employees",
     *     summary="Get Top paid employees",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         description="page number",
     *         in="query",
     *         name="page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     example=1
     *     ),
     *     @OA\Parameter(
     *         description="limit of the item",
     *         in="query",
     *         name="limit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     example=5
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Could Not Find Resource",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function employeesTopPaid(Request $request)
    {
        $page=intval(1);$limit=intval(5);
        if ($request->has(['page', 'limit'])) {
            $page = intval($request->input('page'));
            $limit = intval($request->input('limit'));
        }
        $skip = ($page == 1 ? 0 : (($page - 1) * $limit));
        try {

            //$allEmployees=Employee::all();
            $allEmployees=DB::select(
                DB::raw("
                     SELECT employees.*,salaries.amount AS salary,departments.dept_name FROM employees
                    join salaries ON (salaries.emp_no = employees.emp_no)
                    join dept_emps ON (dept_emps.emp_no = employees.emp_no)
                    join departments ON (departments.dept_no = dept_emps.dept_no)
                    ORDER BY salary DESC;
                        ")
            );

            if($allEmployees)return response(['success'=> true,'message'=> ["Employees list"],'data' => $allEmployees],200);

            return response(['success'=> true,'message'=> ["No employee has been found"],'data' => Employee::all()],404);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/employees/employees_salary_by_age",
     *     summary="Get Average employees Salary by Age",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         description="page number",
     *         in="query",
     *         name="page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     example=1
     *     ),
     *     @OA\Parameter(
     *         description="limit of the item",
     *         in="query",
     *         name="limit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     example=5
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Could Not Find Resource",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function employeesArgSalary(Request $request)
    {
        $page=intval(1);$limit=intval(5);
        if ($request->has(['page', 'limit'])) {
            $page = intval($request->input('page'));
            $limit = intval($request->input('limit'));
        }
        $skip = ($page == 1 ? 0 : (($page - 1) * $limit));
        try {

            //$allEmployees=Employee::all();
            $allEmployees=DB::select(
                DB::raw("
                       select TIMESTAMPDIFF(YEAR, employees.birth_date , '2019-01-21') AS age,
                       employees.first_name,employees.last_name,AVG(salaries.amount) AS salary,
                       departments.dept_name FROM employees
                        join salaries ON (salaries.emp_no = employees.emp_no)
                        join dept_emps ON (dept_emps.emp_no = employees.emp_no)
                        join departments ON (departments.dept_no = dept_emps.dept_no)
                        GROUP BY age
                        ORDER BY salary DESC
                       ")
            );

            if($allEmployees)return response(['success'=> true,'message'=> ["Employees list"],'data' => $allEmployees],200);

            return response(['success'=> true,'message'=> ["No employee has been found"],'data' => Employee::all()],404);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/employees",
     *     summary="Add a new employee      *Note: 1:male, 2:female, 3:other",
     *     tags={"Employee"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="integar"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="hire_date",
     *                     type="date"
     *                 ),
     *                 example={
     *                          "first_name":"Mustafa",
     *                          "last_name":"Dahab",
     *                          "gender":1,
     *                          "birth_date":"1988-11-11",
     *                          "hire_date":"2020-10-10",
     *                          }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Could Not Find Resource",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function createEmployee(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'first_name'             => 'required',
            'last_name'              => 'required',
            'gender'                 => 'required',
            'birth_date'             => 'required',
            'hire_date'              => 'required'
        ]);

        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){

            try {
                $employee = Employee::create($request->all());
                return response(['success'=> true,'message'=> ["Employee created successfully"],'data' => $employee],201);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Put(
     *     path="/api/employees/{id}",
     *     summary="Update employee",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         description="Employee id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="integar"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="hire_date",
     *                     type="date"
     *                 ),
     *                 example={
     *                          "first_name":"Mustafa",
     *                          "last_name":"Dahab",
     *                          "gender":1,
     *                          "birth_date":"1988-11-11",
     *                          "hire_date":"2020-10-10",
     *                          }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Could Not Find Resource",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function update($id, Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'first_name'             => 'required',
            'last_name'              => 'required',
            'gender'                 => 'required',
            'birth_date'             => 'required',
            'hire_date'              => 'required'
        ]);

        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){

            try {
                $employee = Employee::findOrFail($id);
                $employee->update($request->all());
                return response(['success'=> true,'message'=> ["Employee updated successfully"],'data' => $employee],200);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Delete(
     *     path="/api/employees/{id}",
     *     summary="Delete employee",
     *     tags={"Employee"},
     *     @OA\Parameter(
     *         description="Employee id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Could Not Find Resource",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function delete($id)
    {
        try {

            if(!$operation=Employee::find($id))return response(['success'=> false,'message'=> ["No Employee found for this number"],'data' => []],404);

            $operation->delete();

            if($operation)return response(['success'=> true,'message'=> ["Employee has been deleted"],'data' => []],200);

            return response(['success'=> true,'message'=> ["Employee could not been deleted"],'data' => []],200);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }

    }
}
