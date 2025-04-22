<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{
    /**
     * Success Response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($data, string $message = '', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param int $code
     * @param array $errors
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $code = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Handle validation exception for API
     *
     * @param ValidationException $exception
     * @return JsonResponse
     */
    public function validationErrorResponse(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
} 