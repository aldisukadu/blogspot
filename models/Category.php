<?php
// models/Category.php

require_once __DIR__ . '/../config/Database.php';

class Category {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(p.id) AS post_count
            FROM categories c
            LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getBySlug(string $slug): array|false {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, slug, description, color)
            VALUES (:name, :slug, :description, :color)
        ");
        $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':color'       => $data['color']       ?? '#6c757d',
        ]);
        return $this->db->lastInsertId() ?: false;
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE categories SET name = :name, slug = :slug, description = :description, color = :color
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':color'       => $data['color']       ?? '#6c757d',
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function slugExists(string $slug, int $excludeId = 0): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = :slug AND id != :id");
        $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
