<?php
namespace App\Http\Traits;

// Trait is a way to reuse methods and properties in multiple classes without using inheritance.
trait ApiResponse
{
    protected function successResponse(
        string $message = 'Success',
        int $code = 200,
        mixed $data = null
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(
        string $message = 'Error',
        int $code = 400,
        mixed $data = null,
    ) {
        return response()->json([
            'error'   => false,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }
}
