<?php

namespace app\Http\Responses\Api;

class SuccessfulResponse
{
    public static function response($message = 'Success', $statusCode = 200, $token = "", $data = [])
    {
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => false,
            'token' => $token,
            'data' => $data,
        ], $statusCode);
    }
}
