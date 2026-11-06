<?php

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use Exception;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $repository
    ) {
    }

    public function list(): array
    {
        return array_map(
            fn(Book $book) => $book->toArray(),
            $this->repository->findAll()
        );
    }

    public function detail(int $id): array
    {
        $book = $this->repository->findById($id);

        if ($book === null) {
            throw new Exception(
                "Buku dengan ID $id tidak ditemukan",
                404
            );
        }

        return $book->toArray();
    }

    public function create(array $data): array
    {
        if (empty(trim($data['judul'] ?? ''))) {
            throw new Exception("Judul wajib diisi", 422);
        }

        if (empty(trim($data['penulis'] ?? ''))) {
            throw new Exception("Penulis wajib diisi", 422);
        }

        $tahun = (int) ($data['tahun'] ?? 0);
        $stok = (int) ($data['stok'] ?? 0);

        if ($tahun <= 0) {
            throw new Exception("Tahun terbit tidak valid", 422);
        }

        if ($stok < 0) {
            throw new Exception("Stok tidak boleh negatif", 422);
        }

        $book = new Book(
            0,
            trim($data['judul']),
            trim($data['penulis']),
            $tahun,
            $stok
        );

        $id = $this->repository->save($book);

        return [
            'id' => $id,
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahunTerbit(),
            'stok' => $book->getStok()
        ];
    }

    public function update(int $id, array $data): array
    {
        $existingBook = $this->repository->findById($id);

        if ($existingBook === null) {
            throw new Exception(
                "Buku dengan ID $id tidak ditemukan",
                404
            );
        }

        if (empty(trim($data['judul'] ?? ''))) {
            throw new Exception("Judul wajib diisi", 422);
        }

        if (empty(trim($data['penulis'] ?? ''))) {
            throw new Exception("Penulis wajib diisi", 422);
        }

        $tahun = (int) ($data['tahun'] ?? 0);
        $stok = (int) ($data['stok'] ?? 0);

        if ($tahun <= 0) {
            throw new Exception("Tahun terbit tidak valid", 422);
        }

        if ($stok < 0) {
            throw new Exception("Stok tidak boleh negatif", 422);
        }

        $book = new Book(
            $id,
            trim($data['judul']),
            trim($data['penulis']),
            $tahun,
            $stok
        );

        $this->repository->update($book);

        return $book->toArray();
    }

    public function delete(int $id): void
    {
        $book = $this->repository->findById($id);

        if ($book === null) {
            throw new Exception(
                "Buku dengan ID $id tidak ditemukan",
                404
            );
        }

        $this->repository->delete($id);
    }
}
