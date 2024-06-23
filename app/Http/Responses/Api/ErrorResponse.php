<?php

namespace app\Http\Responses\Api;

class ErrorResponse
{
    public static function response($message = 'Error', $statusCode = 404, $token = "", $data = [])
    {
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true,
            'token' => $token,
            'data' => $data,
        ], $statusCode);
    }
}
