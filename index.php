<?php
// Halaman depan - tampilan artikel untuk pembaca
require_once 'config/database.php';

$pageTitle = "Beranda";

$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, 
               categories.name AS category_name 
        FROM posts 
        LEFT JOIN categories ON posts.category_id = categories.id 
        ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $sql);

include 'layout/header.php';
?>

<div class="container">
    
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Artikel Terbaru</h2>
            <hr>
        </div>
    </div>
    
    <div class="row row-cols-1 row-cols-md-3 g-4">
        
        <?php
        if (mysqli_num_rows($result) > 0):
            while ($post = mysqli_fetch_assoc($result)):
        ?>
        
        <!-- Satu kartu untuk satu artikel -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    
                    <?php if ($post['category_name']): ?>
                        <span class="badge bg-primary badge-kategori mb-2">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary badge-kategori mb-2">Umum</span>
                    <?php endif; ?>
                    
                    <h5 class="card-title">
                        <?= htmlspecialchars($post['title']) ?>
                    </h5>
                    
                    <p class="card-text text-muted">
                        <?= htmlspecialchars(substr(strip_tags($post['content']), 0, 100)) ?>...
                    </p>
                </div>
                
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <?= date('d M Y', strtotime($post['created_at'])) ?>
                    </small>
                    
                    <a href="/blogspot/post.php?id=<?= $post['id'] ?>" 
                       class="btn btn-sm btn-outline-primary">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
        </div>
        
        <?php
            endwhile;
        else:
        ?>
        
        <!-- Pesan jika belum ada artikel -->
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h5>Belum ada artikel.</h5>
                <a href="/blogspot/posts/create.php" class="btn btn-primary mt-2">
                    Tulis Artikel Pertama
                </a>
            </div>
        </div>
        
        <?php endif; ?>
        
    </div>
</div>

<?php
include 'layout/footer.php';
?>
