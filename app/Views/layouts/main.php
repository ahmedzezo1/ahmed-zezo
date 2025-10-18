<?php use App\Core\View; use App\Core\Session; ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= View::e($title ?? 'لوحة إدارة الأصول'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        body { padding-bottom: 4rem; }
        header.container { margin-top: 1rem; }
        .flash { margin: 0.5rem 0; }
        .flash-success { color: #0a7d2d; }
        .flash-error { color: #b41010; }
    </style>
</head>
<body>
    <header class="container">
        <nav>
            <ul>
                <li><strong>إدارة الأصول التقنية</strong></li>
            </ul>
            <ul>
                <li><a href="/">الرئيسية</a></li>
                <li><a href="/assets">الأصول</a></li>
                <li><a href="/login">دخول</a></li>
            </ul>
        </nav>
        <?php foreach (Session::allFlashes() as $type => $message): ?>
            <p class="flash flash-<?= View::e($type) ?>"><?= View::e($message) ?></p>
        <?php endforeach; ?>
    </header>

    <main class="container">
        <?= $content ?? '' ?>
    </main>

    <footer class="container">
        <small>© <?= date('Y') ?> - IT Asset Management</small>
    </footer>
</body>
</html>
