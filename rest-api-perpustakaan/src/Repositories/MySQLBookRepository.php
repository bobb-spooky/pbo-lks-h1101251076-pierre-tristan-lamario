<?php

namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM books ORDER BY id DESC"
        );

        return array_map(
            fn(array $row): Book => $this->toModel($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM books WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->toModel($row) : null;
    }

    public function save(Book $book): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO books (judul, penulis, tahun, stok)
             VALUES (:judul, :penulis, :tahun, :stok)"
        );

        $stmt->execute([
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahunTerbit(),
            'stok' => $book->getStok()
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Book $book): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE books
             SET judul = :judul,
                 penulis = :penulis,
                 tahun = :tahun,
                 stok = :stok
             WHERE id = :id"
        );

        return $stmt->execute([
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahunTerbit(),
            'stok' => $book->getStok(),
            'id' => $book->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM books WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }

    private function toModel(array $row): Book
    {
        return new Book(
            (int) $row['id'],
            $row['judul'],
            $row['penulis'],
            (int) $row['tahun'],
            (int) $row['stok']
        );
    }
}
