-- Setup database dan tabel untuk blog

CREATE DATABASE IF NOT EXISTS blogspot_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE blogspot_db;

-- Tabel kategori artikel
CREATE TABLE IF NOT EXISTS categories (
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

-- Tabel artikel
CREATE TABLE IF NOT EXISTS posts (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    title       VARCHAR(200) NOT NULL,
    content     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Data contoh
INSERT INTO categories (name, slug) VALUES
    ('Teknologi', 'teknologi'),
    ('Lifestyle', 'lifestyle'),
    ('Tutorial', 'tutorial');

INSERT INTO posts (category_id, title, content) VALUES
    (3, 'Selamat Datang di Blog Saya', 
     'Halo! Ini adalah artikel pertama di blog ini.\n\nBlog ini dibuat menggunakan PHP Native dan Bootstrap 5 sebagai proyek belajar.\n\nSemoga bermanfaat!');
