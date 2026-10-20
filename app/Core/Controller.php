<?php

namespace App\Core;

class Controller
{
    /**
     * Render view dengan layout header + footer (untuk halaman setelah login).
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/layout/header.php';
        require __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layout/footer.php';
    }

    /**
     * Render view tanpa layout (dipakai untuk halaman login yang berdiri sendiri).
     */
    protected function bareView(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $page, array $params = []): void
    {
        $query = array_merge(['page' => $page], $params);
        header('Location: ' . self::baseUrl() . '?' . http_build_query($query));
        exit;
    }

    /** Path absolut ke front controller (mis. /public/index.php) agar tidak tergantung URL saat ini. */
    public static function baseUrl(): string
    {
        return $_SERVER['SCRIPT_NAME'] ?? 'index.php';
    }
}
