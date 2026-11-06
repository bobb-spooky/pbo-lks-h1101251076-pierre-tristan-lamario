<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $query;
    private array $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $this->uri = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        );

        $this->query = $_GET;

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $input = file_get_contents('php://input');
            $this->body = json_decode($input, true) ?? [];
        } else {
            $this->body = $_POST;
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key]
            ?? $this->query[$key]
            ?? $default;
    }
}