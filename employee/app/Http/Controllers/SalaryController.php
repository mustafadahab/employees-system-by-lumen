<?php

namespace App\Http\Controllers;


use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Salary;


class SalaryController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/salaries",
     *     summary="Get all salaries",
     *     tags={"Salary"},
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
    public function salariesList()
    {
        try {
            $allSalaries=Salary::all();

            if($allSalaries)return response(['success'=> true,'message'=> ["Salaries list"],'data' => $allSalaries],200);

            return response(['success'=> true,'message'=> ["No salary has been found"],'data' => Salary::all()],404);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/salaries",
     *     summary="Add a new salary",
     *     tags={"Salary"},
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
     *                     property="amount",
     *                     type="double"
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
     *                          "amount":10.500,
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
    public function addSalary(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'emp_no'        => 'required',
            'amount'        => 'required',
            'from_date'     => 'required',
            'to_date'       => 'required'
        ]);

        if(!Employee::find($request->emp_no))return response(['success'=> false,'message'=> ['Employee not found'],'data' => []],404);

        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){

            try {
                $salary = Salary::create($request->all());
                return response(['success'=> true,'message'=> ["Salary created successfully"],'data' => $salary],201);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Put(
     *     path="/api/salaries/{id}",
     *     summary="Update salary",
     *     tags={"Salary"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         description="Salary id",
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
     *                     property="amount",
     *                     type="double"
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
     *                          "amount":10.500,
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
            'emp_no'        => 'required',
            'amount'        => 'required',
            'from_date'     => 'required',
            'to_date'       => 'required'
        ]);

        if (empty($data))return response(['success'=> false,'message'=> ['Invalid Json format'],'data' => []],200);

        if($validator->passes()){

            try {
                $salary = Salary::findOrFail($id);
                $salary->update($request->all());
                return response(['success'=> true,'message'=> ["Salary updated successfully"],'data' => $salary],200);

            }
            catch(\Illuminate\Database\QueryException $ex){
                return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
            }
        }
        return response(['success'=> false,'message'=> $validator->errors()->all(),'data' => []]);

    }

    /**
     * @OA\Delete(
     *     path="/api/salaries/{id}",
     *     summary="Delete salary",
     *     tags={"Salary"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         description="Salary id",
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
            if(!$operation=Salary::find($id))return response(['success'=> false,'message'=> ["No Salary found for this number"],'data' => []],404);

            $operation->delete();

            if($operation)return response(['success'=> true,'message'=> ["Salary has been deleted"],'data' => []],200);

            return response(['success'=> true,'message'=> ["Salary could not been deleted"],'data' => []],200);

        }
        catch(\Illuminate\Database\QueryException $ex){
            return response(['success'=> false,'message'=> [$ex->getMessage()],'data' => []],500);
        }

    }
}
