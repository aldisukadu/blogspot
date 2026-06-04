<?php
// index.php — Application Entry Point

declare(strict_types=1);

session_start();

// ── Autoload ──────────────────────────────────────────────────────────────
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Post.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/controllers/PostController.php';
require_once __DIR__ . '/controllers/CategoryController.php';

// ── Router ────────────────────────────────────────────────────────────────
$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? 'index';
$slug   = $_GET['slug']   ?? '';
$id     = (int)($_GET['id'] ?? 0);

$postCtrl = new PostController();
$catCtrl  = new CategoryController();

switch ($page) {

    // ── Posts ────────────────────────────────────────────────────────────
    case 'home':
    case '':
        $postCtrl->index();
        break;

    case 'posts':
        switch ($action) {
            case 'show':   $postCtrl->show($slug); break;
            case 'create': $postCtrl->create();     break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $postCtrl->store();
                else redirect('index.php?page=posts&action=create');
                break;
            case 'edit':   $postCtrl->edit($id);    break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $postCtrl->update($id);
                else redirect('index.php?page=posts&action=edit&id=' . $id);
                break;
            case 'delete': $postCtrl->delete($id);  break;
            default:       $postCtrl->index();       break;
        }
        break;

    // ── Categories ───────────────────────────────────────────────────────
    case 'categories':
        switch ($action) {
            case 'create': $catCtrl->create();        break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $catCtrl->store();
                else redirect('index.php?page=categories&action=create');
                break;
            case 'delete': $catCtrl->delete($id);     break;
            default:
                $slug ? $catCtrl->show($slug) : $catCtrl->index();
                break;
        }
        break;

    // ── Search ───────────────────────────────────────────────────────────
    case 'search':
        $postCtrl->search();
        break;

    // ── 404 ──────────────────────────────────────────────────────────────
    default:
        http_response_code(404);
        $postCtrl->index();
        break;
}
