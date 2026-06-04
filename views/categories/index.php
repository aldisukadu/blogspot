<?php
$pageTitle = isset($category) ? 'Kategori: ' . $category['name'] : 'Semua Kategori';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="py-5">
    <div class="container">

        <?php if (isset($category)): ?>
        <!-- Category filter view -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center">
                <span class="cat-badge cat-badge--lg mb-3 d-inline-block"
                      style="--cat-color:<?= htmlspecialchars($category['color']) ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </span>
                <h1 class="display-6 fw-bold"><?= htmlspecialchars($category['name']) ?></h1>
                <?php if ($category['description']): ?>
                <p class="text-muted mt-2"><?= htmlspecialchars($category['description']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($posts)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
            <h4>Belum ada artikel di kategori ini</h4>
            <a href="index.php?page=posts&action=create" class="btn btn-primary mt-3">Tulis Artikel Pertama</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4">
                <article class="post-card h-100">
                    <div class="post-card__header">
                        <span class="post-card__date"><?= formatDate($post['created_at']) ?></span>
                    </div>
                    <div class="post-card__body">
                        <h2 class="post-card__title h5">
                            <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <p class="post-card__excerpt"><?= htmlspecialchars(truncate($post['excerpt'] ?? $post['content'], 100)) ?></p>
                    </div>
                    <div class="post-card__footer">
                        <span class="small text-muted"><?= htmlspecialchars($post['author_name']) ?></span>
                        <span class="small text-muted"><i class="bi bi-clock"></i> <?= readTime($post['content']) ?></span>
                    </div>
                    <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>" class="post-card__overlay-link"></a>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- All categories view -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <span class="section-label">📂 Topik</span>
                <h1 class="section-title display-6 fw-bold mt-1">Semua Kategori</h1>
                <p class="text-muted">Jelajahi artikel berdasarkan topik yang Anda minati.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($categories as $cat): ?>
            <div class="col-sm-6 col-lg-4">
                <a href="index.php?page=categories&slug=<?= $cat['slug'] ?>" class="category-card text-decoration-none">
                    <div class="category-card__accent" style="background:<?= htmlspecialchars($cat['color']) ?>"></div>
                    <div class="category-card__body">
                        <h4 class="category-card__name"><?= htmlspecialchars($cat['name']) ?></h4>
                        <?php if ($cat['description']): ?>
                        <p class="category-card__desc"><?= htmlspecialchars(truncate($cat['description'], 80)) ?></p>
                        <?php endif; ?>
                        <span class="category-card__count"><?= $cat['post_count'] ?> artikel</span>
                    </div>
                    <div class="category-card__arrow"><i class="bi bi-arrow-right"></i></div>
                </a>
            </div>
            <?php endforeach; ?>

            <!-- Add new category card -->
            <div class="col-sm-6 col-lg-4">
                <a href="index.php?page=categories&action=create" class="category-card category-card--add text-decoration-none">
                    <div class="text-center w-100">
                        <i class="bi bi-plus-circle display-6 text-muted"></i>
                        <p class="mt-2 mb-0 text-muted">Tambah Kategori</p>
                    </div>
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
