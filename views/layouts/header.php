<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blog modern dengan artikel berkualitas tentang teknologi, lifestyle, dan tutorial.">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>Narratio Blog</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<!-- Top Bar -->
<div class="topbar py-2 d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="small text-muted">
            <i class="bi bi-calendar3 me-1"></i>
            <?= date('l, d F Y') ?>
        </span>
        <div class="d-flex gap-3">
            <a href="#" class="topbar-link"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="topbar-link"><i class="bi bi-instagram"></i></a>
            <a href="#" class="topbar-link"><i class="bi bi-youtube"></i></a>
            <a href="#" class="topbar-link"><i class="bi bi-rss"></i></a>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">
            <span class="brand-icon"><i class="bi bi-pen-fill"></i></span>
            <span class="brand-text">Narratio</span>
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <!-- Nav links -->
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= isActivePage('home') ?>" href="index.php">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= isActivePage('categories') ?>" href="#" data-bs-toggle="dropdown">Kategori</a>
                    <ul class="dropdown-menu shadow-sm border-0">
                        <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="index.php?page=categories&slug=<?= $cat['slug'] ?>">
                                <span class="cat-dot" style="background:<?= htmlspecialchars($cat['color']) ?>"></span>
                                <?= htmlspecialchars($cat['name']) ?>
                                <span class="badge ms-auto bg-light text-dark"><?= $cat['post_count'] ?></span>
                            </a>
                        </li>
                        <?php endforeach; endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?page=categories">Semua Kategori</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=posts&action=create">Tulis</a>
                </li>
            </ul>

            <!-- Search -->
            <form class="d-flex align-items-center gap-2" action="index.php" method="GET">
                <input type="hidden" name="page" value="search">
                <div class="search-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" name="q" class="form-control search-input"
                           placeholder="Cari artikel..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Flash Message -->
<?php $flash = getFlash(); if ($flash): ?>
<div class="container mt-3">
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- Main Content Wrapper -->
<main class="main-content">
