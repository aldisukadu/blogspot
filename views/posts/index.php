<?php
$pageTitle = isset($query) ? 'Pencarian: ' . htmlspecialchars($query) : 'Beranda';
require_once __DIR__ . '/../layouts/header.php';
?>

<?php if (!isset($query)): /* Homepage hero — show only if not search */ ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-lg-6">
                <div class="hero-tag mb-3"><i class="bi bi-stars me-1"></i>Blog Terkurasi</div>
                <h1 class="hero-title">Cerita yang<br><em>Menginspirasi</em></h1>
                <p class="hero-subtitle">Temukan artikel berkualitas tentang teknologi, lifestyle, tutorial, dan banyak lagi. Ditulis oleh para penulis berpengalaman untuk pembaca yang ingin terus berkembang.</p>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="#latest-posts" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-book me-2"></i>Baca Sekarang
                    </a>
                    <a href="index.php?page=posts&action=create" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="bi bi-pencil me-2"></i>Tulis Artikel
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-illustration">
                    <div class="hero-card-stack">
                        <?php foreach (array_slice($featured ?? [], 0, 3) as $i => $f): ?>
                        <div class="hero-mini-card" style="--card-index:<?= $i ?>">
                            <div class="mini-card-inner">
                                <span class="mini-cat-badge" style="background:<?= htmlspecialchars($f['category_color'] ?? '#333') ?>">
                                    <?= htmlspecialchars($f['category_name'] ?? 'Umum') ?>
                                </span>
                                <h6><?= htmlspecialchars(truncate($f['title'], 50)) ?></h6>
                                <small class="text-muted"><?= formatDate($f['created_at']) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-hint">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- Featured Posts -->
<?php if (!empty($featured)): ?>
<section class="featured-section py-5">
    <div class="container">
        <div class="section-header d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="section-label">🔥 Trending</span>
                <h2 class="section-title">Paling Populer</h2>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($featured as $i => $post): ?>
            <div class="col-lg-<?= $i === 0 ? '6' : '3' ?>">
                <article class="featured-card <?= $i === 0 ? 'featured-card--large' : '' ?> h-100">
                    <div class="featured-card__body">
                        <?php if ($post['category_name']): ?>
                        <a class="cat-badge mb-2" style="--cat-color:<?= htmlspecialchars($post['category_color']) ?>"
                           href="index.php?page=categories&slug=<?= $post['category_slug'] ?>">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </a>
                        <?php endif; ?>
                        <h3 class="featured-card__title <?= $i === 0 ? 'h4' : 'h6' ?>">
                            <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h3>
                        <?php if ($i === 0): ?>
                        <p class="featured-card__excerpt"><?= htmlspecialchars(truncate($post['excerpt'] ?? $post['content'], 120)) ?></p>
                        <?php endif; ?>
                        <div class="post-meta-sm">
                            <span><i class="bi bi-person-circle"></i> <?= htmlspecialchars($post['author_name']) ?></span>
                            <span><i class="bi bi-eye"></i> <?= formatNumber($post['views']) ?></span>
                            <span><i class="bi bi-clock"></i> <?= readTime($post['content']) ?></span>
                        </div>
                    </div>
                    <div class="featured-card__num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php endif; /* end !query */ ?>

<!-- Latest Posts -->
<section class="posts-section py-5" id="latest-posts">
    <div class="container">
        <div class="row g-5">
            <!-- Posts Grid -->
            <div class="col-lg-8">
                <?php if (isset($query)): ?>
                <div class="search-results-header mb-4">
                    <h2 class="h4">Hasil pencarian: "<em><?= htmlspecialchars($query) ?></em>"</h2>
                    <p class="text-muted"><?= count($posts) ?> artikel ditemukan</p>
                </div>
                <?php else: ?>
                <div class="section-header mb-4">
                    <span class="section-label">✍️ Terbaru</span>
                    <h2 class="section-title">Artikel Terbaru</h2>
                </div>
                <?php endif; ?>

                <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
                    <h4>Belum ada artikel</h4>
                    <p class="text-muted">Jadilah yang pertama menulis artikel di sini.</p>
                    <a href="index.php?page=posts&action=create" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-lg me-1"></i>Tulis Artikel
                    </a>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($posts as $post): ?>
                    <div class="col-md-6">
                        <article class="post-card h-100" data-aos="fade-up">
                            <div class="post-card__header">
                                <?php if ($post['category_name']): ?>
                                <a class="cat-badge" style="--cat-color:<?= htmlspecialchars($post['category_color']) ?>"
                                   href="index.php?page=categories&slug=<?= $post['category_slug'] ?>">
                                    <?= htmlspecialchars($post['category_name']) ?>
                                </a>
                                <?php endif; ?>
                                <span class="post-card__date"><?= formatDate($post['created_at']) ?></span>
                            </div>

                            <div class="post-card__body">
                                <h2 class="post-card__title h5">
                                    <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h2>
                                <p class="post-card__excerpt">
                                    <?= htmlspecialchars(truncate($post['excerpt'] ?? $post['content'], 110)) ?>
                                </p>
                            </div>

                            <div class="post-card__footer">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="author-avatar-sm">
                                        <?= mb_substr($post['author_name'], 0, 1) ?>
                                    </div>
                                    <span class="small fw-medium"><?= htmlspecialchars($post['author_name']) ?></span>
                                </div>
                                <div class="d-flex gap-3 small text-muted">
                                    <span><i class="bi bi-eye"></i> <?= formatNumber($post['views']) ?></span>
                                    <span><i class="bi bi-clock"></i> <?= readTime($post['content']) ?></span>
                                </div>
                            </div>

                            <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>" class="post-card__overlay-link" aria-label="Baca: <?= htmlspecialchars($post['title']) ?>"></a>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (!isset($query) && ($totalPages ?? 1) > 1): ?>
                <nav class="mt-5" aria-label="Navigasi halaman">
                    <ul class="pagination justify-content-center gap-1">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=home&p=<?= $page - 1 ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?p=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=home&p=<?= $page + 1 ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
                <?php endif; // empty posts ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="sidebar">
                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Cari Artikel</h5>
                        <form action="index.php" method="GET">
                            <input type="hidden" name="page" value="search">
                            <div class="input-group">
                                <input type="search" name="q" class="form-control" placeholder="Kata kunci..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Kategori</h5>
                        <ul class="category-list">
                            <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                            <li class="category-list__item">
                                <a href="index.php?page=categories&slug=<?= $cat['slug'] ?>" class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="cat-dot" style="background:<?= htmlspecialchars($cat['color']) ?>"></span>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </span>
                                    <span class="badge bg-light text-dark"><?= $cat['post_count'] ?></span>
                                </a>
                            </li>
                            <?php endforeach; endif; ?>
                        </ul>
                    </div>

                    <!-- Write CTA Widget -->
                    <div class="sidebar-widget sidebar-cta">
                        <div class="text-center p-4">
                            <div class="cta-icon"><i class="bi bi-pencil-square"></i></div>
                            <h5 class="mt-3">Punya Cerita?</h5>
                            <p class="small text-muted">Bagikan pengetahuan dan pengalaman Anda kepada ribuan pembaca.</p>
                            <a href="index.php?page=posts&action=create" class="btn btn-primary w-100 mt-2">
                                Mulai Menulis
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
