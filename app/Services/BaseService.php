<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class BaseService
{
    /**
     * @param $success
     * @param  null  $message
     * @param  null  $data
     * @param  int  $statusCode
     * @return object
     */
    public function response($success, $message = null, $data = null, int $statusCode = Response::HTTP_OK): object
    {
        return (object) [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'code' => $statusCode,
        ];
    }
}
