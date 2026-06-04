<?php
// controllers/CategoryController.php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../helpers/functions.php';

class CategoryController {
    private Category $categoryModel;
    private Post     $postModel;

    public function __construct() {
        $this->categoryModel = new Category();
        $this->postModel     = new Post();
    }

    public function index(): void {
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/categories/index.php';
    }

    public function show(string $slug): void {
        $category = $this->categoryModel->getBySlug($slug);
        if (!$category) { http_response_code(404); echo '404 Not Found'; return; }

        $posts      = $this->postModel->getByCategory($category['id']);
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/categories/index.php';
    }

    public function create(): void {
        $errors = [];
        require_once __DIR__ . '/../views/categories/create.php';
    }

    public function store(): void {
        $errors = [];
        $name   = cleanInput($_POST['name'] ?? '');
        if (empty($name)) $errors[] = 'Nama kategori wajib diisi.';

        $slug = generateSlug($name);
        if ($this->categoryModel->slugExists($slug)) $errors[] = 'Kategori dengan nama ini sudah ada.';

        if (empty($errors)) {
            $this->categoryModel->create([
                'name'        => $name,
                'slug'        => $slug,
                'description' => cleanInput($_POST['description'] ?? ''),
                'color'       => $_POST['color'] ?? '#6c757d',
            ]);
            setFlash('success', 'Kategori berhasil dibuat!');
            redirect('?page=categories');
            return;
        }
        require_once __DIR__ . '/../views/categories/create.php';
    }

    public function delete(int $id): void {
        $this->categoryModel->delete($id);
        setFlash('success', 'Kategori berhasil dihapus.');
        redirect('?page=categories');
    }
}
