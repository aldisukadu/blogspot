<?php
$pageTitle = htmlspecialchars($post['title'] ?? 'Artikel');
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Post Hero -->
<section class="post-hero py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb breadcrumb-post">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <?php if ($post['category_name']): ?>
                        <li class="breadcrumb-item">
                            <a href="index.php?page=categories&slug=<?= $post['category_slug'] ?>">
                                <?= htmlspecialchars($post['category_name']) ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(truncate($post['title'], 40)) ?></li>
                    </ol>
                </nav>

                <!-- Category Badge -->
                <?php if ($post['category_name']): ?>
                <a class="cat-badge cat-badge--lg mb-3 d-inline-block"
                   style="--cat-color:<?= htmlspecialchars($post['category_color']) ?>"
                   href="index.php?page=categories&slug=<?= $post['category_slug'] ?>">
                    <?= htmlspecialchars($post['category_name']) ?>
                </a>
                <?php endif; ?>

                <!-- Post Title -->
                <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>

                <!-- Post Excerpt -->
                <?php if ($post['excerpt']): ?>
                <p class="post-subtitle"><?= htmlspecialchars($post['excerpt']) ?></p>
                <?php endif; ?>

                <!-- Post Meta -->
                <div class="post-meta d-flex flex-wrap align-items-center gap-4 mt-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="author-avatar">
                            <?= mb_substr($post['author_name'], 0, 1) ?>
                        </div>
                        <div>
                            <div class="fw-semibold small"><?= htmlspecialchars($post['author_name']) ?></div>
                            <div class="text-muted" style="font-size:.75rem"><?= formatDate($post['created_at']) ?></div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 text-muted small ms-auto">
                        <span><i class="bi bi-clock me-1"></i><?= readTime($post['content']) ?></span>
                        <span><i class="bi bi-eye me-1"></i><?= formatNumber($post['views']) ?> tayangan</span>
                    </div>
                </div>

                <!-- Divider -->
                <div class="post-divider my-4"></div>
            </div>
        </div>
    </div>
</section>

<!-- Post Content -->
<section class="post-content-section pb-5">
    <div class="container">
        <div class="row justify-content-center g-5">
            <!-- Article Body -->
            <div class="col-lg-8">
                <!-- Share Sidebar (sticky) -->
                <div class="share-sidebar d-none d-xl-flex">
                    <span class="share-label">Share</span>
                    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                       target="_blank" class="share-btn" title="Share di Twitter">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                       target="_blank" class="share-btn" title="Share di Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <button class="share-btn" onclick="copyLink()" title="Salin link">
                        <i class="bi bi-link-45deg" id="copyIcon"></i>
                    </button>
                </div>

                <!-- Article Content -->
                <article class="article-body" id="articleBody">
                    <?= $post['content'] /* Content is stored as HTML from editor */ ?>
                </article>

                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                <div class="post-tags mt-4">
                    <span class="text-muted small me-2">Tags:</span>
                    <?php foreach ($tags as $tag): ?>
                    <span class="tag-chip"># <?= htmlspecialchars($tag['name']) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Share Row (mobile) -->
                <div class="share-row d-flex d-xl-none align-items-center gap-2 mt-4 p-3 bg-light rounded-3">
                    <span class="small fw-semibold me-2">Bagikan:</span>
                    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>"
                       target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                       target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-facebook"></i></a>
                    <button onclick="copyLink()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-link-45deg"></i> Salin</button>
                </div>

                <!-- Author Box -->
                <div class="author-box mt-5">
                    <div class="author-avatar author-avatar--lg">
                        <?= mb_substr($post['author_name'], 0, 1) ?>
                    </div>
                    <div class="author-box__info">
                        <p class="small text-muted mb-0">Penulis</p>
                        <h5 class="author-box__name"><?= htmlspecialchars($post['author_name']) ?></h5>
                        <?php if ($post['author_bio']): ?>
                        <p class="small text-muted mb-0"><?= htmlspecialchars($post['author_bio']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Post Actions (admin) -->
                <div class="post-actions d-flex gap-2 mt-4">
                    <a href="index.php?page=posts&action=edit&id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="index.php?page=posts&action=delete&id=<?= $post['id'] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Hapus artikel ini?')">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </a>
                    <a href="index.php" class="btn btn-sm btn-outline-secondary ms-auto">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <!-- Related Posts -->
                <?php if (!empty($related)): ?>
                <div class="related-posts mt-5">
                    <h4 class="related-title">Artikel Terkait</h4>
                    <div class="row g-3 mt-1">
                        <?php foreach (array_slice($related, 0, 2) as $rel):
                              if ($rel['id'] === $post['id']) continue; ?>
                        <div class="col-sm-6">
                            <div class="related-card">
                                <?php if ($rel['category_name']): ?>
                                <span class="cat-badge" style="--cat-color:<?= htmlspecialchars($rel['category_color']) ?>">
                                    <?= htmlspecialchars($rel['category_name']) ?>
                                </span>
                                <?php endif; ?>
                                <h6 class="related-card__title mt-2">
                                    <a href="index.php?page=posts&action=show&slug=<?= $rel['slug'] ?>">
                                        <?= htmlspecialchars(truncate($rel['title'], 60)) ?>
                                    </a>
                                </h6>
                                <div class="small text-muted"><?= formatDate($rel['created_at']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const icon = document.getElementById('copyIcon');
        if (icon) { icon.className = 'bi bi-check-lg'; setTimeout(() => icon.className = 'bi bi-link-45deg', 2000); }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
