<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  
     *path="/api/register",
     *   tags={"Users"},
     *
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="avatar",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="login",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function register(UserCreateUserRequest $request)
    {
       
        $validator =$request->validated();

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 401);
        // }
        // dd($request);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('authToken')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success' => $success])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="getActiveTestList",
     *      tags={"Users"},
     *      summary="Get list of active tests",
     *      description="Returns list of active tests",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      
     *      ),
     *      @OA\Parameter(
     *      name="login",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *       @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    public function login(Request $request)
    {
        $validator = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);


        $profile = User::where('login', $request->login)->first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login',
            ], 404);
        }


        if (!auth()->attempt(['login' => $request->login, 'password' => $request->password])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }


        $accessToken = $profile->createToken('Client Access Token')->accessToken;

        return response()->json([
            'status' => 'success',
            'access_token' => $accessToken,
        ]);
    }
    /** 
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Users"},
     *     summary="Logout the current user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     * )
     */
    public function logout(Request $request)
    {

        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="getTasksList",
     *      tags={"Users"},
     *   security={{"bearerAuth":{}}},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getUser(Request $request)
    {
        return response()->json(['user' => $request->user()], 200);
    }
    /**
     * @OA\Put(
     *     path="/api/user",
     *     tags={"Users"},
     *     summary="Update an existing post",
     *     security={{"bearerAuth":{}}},
     *    
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name",
     *                 example="Burhon"
     *             ),
     *             @OA\Property(
     *                 property="login",
     *                 type="string",
     *                 description="login",
     *                 example="000000"
     *             ),
     *               @OA\Property(
     *                 property="password",
     *                 type="integer",
     *                 description="password",
     *                 example="12345678"
     *             ),
     *              @OA\Property(
     *                 property="password_confirmation",
     *                 type="integer",
     *                 description="password_confirmation",
     *                 example="12345678"
     *             ),
     *             
     *                @OA\Property(
     *                 property="avatar",
     *                 type="integer",
     *                 description="avatar",
     *                 example="avatar.png"
     *             ),
     *              
     *  
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="object", @OA\AdditionalProperties(type="string"))
     *         )
     *     ),
     *     @OA\Response(response="404", description="Post not found")
     * )
     */
    public function update(UserCreateUserRequest $request)
    {
        $validator = $request->validated();

        
        $user = User::find($request->user()->id);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user->update($validator);
        return response()->json(['success', $user])->setStatusCode(Response::HTTP_CREATED);
    }
}
