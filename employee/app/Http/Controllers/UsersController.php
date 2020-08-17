<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;


class UsersController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/users/register",
     *     summary="Register a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "name":"Mustafa",
     *                          "email":"gm.wahbi@gmail.com",
     *                          "password":"123456"
     *                  }
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response(['success'=> false,'message'=> [$validator->errors()],'data' => []],422);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        /**Take note of this: Your user authentication access token is generated here **/
        $data['token'] =  $user->createToken('MyApp')->accessToken;
        $data['name'] =  $user->name;

        return response(['success'=> true,'message'=> ['Account created successfully!'],'data' => [$data]],201);

    }
    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     summary="Login with email",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "email":"gm.wahbi@gmail.com",
     *                          "password":"123456"
     *                          }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Logged in Successfully",
     *      @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid info",
     *      @OA\JsonContent()
     *     )
     * )
     */
    public function loginEmail(Request $request)
    {

        $loginData = $request->json()->all();
        $validator = \Illuminate\Support\Facades\Validator::make($loginData, [
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $client_id = env('CLIENT_ID');
            $client_secret = env('CLIENT_SECRET');
            $username = $request->input('email');
            $password = $request->input('password');

            $guzzle = new Client;
            $response = $guzzle->post('http://localhost/full-emp/public/v1/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'username' => $username,
                    'password' => $password,
                    'scope' => '*',
                ],
            ]);
            $reply = json_decode($response->getBody(), true);
            $token = $reply['access_token'];

            return response([
                'success'=> true,
                'message' => ["Successfully logedin"],
                'data'          => [[
                    'accessToken'   => $token]
                ]]);

        }

        return response(['success'=> false,'message' => $validator->errors()->all(), 'data' => []]);


    }
}
