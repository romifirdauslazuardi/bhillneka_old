<?php

namespace App\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper{

    public static function apiResponse(bool $status = true, mixed $message = null, mixed $data = null, array $included = null, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        
        if ($data instanceof MessageBag) {
            return response()->json($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            return response()->json([
                'success' => $status ? true : false,
                'message' => $message,
                'data' => $data,
                'included' => $included,
            ], $statusCode);
        }
    }
}
