<?php
$pageTitle = 'Tambah Kategori';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="form-page-header mb-4">
                    <a href="index.php?page=categories" class="back-link"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                    <h1 class="form-page-title mt-2">Tambah Kategori Baru</h1>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e): ?><p class="mb-0"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form action="index.php?page=categories&action=store" method="POST" class="post-form">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               placeholder="Contoh: Teknologi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Deskripsi singkat tentang kategori ini..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Warna Label</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color" class="form-control form-control-color"
                                   value="<?= $_POST['color'] ?? '#0d6efd' ?>" style="width:60px;height:40px">
                            <div class="color-presets d-flex gap-2 flex-wrap">
                                <?php foreach (['#0d6efd','#198754','#dc3545','#fd7e14','#6f42c1','#0dcaf0','#6c757d','#d63384'] as $c): ?>
                                <button type="button" class="color-preset-btn"
                                        style="background:<?= $c ?>"
                                        onclick="document.querySelector('[name=color]').value='<?= $c ?>'"></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="index.php?page=categories" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-plus-lg me-1"></i>Buat Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
