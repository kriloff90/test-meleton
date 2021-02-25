<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Http\Response;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getResponse($message, $status = Response::HTTP_OK)
    {
        if ($status === Response::HTTP_INTERNAL_SERVER_ERROR) {
            $message = 'Произошла ошибка. Попробуйте позже.';

            Log::error("Ошибка: {$message}");
        }

        return response()->json([
            'status' => ($status < Response::HTTP_OK || $status >= Response::HTTP_MULTIPLE_CHOICES) ? 'error' : 'success',
            'message' => $message
        ], $status);
    }

    public function getSuccessResponse()
    {
        return response()->json([
            'status' => 'success',
        ]);
    }
}
