<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$page_title|escape} — Блог</title>
</head>
<body>
    <header class="site-header">
        <div class="container site-header__inner">
            <a class="logo" href="/">Блог</a>
            <nav class="site-nav">
                <a href="/">Главная</a>
            </nav>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            {block name=content}{/block}
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Тестовое задание</p>
        </div>
    </footer>
</body>
</html>
