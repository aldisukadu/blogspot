<?php
$pageTitle = 'Edit: ' . ($post['title'] ?? 'Artikel');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-page-header mb-4">
                    <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>" class="back-link">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Artikel
                    </a>
                    <h1 class="form-page-title mt-2">Edit Artikel</h1>
                    <p class="text-muted">Perbarui konten dan pengaturan artikel Anda.</p>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="index.php?page=posts&action=update&id=<?= $post['id'] ?>" method="POST" class="post-form" id="postForm">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

                    <div class="mb-4">
                        <input type="text" name="title" class="form-control form-control-title"
                               placeholder="Judul artikel..."
                               value="<?= htmlspecialchars($post['title']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Ringkasan</label>
                        <textarea name="excerpt" class="form-control" rows="2" maxlength="300"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Konten <span class="text-danger">*</span></label>
                        <div class="editor-toolbar">
                            <button type="button" class="tb-btn" onclick="execCmd('bold')"><i class="bi bi-type-bold"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('italic')"><i class="bi bi-type-italic"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('underline')"><i class="bi bi-type-underline"></i></button>
                            <div class="tb-divider"></div>
                            <button type="button" class="tb-btn" onclick="execCmd('insertUnorderedList')"><i class="bi bi-list-ul"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('insertOrderedList')"><i class="bi bi-list-ol"></i></button>
                            <div class="tb-divider"></div>
                            <button type="button" class="tb-btn" onclick="insertHeading()"><i class="bi bi-type-h2"></i></button>
                            <button type="button" class="tb-btn" onclick="insertLink()"><i class="bi bi-link-45deg"></i></button>
                        </div>
                        <div id="editor" class="rich-editor" contenteditable="true"><?= $post['content'] ?></div>
                        <textarea name="content" id="hiddenContent" class="d-none"></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-medium">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">— Pilih Kategori —</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-medium">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft"      <?= $post['status'] === 'draft'      ? 'selected' : '' ?>>Draft</option>
                                <option value="published"  <?= $post['status'] === 'published'  ? 'selected' : '' ?>>Dipublikasikan</option>
                                <option value="archived"   <?= $post['status'] === 'archived'   ? 'selected' : '' ?>>Diarsipkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Post info -->
                    <div class="post-meta-info p-3 bg-light rounded-3 mb-4 small text-muted d-flex gap-4 flex-wrap">
                        <span><i class="bi bi-calendar me-1"></i>Dibuat: <?= formatDate($post['created_at']) ?></span>
                        <span><i class="bi bi-eye me-1"></i>Tayangan: <?= formatNumber($post['views']) ?></span>
                        <span><i class="bi bi-person me-1"></i>Penulis: <?= htmlspecialchars($post['author_name']) ?></span>
                    </div>

                    <div class="d-flex gap-3 justify-content-between">
                        <a href="index.php?page=posts&action=delete&id=<?= $post['id'] ?>"
                           class="btn btn-outline-danger"
                           onclick="return confirm('Yakin ingin menghapus artikel ini secara permanen?')">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </a>
                        <div class="d-flex gap-2">
                            <a href="index.php?page=posts&action=show&slug=<?= $post['slug'] ?>" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('postForm').addEventListener('submit', function() {
    document.getElementById('hiddenContent').value = document.getElementById('editor').innerHTML;
});
function execCmd(cmd) { document.getElementById('editor').focus(); document.execCommand(cmd, false, null); }
function insertHeading() { document.getElementById('editor').focus(); document.execCommand('formatBlock', false, 'h3'); }
function insertLink() {
    const url = prompt('URL:');
    if (url) { document.getElementById('editor').focus(); document.execCommand('createLink', false, url); }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
