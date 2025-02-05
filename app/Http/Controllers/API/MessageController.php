<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     *  @OA\GET(
     *     path="/api/chats/{id}/messages",
     *     tags={"Messages"},
     *     security={{"bearerAuth":{}}},
     * @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="Chat ID",
    *         @OA\Schema(type="integer", example=1)
    *     ),
     *       @OA\Response(
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
     * )
     * 
     * 
     */
    public function index($chat_id)
    {
        return Message::where('chat_id', $chat_id)->get();
     
    }

    // Отправка сообщения (POST /api/chats/{id}/messages)
    /**
 * @OA\Post(
 *     path="/api/chats/{id}/messages",
 *     summary="Отправить сообщение в чат",
 *     description="Создает новое сообщение в указанном чате.",
 *     operationId="storeMessage",
 *     tags={"Messages"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID чата, в который отправляется сообщение",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"message"},
 *             @OA\Property(property="message", type="string", example="Привет!"),
 *             @OA\Property(property="file_url", type="string", nullable=true, example="https://example.com/file.png")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Сообщение успешно создано",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="chat_id", type="integer", example=123),
 *             @OA\Property(property="user_id", type="integer", example=45),
 *             @OA\Property(property="message", type="string", example="Привет!"),
 *             @OA\Property(property="file_url", type="string", nullable=true, example="https://example.com/file.png"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-05T12:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Ошибка валидации входных данных"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Неавторизованный доступ"
 *     )
 * )
 */

    public function store(Request $request, $chat_id)
    {
        // dd($request);
        $message = Message::create([
            'chat_id' => $chat_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'file_url' => $request->file_url,
        ]);

        return response()->json($message, 201);
    }
}
