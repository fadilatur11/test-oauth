<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'error' => 0,
            'data' => $result,
            'message' => $message
        ];

        return response()->json($response, $code);
    }

    function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'error' => 1,
            'message' => $error
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
