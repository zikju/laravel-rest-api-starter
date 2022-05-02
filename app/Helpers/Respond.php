<?php


namespace App\Helpers;


use Illuminate\Http\JsonResponse;

class Respond
{

    /**
     * Return successful JSON response
     *
     * @param string $message
     * @param array $data
     * @param int $http_code
     * @return JsonResponse
     */
    public static function ok (string $message, array $data = [],  int $http_code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => $message,
            'data' => (empty($data) ? NULL : $data)
        ], $http_code);
    }

    /**
     * Return JSON response with error
     *
     * @param string $message
     * @param int $http_code
     * @return JsonResponse
     */
    public static function error (string $message, int $http_code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $http_code);
    }
}
