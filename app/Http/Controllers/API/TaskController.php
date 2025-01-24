<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTasktRequest;
use App\Models\Task;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use OpenApi\Annotations\Post;
use OpenApi\Attributes\Tag;

class TaskController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/tasks",
     *      operationId="getTasksList",
     *      tags={"Tasks"},
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
    public function index()
    {
        $tasks = Task::paginate(2);

    return response()->json([
        'success' => true,
        'data' => $tasks
    ], 200);
    }

    /**
 * @OA\Post(
 *     path="/api/tasks",
 *     tags={"Tasks"},
 *     security={{"bearerAuth":{}}},
 *     summary="Create a new post",
 *     description="This endpoint allows an authenticated user to create a new post.",
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Title of the post",
 *                 example="My First Post"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Content of the post",
 *                 example="This is the content of my first post."
 *             ),
 *               @OA\Property(
 *                 property="status_id",
 *                 type="integer",
 *                 description="Status_id",
 *                 example="1,2,3"
 *             ),
 *              
 *  
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=201,
 *         description="Post created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true
 *             ),
 *            
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Unauthorized"
 *             )
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="errors",
 *                 type="array",
 *                 @OA\Items(
 *                     type="string",
 *                     example="The title field is required."
 *                 )
 *             )
 *         )
 *     )
 * )
**/
   
    public function store(StoreTasktRequest $request)
    {
        $validated = $request->validated();
 
        $task= Task::create($validated);
        return $task;
    

        
    }

    /**
     * Получить данные пользователя по ID.
     *
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Получение данных пользователя по ID",
     *     description="Возвращает информацию о пользователе только для авторизованных пользователей.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description=" не найден"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="доступ"
     *     )
     * )
     */
    public function show(string $id)
    {
        $task= Task::find($id);
        return $task;
    }

    

/**
 * @OA\Put(
 *     path="/api/tasks/{id}",
 *     tags={"Tasks"},
 *     summary="Update an existing post",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Title of the post",
 *                 example="My First Post"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Content of the post",
 *                 example="This is the content of my first post."
 *             ),
 *               @OA\Property(
 *                 property="status_id",
 *                 type="integer",
 *                 description="Status_id",
 *                 example="1,2,3"
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

 public function update(StoreTasktRequest $request, $id)
 {
    $validated = $request->validated();
    $task = Task::find($id);
    if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
    }
    $task->update($validated);
    return response()->json(['success' => $task], 200);
 }

    /**
 * @OA\Delete(
 *     path="/api/tasks/{id}",
 *     tags={"Tasks"},
 *     summary="Delete an existing post",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Post deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(response="404", description="Post not found")
 * )
 */

    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
        }
        $task->delete(); 
        return 'success';
    }
}
