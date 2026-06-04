<?php
// models/Post.php

require_once __DIR__ . '/../config/Database.php';

class Post {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(int $limit = 10, int $offset = 0, string $status = 'published'): array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                   u.name AS author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.status = :status
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll(string $status = 'published'): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM posts WHERE status = :status");
        $stmt->execute([':status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getBySlug(string $slug): array|false {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                   u.name AS author_name, u.bio AS author_bio, u.avatar AS author_avatar
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.slug = :slug AND p.status = 'published'
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, u.name AS author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByCategory(int $categoryId, int $limit = 10, int $offset = 0): array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                   u.name AS author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.category_id = :cat_id AND p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,       PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,      PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(string $query, int $limit = 10): array {
        $like = '%' . $query . '%';
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.color AS category_color, u.name AS author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.status = 'published' AND (p.title LIKE :q OR p.excerpt LIKE :q2 OR p.content LIKE :q3)
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':q',     $like);
        $stmt->bindValue(':q2',    $like);
        $stmt->bindValue(':q3',    $like);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeatured(int $limit = 3): array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.color AS category_color,
                   u.name AS author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.status = 'published'
            ORDER BY p.views DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, thumbnail, status)
            VALUES (:user_id, :category_id, :title, :slug, :excerpt, :content, :thumbnail, :status)
        ");
        $stmt->execute([
            ':user_id'     => $data['user_id']     ?? 1,
            ':category_id' => $data['category_id'] ?? null,
            ':title'       => $data['title'],
            ':slug'        => $data['slug'],
            ':excerpt'     => $data['excerpt']     ?? null,
            ':content'     => $data['content'],
            ':thumbnail'   => $data['thumbnail']   ?? null,
            ':status'      => $data['status']      ?? 'draft',
        ]);
        return $this->db->lastInsertId() ?: false;
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE posts SET
                category_id = :category_id,
                title       = :title,
                slug        = :slug,
                excerpt     = :excerpt,
                content     = :content,
                thumbnail   = :thumbnail,
                status      = :status,
                updated_at  = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'          => $id,
            ':category_id' => $data['category_id'] ?? null,
            ':title'       => $data['title'],
            ':slug'        => $data['slug'],
            ':excerpt'     => $data['excerpt']     ?? null,
            ':content'     => $data['content'],
            ':thumbnail'   => $data['thumbnail']   ?? null,
            ':status'      => $data['status']      ?? 'draft',
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function incrementViews(int $id): void {
        $this->db->prepare("UPDATE posts SET views = views + 1 WHERE id = :id")->execute([':id' => $id]);
    }

    public function getTags(int $postId): array {
        $stmt = $this->db->prepare("
            SELECT t.* FROM tags t
            JOIN post_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = :post_id
        ");
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll();
    }
}
