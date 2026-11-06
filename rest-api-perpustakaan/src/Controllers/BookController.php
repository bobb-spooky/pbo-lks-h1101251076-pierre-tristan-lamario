<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\BookService;

class BookController
{
    public function __construct(
        private BookService $service
    ) {
    }

    public function index(Request $request): void
    {
        try {
            $books = $this->service->list();

            Response::success($books, 'Daftar buku');
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function show(Request $request, string $id): void
    {
        try {
            $book = $this->service->detail((int) $id);

            Response::success($book, 'Detail buku');
        } catch (\Throwable $e) {
            Response::error(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    public function store(Request $request): void
    {
        try {
            $book = $this->service->create($request->getBody());

            Response::success(
                $book,
                'Buku berhasil dibuat',
                201
            );
        } catch (\Throwable $e) {
            Response::error(
                $e->getMessage(),
                $e->getCode() ?: 422
            );
        }
    }

    public function update(Request $request, string $id): void
    {
        try {
            $book = $this->service->update(
                (int) $id,
                $request->getBody()
            );

            Response::success(
                $book,
                'Buku berhasil diperbarui'
            );
        } catch (\Throwable $e) {
            Response::error(
                $e->getMessage(),
                $e->getCode() ?: 422
            );
        }
    }

    public function destroy(Request $request, string $id): void
    {
        try {
            $this->service->delete((int) $id);

            Response::success(
                null,
                'Buku berhasil dihapus'
            );
        } catch (\Throwable $e) {
            Response::error(
                $e->getMessage(),
                $e->getCode() ?: 404
            );
        }
    }
}
