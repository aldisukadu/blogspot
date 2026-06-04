<?php
// controllers/PostController.php

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/functions.php';

class PostController {
    private Post     $postModel;
    private Category $categoryModel;
    private int      $perPage = 6;

    public function __construct() {
        $this->postModel     = new Post();
        $this->categoryModel = new Category();
    }

    /** Homepage — list all published posts */
    public function index(): void {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $this->perPage;

        $posts      = $this->postModel->getAll($this->perPage, $offset);
        $totalPosts = $this->postModel->countAll();
        $totalPages = (int)ceil($totalPosts / $this->perPage);
        $featured   = $this->postModel->getFeatured(3);
        $categories = $this->categoryModel->getAll();

        require_once __DIR__ . '/../views/posts/index.php';
    }

    /** Single post detail */
    public function show(string $slug): void {
        $post = $this->postModel->getBySlug($slug);
        if (!$post) { $this->notFound(); return; }

        $this->postModel->incrementViews($post['id']);
        $tags       = $this->postModel->getTags($post['id']);
        $categories = $this->categoryModel->getAll();
        $related    = $this->postModel->getByCategory($post['category_id'] ?? 0, 3);

        require_once __DIR__ . '/../views/posts/show.php';
    }

    /** Create form (GET) */
    public function create(): void {
        $categories = $this->categoryModel->getAll();
        $errors     = [];
        require_once __DIR__ . '/../views/posts/create.php';
    }

    /** Store new post (POST) */
    public function store(): void {
        $errors = $this->validatePost($_POST);

        if (empty($errors)) {
            $slug = generateSlug($_POST['title']);
            $id   = $this->postModel->create([
                'user_id'     => 1, // TODO: use session user id
                'category_id' => $_POST['category_id'] ?: null,
                'title'       => cleanInput($_POST['title']),
                'slug'        => $slug,
                'excerpt'     => cleanInput($_POST['excerpt'] ?? ''),
                'content'     => $_POST['content'],
                'status'      => $_POST['status'] ?? 'draft',
            ]);
            if ($id) {
                setFlash('success', 'Post berhasil dibuat!');
                redirect('?page=posts&action=show&slug=' . $slug);
                return;
            }
            $errors[] = 'Gagal menyimpan post. Coba lagi.';
        }

        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/posts/create.php';
    }

    /** Edit form (GET) */
    public function edit(int $id): void {
        $post = $this->postModel->getById($id);
        if (!$post) { $this->notFound(); return; }
        $categories = $this->categoryModel->getAll();
        $errors     = [];
        require_once __DIR__ . '/../views/posts/edit.php';
    }

    /** Update existing post (POST) */
    public function update(int $id): void {
        $post   = $this->postModel->getById($id);
        $errors = $this->validatePost($_POST);

        if (empty($errors)) {
            $slug = generateSlug($_POST['title']);
            $this->postModel->update($id, [
                'category_id' => $_POST['category_id'] ?: null,
                'title'       => cleanInput($_POST['title']),
                'slug'        => $slug,
                'excerpt'     => cleanInput($_POST['excerpt'] ?? ''),
                'content'     => $_POST['content'],
                'status'      => $_POST['status'] ?? 'draft',
            ]);
            setFlash('success', 'Post berhasil diupdate!');
            redirect('?page=posts&action=show&slug=' . $slug);
            return;
        }

        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/posts/edit.php';
    }

    /** Delete post */
    public function delete(int $id): void {
        $this->postModel->delete($id);
        setFlash('success', 'Post berhasil dihapus.');
        redirect('?page=posts');
    }

    /** Search */
    public function search(): void {
        $query      = cleanInput($_GET['q'] ?? '');
        $posts      = $query ? $this->postModel->search($query) : [];
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/posts/index.php';
    }

    // -------------------------------------------------------------------
    private function validatePost(array $data): array {
        $errors = [];
        if (empty(trim($data['title'] ?? '')))   $errors[] = 'Judul wajib diisi.';
        if (empty(trim($data['content'] ?? ''))) $errors[] = 'Konten wajib diisi.';
        return $errors;
    }

    private function notFound(): void {
        http_response_code(404);
        echo '<h1>404 — Halaman tidak ditemukan</h1>';
    }
}
