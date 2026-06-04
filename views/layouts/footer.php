</main><!-- /main-content -->

<!-- Footer -->
<footer class="site-footer mt-auto">
    <div class="footer-top py-5">
        <div class="container">
            <div class="row g-4">
                <!-- About -->
                <div class="col-lg-4">
                    <a class="navbar-brand mb-3 d-inline-block" href="index.php">
                        <span class="brand-icon"><i class="bi bi-pen-fill"></i></span>
                        <span class="brand-text">Narratio</span>
                    </a>
                    <p class="footer-desc">Platform blog modern untuk berbagi cerita, pengetahuan, dan inspirasi. Kami percaya setiap kata memiliki kekuatan untuk mengubah dunia.</p>
                    <div class="social-links d-flex gap-2 mt-3">
                        <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-6">
                    <h6 class="footer-heading">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="index.php?page=categories">Kategori</a></li>
                        <li><a href="index.php?page=posts&action=create">Tulis Artikel</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-6">
                    <h6 class="footer-heading">Kategori</h6>
                    <ul class="footer-links">
                        <?php if (!empty($categories)): foreach (array_slice($categories, 0, 5) as $cat): ?>
                        <li>
                            <a href="index.php?page=categories&slug=<?= $cat['slug'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-4">
                    <h6 class="footer-heading">Newsletter</h6>
                    <p class="small text-muted mb-3">Dapatkan artikel terbaru langsung di inbox Anda setiap minggu.</p>
                    <div class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="email@anda.com">
                            <button class="btn btn-primary px-3" type="button" id="subscribeBtn">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                    <p class="small text-muted mt-2"><i class="bi bi-shield-check me-1"></i> Kami tidak pernah spam.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom py-3">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small text-muted">
                &copy; <?= date('Y') ?> Narratio Blog. Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> menggunakan PHP & Bootstrap.
            </p>
            <div class="d-flex gap-3">
                <a href="#" class="footer-bottom-link">Privasi</a>
                <a href="#" class="footer-bottom-link">Syarat</a>
                <a href="#" class="footer-bottom-link">Kontak</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="public/js/script.js"></script>
</body>
</html>
