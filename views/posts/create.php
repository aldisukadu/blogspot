<?php
$pageTitle = 'Tulis Artikel Baru';
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="form-page-header mb-4">
                    <a href="index.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                    <h1 class="form-page-title mt-2">Tulis Artikel Baru</h1>
                    <p class="text-muted">Bagikan cerita dan pengetahuan Anda kepada pembaca.</p>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Ada yang perlu diperbaiki:</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="index.php?page=posts&action=store" method="POST" class="post-form" id="postForm">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

                    <!-- Title -->
                    <div class="mb-4">
                        <input type="text" name="title" id="postTitle"
                               class="form-control form-control-title"
                               placeholder="Judul artikel yang menarik..."
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                               required>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="form-text">Judul yang baik ringkas dan deskriptif.</div>
                            <small class="text-muted slug-preview" id="slugPreview"></small>
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Ringkasan <span class="text-muted">(opsional)</span></label>
                        <textarea name="excerpt" class="form-control" rows="2"
                                  placeholder="Deskripsi singkat yang muncul di halaman daftar artikel..."
                                  maxlength="300"><?= htmlspecialchars($_POST['excerpt'] ?? '') ?></textarea>
                        <div class="form-text">Maks. 300 karakter. Jika kosong, otomatis diambil dari konten.</div>
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Konten <span class="text-danger">*</span></label>
                        <div class="editor-toolbar">
                            <button type="button" class="tb-btn" onclick="execCmd('bold')" title="Bold"><i class="bi bi-type-bold"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('italic')" title="Italic"><i class="bi bi-type-italic"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('underline')" title="Underline"><i class="bi bi-type-underline"></i></button>
                            <div class="tb-divider"></div>
                            <button type="button" class="tb-btn" onclick="execCmd('insertUnorderedList')" title="List"><i class="bi bi-list-ul"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('insertOrderedList')" title="Ordered"><i class="bi bi-list-ol"></i></button>
                            <div class="tb-divider"></div>
                            <button type="button" class="tb-btn" onclick="insertHeading()" title="Heading"><i class="bi bi-type-h2"></i></button>
                            <button type="button" class="tb-btn" onclick="insertLink()" title="Link"><i class="bi bi-link-45deg"></i></button>
                            <button type="button" class="tb-btn" onclick="execCmd('removeFormat')" title="Clear format"><i class="bi bi-eraser"></i></button>
                        </div>
                        <div id="editor" class="rich-editor" contenteditable="true"
                             data-placeholder="Mulai menulis artikel Anda di sini..."><?= $_POST['content'] ?? '' ?></div>
                        <textarea name="content" id="hiddenContent" class="d-none"></textarea>
                    </div>

                    <!-- Meta Row -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-medium">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">— Pilih Kategori —</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-medium">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" <?= ($_POST['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= ($_POST['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publikasikan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="index.php" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" name="save_draft" value="1" class="btn btn-outline-primary px-4" onclick="document.querySelector('[name=status]').value='draft'">
                            <i class="bi bi-save me-1"></i>Simpan Draft
                        </button>
                        <button type="submit" class="btn btn-primary px-5" onclick="document.querySelector('[name=status]').value='published'">
                            <i class="bi bi-send me-1"></i>Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Sync rich editor to hidden textarea on submit
document.getElementById('postForm').addEventListener('submit', function() {
    document.getElementById('hiddenContent').value = document.getElementById('editor').innerHTML;
});

// Simple rich editor commands
function execCmd(cmd) {
    document.getElementById('editor').focus();
    document.execCommand(cmd, false, null);
}
function insertHeading() {
    document.getElementById('editor').focus();
    document.execCommand('formatBlock', false, 'h3');
}
function insertLink() {
    const url = prompt('Masukkan URL:');
    if (url) { document.getElementById('editor').focus(); document.execCommand('createLink', false, url); }
}

// Slug preview
document.getElementById('postTitle').addEventListener('input', function() {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim('-');
    document.getElementById('slugPreview').textContent = slug ? 'Slug: ' + slug : '';
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
