<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Department;


class DepartmentController extends BaseController
{
    /**
     * @OA\Info(title="Employee API", version="0.1")
     * Shaadoow API.
     *      *@OA\Server(
     *         description="Production",
     *         url="http://localhost/emp/employees-system-by-lumen/employee/public/",
     *     )
     */

    /**
     * @OA\Get(
     *     path="/api/departments",
     *     summary="Get all departments",
     *     tags={"Department"},
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
    public function departmentsList()
    {
        try {
            $allDepartments=Department::all();

            if($allDepartments)return response(['success'=> true,'message'=> ["Departments list"],'data' => $allDepartments],200);

            return response(['success'=> true,'message'=> ["No department has been found"],'data' => Department::all()],404);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/departments",
     *     summary="Add a new department",
     *     tags={"Department"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "dept_name":"IT",
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
    public function createDepartment(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'dept_name'             => 'required|unique:departments'
        ]);

        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){

            try {
                $department = Department::create($request->all());
                return response(['success'=> true,'message'=> ["Department created successfully"],'data' => $department],201);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Patch(
     *     path="/api/departments/{id}",
     *     summary="Update department",
     *     tags={"Department"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         description="Department id",
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
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "dept_name":"New Name",
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
            'dept_name'             => 'required|unique:departments'
        ]);

        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){

            try {
                $department = Department::findOrFail($id);
                $department->update($request->all());
                return response(['success'=> true,'message'=> ["Department updated successfully"],'data' => $department],200);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Delete(
     *     path="/api/departments/{id}",
     *     summary="Delete department",
     *     tags={"Department"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         description="Department id",
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
            if(!$operation=Department::find($id))return response(['success'=> false,'message'=> ["No Department found for this number"],'data' => []],404);
            $operation->delete();

            if($operation)return response(['success'=> true,'message'=> ["Department has been deleted"],'data' => []],200);

            return response(['success'=> true,'message'=> ["Department could not been deleted"],'data' => []],200);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }

    }
}
