<?php

namespace App\Core;

class Response
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): void {
        http_response_code($statusCode);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function error(
        string $message,
        int $statusCode = 400
    ): void {
        http_response_code($statusCode);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => $message
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function json(
        array $data,
        int $statusCode = 200
    ): void {
        http_response_code($statusCode);

        header('Content-Type: application/json');

        echo json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }
}
