<?php
// Halaman detail artikel
require_once 'config/database.php';

// Validasi ID dari URL
if (!isset($_GET['id'])) {
    header("Location: /blogspot/index.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data artikel
$stmt = $conn->prepare(
    "SELECT posts.*, categories.name AS category_name 
     FROM posts 
     LEFT JOIN categories ON posts.category_id = categories.id 
     WHERE posts.id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    header("Location: /blogspot/index.php");
    exit;
}

$pageTitle = $post['title'];

include 'layout/header.php';
?>

<div class="container">
    <div class="mb-3">
        <a href="/blogspot/index.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Beranda
        </a>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article>
                
                <?php if ($post['category_name']): ?>
                    <span class="badge bg-primary mb-3">
                        <?= htmlspecialchars($post['category_name']) ?>
                    </span>
                <?php endif; ?>
                
                <h1 class="fw-bold mb-3">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                
                <p class="text-muted mb-4">
                    <small>
                        📅 Diterbitkan pada <?= date('d F Y, H:i', strtotime($post['created_at'])) ?>
                    </small>
                </p>
                
                <hr>
                
                <div class="article-content">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </div>
                
                <hr class="mt-5">
                
                <div class="d-flex gap-2">
                    <a href="/blogspot/posts/edit.php?id=<?= $post['id'] ?>" 
                       class="btn btn-warning btn-sm">
                        ✏️ Edit Artikel
                    </a>
                </div>
                
            </article>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>