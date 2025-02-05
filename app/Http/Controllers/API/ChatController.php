<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     *@OA\GET(
     *   path="/api/chats",
     *   tags={"Chats"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *       response=200,
     *       description="Successful operation",
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
     *) 
     */

    public function index()
    {
        $chats = Chat::with(['createdBy', 'messages'])->get();
        return response()->json($chats);
    }

    /**
 * @OA\Post(
 *     path="/api/chats",
 *     summary="Create a new chat",
 *     description="Creates a new chat with the specified type and the authenticated user as the creator.",
 *     operationId="storeChat",
 *     tags={"Chats"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data needed to create a new chat",
 *         @OA\JsonContent(
 *             required={"type"},
 *             @OA\Property(property="type", type="string", example="private", description="Type of the chat"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Chat created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="private"),
 *             @OA\Property(property="created_by", type="integer", example=1),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(property="errors", type="object", example={"type": {"The type field is required."}}),
 *         ),
 *     ),
 * )
 */
public function store(Request $request)
{
    $chat = Chat::create([
        'type' => $request->type,
        'created_by' => auth()->id(),
    ]);
    
    return response()->json($chat, 201);
}

/**
 * @OA\GET(
 *     path="/api/chats/{id}",
 *     summary="Berilgan ID bo‘yicha chatni olish",
 *     tags={"Chats"},
 *     security={{"bearerAuth":{}}}, 
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Chat ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Muvaffaqiyatli so‘rov",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="private"),
 *             @OA\Property(property="created_by", type="integer", example=5),
 *             @OA\Property(
 *                 property="messages",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=10),
 *                     @OA\Property(property="chat_id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="integer", example=5),
 *                     @OA\Property(property="message", type="string", example="Salom!"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-04T12:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Chat topilmadi"
 *     )
 * )
 */
    public function show($id)
    {
        $chat = Chat::with('messages')->findOrFail($id);
        return response()->json($chat);
    }

}
