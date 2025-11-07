<?php

class User {

    public static function findAll(PDO $db): array
    {
        $stmt = $db->query('SELECT * FROM users ORDER BY id ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(PDO $db, int $id): ?array
    {
        $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function update(PDO $db, int $id, string $username, string $email, string $role): bool
    {
        $sql = 'UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?';
        $stmt = $db->prepare($sql);
        return $stmt->execute([$username, $email, $role, $id]);
    }

    public static function delete(PDO $db, int $id): bool
    {
        $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    public static function findTopContributors(PDO $db, int $limit = 5): array
    {
        $stmt = $db->query(
            'SELECT COALESCE(u.username, u.full_name) AS username, COUNT(p.id) AS project_count
             FROM users u
             LEFT JOIN projets_upcycling p ON u.id = p.id_auteur
             GROUP BY u.id
             ORDER BY project_count DESC
             LIMIT ' . $limit
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}