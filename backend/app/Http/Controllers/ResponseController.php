<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
       /**
     * success response method.
     */
    public function sendSuccessResponse($result, $message, $code)
    {
        $response = [
            'status' => $code,
            'data'    => $result,
            'message' => $message,
        ];

        return new JsonResponse($response, $code);
    }

        /**
     * error response method.
     */

     public function sendErrorResponse($error, $errorMessages = [], $code)
     {
        $response = [
            'status' => $code,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['error-message'] = $errorMessages;
        }

        return new JsonResponse($response, $code);
     }
}
