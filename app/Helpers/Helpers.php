<?php

function errorResponse($status = 400, $message = 'Bad Request', $errors = [])
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'errors' => $errors
    ], $status);
}