<?php

class Categorie {

    private ?int $id;
    private string $nom;

    public function __construct(string $nom) {
        $this->id = null;
        $this->nom = $nom;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public static function findAll(PDO $db): array
    {
        $stmt = $db->query('SELECT * FROM categories ORDER BY nom ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(PDO $db, int $id): ?array
    {
        $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function save(PDO $db, string $nom): bool
    {
        $stmt = $db->prepare('INSERT INTO categories (nom) VALUES (?)');
        return $stmt->execute([$nom]);
    }

    public static function update(PDO $db, int $id, string $nom): bool
    {
        $stmt = $db->prepare('UPDATE categories SET nom = ? WHERE id = ?');
        return $stmt->execute([$nom, $id]);
    }

    public static function getProjectCount(PDO $db, int $id): int
    {
        $stmt = $db->prepare('SELECT COUNT(*) FROM projets_upcycling WHERE id_categorie = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public static function delete(PDO $db, int $id): bool
    {
        $deleteStmt = $db->prepare('DELETE FROM categories WHERE id = ?');
        return $deleteStmt->execute([$id]);
    }
}