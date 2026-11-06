<?php

namespace App\Models;

class Book
{
    private int $id;
    private string $judul;
    private string $penulis;
    private int $tahunTerbit;
    private int $stok;

    public function __construct(
        int $id,
        string $judul,
        string $penulis,
        int $tahunTerbit,
        int $stok
    ) {
        $this->id = $id;
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tahunTerbit = $tahunTerbit;
        $this->stok = $stok;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getJudul(): string
    {
        return $this->judul;
    }

    public function getPenulis(): string
    {
        return $this->penulis;
    }

    public function getTahunTerbit(): int
    {
        return $this->tahunTerbit;
    }

    public function getStok(): int
    {
        return $this->stok;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'tahun_terbit' => $this->tahunTerbit,
            'stok' => $this->stok
        ];
    }
}