<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     ** path="/api/register",
     *   tags={"Register"},
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
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="mobile_number",
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
    
     public function register(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required',
             'email' => 'required|email|unique:users',
             'password' => 'required',
             'mobile_number' => 'required'
         ]);
 
         if ($validator->fails()) {
             return response()->json(['error' => $validator->errors()], 401);
         }
 
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
     *      tags={"Login"},

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
     *      name="email",
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
        'email' => 'email|required',
        'password' => 'required',
    ]);

    // Поиск пользователя по email
    $profile = User::where('email', $request->email)->first();

    if (!$profile) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email',
        ], 404);
    }

   
    if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }

    
    $accessToken = $profile->createToken('Client Access Token');

    return response()->json([
        'status' => 'success',
        'access_token' => $accessToken,
    ]);

    }
    /** 
    * @OA\Post(
        *     path="/api/logout",
        *     tags={"logout"},
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
        *     @OA\Response(response=401, description="Unauthorized")
        * @OA\Response(
        *      response=404,
        *      description="not found"
        *  
        * ),
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

}
