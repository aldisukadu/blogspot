<?php
// helpers/functions.php

/**
 * Generate URL-friendly slug dari string
 */
function generateSlug(string $text): string {
    $text = mb_strtolower(trim($text));
    // Indonesian character map
    $map = ['à'=>'a','á'=>'a','â'=>'a','ä'=>'a','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
            'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ò'=>'o','ó'=>'o','ô'=>'o','ö'=>'o',
            'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ñ'=>'n','ç'=>'c'];
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Sanitize user input
 */
function cleanInput(string $input): string {
    return htmlspecialchars(trim(strip_tags($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect ke URL
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Set flash message ke session
 */
function setFlash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get & clear flash message
 */
function getFlash(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Format tanggal ke Bahasa Indonesia
 */
function formatDate(string $date, string $format = 'd M Y'): string {
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $d      = new DateTime($date);
    $output = $d->format($format);
    // Replace English month abbreviations
    $engMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return str_replace($engMonths, $months, $output);
}

/**
 * Truncate text
 */
function truncate(string $text, int $length = 150, string $suffix = '...'): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Generate read time estimate
 */
function readTime(string $content): string {
    $wordCount = str_word_count(strip_tags($content));
    $minutes   = (int) ceil($wordCount / 200);
    return $minutes . ' menit baca';
}

/**
 * Format angka
 */
function formatNumber(int $num): string {
    if ($num >= 1000) return round($num / 1000, 1) . 'K';
    return (string)$num;
}

/**
 * Active nav check
 */
function isActivePage(string $page): string {
    return ($_GET['page'] ?? 'home') === $page ? 'active' : '';
}

/**
 * CSRF token generate & verify
 */
function csrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('CSRF token mismatch');
    }
}
