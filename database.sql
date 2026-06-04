-- =============================================
-- Blog Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_db;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    role ENUM('admin','author','reader') DEFAULT 'reader',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table: categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    color VARCHAR(7) DEFAULT '#6c757d',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table: posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE,
    excerpt TEXT DEFAULT NULL,
    content LONGTEXT NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    status ENUM('draft','published','archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Table: comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending','approved','spam') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: tags
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Table: post_tags (pivot)
CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Seed Data
-- =============================================

INSERT INTO users (name, email, password, bio, role) VALUES
('Admin Blog', 'admin@blog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Penulis dan pengelola blog ini.', 'admin'),
('Budi Santoso', 'budi@blog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Penulis konten teknologi.', 'author');

INSERT INTO categories (name, slug, description, color) VALUES
('Teknologi', 'teknologi', 'Artikel seputar dunia teknologi terkini', '#0d6efd'),
('Lifestyle', 'lifestyle', 'Tips dan gaya hidup modern', '#198754'),
('Tutorial', 'tutorial', 'Panduan dan how-to artikel', '#fd7e14'),
('Review', 'review', 'Ulasan produk dan layanan', '#6f42c1'),
('Bisnis', 'bisnis', 'Dunia bisnis dan entrepreneur', '#dc3545');

INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, status, views) VALUES
(1, 1, 'Mengenal Kecerdasan Buatan di Era Modern', 'mengenal-kecerdasan-buatan-era-modern',
 'AI telah mengubah cara kita bekerja dan berinteraksi. Artikel ini membahas perkembangan AI terkini.',
 '<p>Kecerdasan Buatan atau Artificial Intelligence (AI) kini bukan lagi sekadar fiksi ilmiah. Teknologi ini telah merasuk ke hampir setiap aspek kehidupan kita — dari asisten virtual di smartphone hingga sistem rekomendasi di platform streaming favorit.</p><h3>Apa itu Kecerdasan Buatan?</h3><p>AI adalah cabang ilmu komputer yang berfokus pada pembuatan sistem mampu melakukan tugas yang biasanya memerlukan kecerdasan manusia. Ini mencakup pembelajaran mesin (machine learning), pemrosesan bahasa alami (NLP), hingga penglihatan komputer (computer vision).</p><p>Dalam beberapa tahun terakhir, perkembangan model bahasa besar seperti GPT dan LLaMA telah mendorong adopsi AI secara masif di berbagai industri.</p><h3>Dampak AI pada Dunia Kerja</h3><p>Banyak pekerjaan repetitif mulai diotomasi. Namun, ini juga membuka peluang baru — profesi seperti AI trainer, prompt engineer, dan data scientist semakin diminati. Yang terpenting adalah adaptasi dan peningkatan keterampilan secara berkelanjutan.</p>',
 'published', 1247),
(2, 3, 'Panduan Lengkap Belajar PHP untuk Pemula', 'panduan-lengkap-belajar-php-pemula',
 'PHP masih menjadi salah satu bahasa pemrograman web paling populer. Pelajari cara memulainya dari nol.',
 '<p>PHP (Hypertext Preprocessor) adalah bahasa skrip server-side yang digunakan untuk pengembangan web. Meskipun sudah berusia lebih dari dua dekade, PHP tetap relevan dan banyak digunakan di berbagai platform besar seperti WordPress, Laravel, dan lainnya.</p><h3>Kenapa Belajar PHP?</h3><p>PHP memiliki ekosistem yang matang, dokumentasi lengkap, dan komunitas yang besar. Bagi pemula, PHP relatif mudah dipelajari karena sintaksnya yang sederhana dan mudah dipahami.</p><h3>Langkah Pertama</h3><p>Mulailah dengan menginstal XAMPP atau Laragon di komputer Anda. Kemudian pelajari dasar-dasar sintaks PHP, variabel, kondisi, perulangan, dan fungsi. Setelah itu, pelajari cara berinteraksi dengan database MySQL.</p><p>Konsistensi adalah kunci. Luangkan waktu minimal 1-2 jam setiap hari untuk berlatih coding.</p>',
 'published', 856),
(1, 2, 'Tips Menjaga Keseimbangan Hidup di Era Digital', 'tips-menjaga-keseimbangan-hidup-era-digital',
 'Di tengah derasnya arus informasi digital, menjaga keseimbangan hidup menjadi tantangan tersendiri.',
 '<p>Kita hidup di era di mana notifikasi terus berdatangan, email tidak pernah berhenti, dan media sosial selalu menawarkan konten baru. Ini bisa membuat kita merasa kewalahan jika tidak dikelola dengan bijak.</p><h3>Digital Detox</h3><p>Sesekali lakukan digital detox — matikan semua perangkat dan nikmati momen tanpa layar. Aktivitas seperti membaca buku fisik, berjalan di alam terbuka, atau berkumpul dengan keluarga tanpa gadget sangat membantu.</p><h3>Batasi Screen Time</h3><p>Gunakan fitur screen time di smartphone Anda untuk memantau dan membatasi penggunaan aplikasi tertentu. Tetapkan "phone-free zone" seperti meja makan atau kamar tidur.</p>',
 'published', 632),
(2, 4, 'Review: MacBook Pro M3 Setelah 6 Bulan Pemakaian', 'review-macbook-pro-m3-setelah-6-bulan',
 'Apakah MacBook Pro M3 worth it? Berikut pengalaman nyata setelah 6 bulan penggunaan intensif.',
 '<p>Enam bulan lalu saya memutuskan beralih dari laptop Windows ke MacBook Pro M3. Ini adalah pengalaman dan penilaian jujur saya setelah penggunaan intensif sehari-hari.</p><h3>Performa</h3><p>Chip M3 benar-benar mengagumkan. Kompilasi kode yang biasanya memakan waktu 3-4 menit kini selesai dalam hitungan detik. Multitasking dengan banyak tab browser, IDE, dan aplikasi desain berjalan mulus tanpa lag.</p><h3>Baterai</h3><p>Inilah keunggulan terbesar. Dengan penggunaan normal (coding, browsing, video call), baterai bisa bertahan 12-14 jam. Ini benar-benar mengubah cara kerja saya — tidak perlu lagi membawa charger ke mana-mana.</p>',
 'published', 2103);

INSERT INTO tags (name, slug) VALUES
('PHP', 'php'), ('JavaScript', 'javascript'), ('AI', 'ai'), ('Tutorial', 'tutorial'),
('Review', 'review'), ('Lifestyle', 'lifestyle'), ('MacBook', 'macbook'), ('Pemula', 'pemula');

INSERT INTO post_tags (post_id, tag_id) VALUES (1,3),(2,1),(2,4),(2,8),(3,6),(4,5),(4,7);
