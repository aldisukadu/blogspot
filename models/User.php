<?php
// models/User.php

require_once __DIR__ . '/../config/Database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT id, name, email, bio, avatar, role, created_at FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, bio, role)
            VALUES (:name, :email, :password, :bio, :role)
        ");
        $stmt->execute([
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':bio'      => $data['bio']  ?? null,
            ':role'     => $data['role'] ?? 'reader',
        ]);
        return $this->db->lastInsertId() ?: false;
    }

    public function verifyPassword(string $email, string $password): array|false {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }
}
