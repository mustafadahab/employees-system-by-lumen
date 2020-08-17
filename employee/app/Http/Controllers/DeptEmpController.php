<?php

namespace App\Http\Controllers;


use App\Department;
use App\Employee;
use App\DeptEmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;


class DeptEmpController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/dept_emp",
     *     summary="Get all employees departments",
     *     tags={"Employees Departments"},
     *     security={{"Bearer":{}}},
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
    public function list()
    {
        try {
            $all=DeptEmp::all();

            if($all)return response(['success'=> true,'message'=> ["employees departments list"],'data' => $all],200);

            return response(['success'=> true,'message'=> ["No salary has been found"],'data' => Department::all()],404);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/dept_emp",
     *     summary="Add a new employee to a department",
     *     tags={"Employees Departments"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="employee number",
     *                     type="integar"
     *                 ),
     *                 @OA\Property(
     *                     property="Department number",
     *                     type="integar"
     *                 ),
     *                 @OA\Property(
     *                     property="from_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="to_date",
     *                     type="date"
     *                 ),
     *                 example={
     *                          "emp_no":1,
     *                          "dept_no":1,
     *                          "from_date":"2020-10-10",
     *                          "to_date":"2020-08-09",
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
    public function add(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'dept_no'       => 'required',
            'emp_no'        => 'required|unique:dept_emps',
            'from_date'     => 'required',
            'to_date'       => 'required'
        ]);


        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){
            if(!Employee::find($request->emp_no))return response(['success'=> false,'message'=> ['Employee not found'],'data' => []],404);

            if(!Department::find($request->dept_no))return response(['success'=> false,'message'=> ['Department not found'],'data' => []],404);

            try {
                $salary = DeptEmp::create($request->all());
                return response(['success'=> true,'message'=> ["Employee has been added to department "],'data' => $salary],201);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Put(
     *     path="/api/dept_emp/{id}",
     *     summary="Update employee department",
     *     tags={"Employees Departments"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         description="Department Employee id",
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
     *                     property="employee number",
     *                     type="integar"
     *                 ),
     *                 @OA\Property(
     *                     property="Department number",
     *                     type="integar"
     *                 ),
     *                 @OA\Property(
     *                     property="from_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="to_date",
     *                     type="date"
     *                 ),
     *                 example={
     *                          "emp_no":1,
     *                          "dept_no":1,
     *                          "from_date":"2020-10-10",
     *                          "to_date":"2020-08-09",
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
    public function update($id,Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'dept_no'       => 'required',
            'emp_no'        => 'required',
            'from_date'     => 'required',
            'to_date'       => 'required'
        ]);


        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){
            if(!Employee::find($request->emp_no))return response(['success'=> false,'message'=> ['Employee not found'],'data' => []],404);

            if(!Department::find($request->dept_no))return response(['success'=> false,'message'=> ['Department not found'],'data' => []],404);


            try {
                $deptEmp = DeptEmp::findOrFail($id);
                $deptEmp->update($request->all());
                return response(['success'=> true,'message'=> ["Department updated successfully"],'data' => $deptEmp],200);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Delete(
     *     path="/api/dept_emp/{id}",
     *     summary="Delete Dempartment Employee attachment",
     *     tags={"Employees Departments"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         description="Department Employee id",
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
            if(!$operation=DeptEmp::find($id))return response(['success'=> false,'message'=> ["No Department found for this number"],'data' => []],404);

            $operation->delete();

            if($operation)return response(['success'=> true,'message'=> ["Department has been deleted"],'data' => []],200);

            return response(['success'=> true,'message'=> ["Department could not been deleted"],'data' => []],200);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }

    }
}
