Note: some work has been delegated to AI agent - AI usage logged in commits comments.
https://github.com/avysokikh/testBlog/commits/main/

# Test Blog

Небольшой travel-блог на PHP 8.4, MySQL и шаблонах Smarty.

## Требования

- **PHP** 8.4+ с расширениями: `pdo`, `pdo_mysql`, `zip`
- **Composer** 2
- **MySQL** 8+
- **Node.js** 18+ и npm (только для компиляции SCSS)

Дополнительно:

- **Docker** и Docker Compose (рекомендуется для локальной разработки)

## Быстрый старт (Docker)

### 1. Клонирование и настройка окружения

```bash
cp .env.example .env
```

Значения по умолчанию подходят для Docker Compose. Приложение будет доступно по адресу [http://localhost:8000](http://localhost:8000).

### 2. Запуск контейнеров

```bash
docker compose up -d --build
```

Будут запущены:

- `app` — встроенный PHP-сервер на порту `8000`
- `mysql` — MySQL на порту `3306`

### 3. Установка PHP-зависимостей

Если каталог `vendor/` отсутствует, установите зависимости внутри контейнера:

```bash
docker compose exec app composer install
```

### 4. Создание схемы базы данных

Импортируйте схему в MySQL (только при первой настройке):

```bash
docker compose exec -T mysql mysql -uroot -proot blog < database/schema.sql
```

Если вы изменили `MYSQL_ROOT_PASSWORD` в `.env`, используйте этот пароль вместо `root`.

### 5. Сборка стилей

На хост-машине:

```bash
npm install
npm run sass:build
```

Скомпилированный CSS сохраняется в `public/css/app.css`.

### 6. Заполнение базы данных

Внутри контейнера:

```bash
docker compose exec app php bin/seed.php
```

Или с хоста (если MySQL доступен на `127.0.0.1:3306`):

```bash
composer install
php bin/seed.php
```

Откройте [http://localhost:8000](http://localhost:8000).

## Локальная установка (без Docker)

### 1. Окружение

```bash
cp .env.example .env
```

Убедитесь, что `.env` указывает на локальный MySQL:

```env
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_DATABASE=blog
MYSQL_USER=blog
MYSQL_PASSWORD=blog
APP_URL=http://localhost:8000
```

Создайте базу данных и пользователя в MySQL, затем импортируйте схему:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p blog < database/schema.sql
```

### 2. Установка зависимостей

```bash
composer install
npm install
npm run sass:build
```

### 3. Заполнение данными

```bash
php bin/seed.php
```

### 4. Запуск приложения

```bash
php -S localhost:8000 -t public
```

Перейдите на [http://localhost:8000](http://localhost:8000).

## Заполнение базы (seed)

Файлы с данными находятся в `database/seed/`:

- `categories.json` — категории блога
- `articles.json` — статьи со связями с категориями и путями к изображениям

### Полное заполнение (по умолчанию)

Очищает существующие категории, статьи и связи, затем загружает данные из JSON:

```bash
php bin/seed.php
```

Или через Composer:

```bash
composer seed
```

В Docker:

```bash
docker compose exec app php bin/seed.php
```

### Режим добавления

Добавляет записи без очистки таблиц:

```bash
php bin/seed.php --append
```

Используйте его только когда нужно сохранить существующие данные. Для разработки рекомендуется режим **fresh** (по умолчанию).

### После изменения JSON или изображений

1. Обновите `database/seed/*.json` и/или файлы в `public/uploads/`
2. Снова выполните `php bin/seed.php`

Изображения статей хранятся в `public/uploads/` и указываются в `articles.json` как пути вида `/uploads/prague.jpg`.

Чтобы заново скачать все изображения статей:

```bash
python3 bin/download_article_images.py
```

## Frontend-ресурсы

Однократная компиляция SCSS:

```bash
npm run sass:build
```

Отслеживание изменений при разработке:

```bash
npm run sass:watch
```

Исходники: `assets/scss/app.scss`  
Результат: `public/css/app.css`

## Полезные команды

| Команда | Описание |
|---------|----------|
| `docker compose up -d` | Запустить app и MySQL |
| `docker compose down` | Остановить контейнеры |
| `docker compose logs -f app` | Просмотр логов приложения |
| `composer install` | Установить PHP-зависимости |
| `php bin/seed.php` | Полное заполнение базы |
| `npm run sass:build` | Скомпилировать стили |

## Структура проекта

```
app/                  Код приложения (контроллеры, модели, репозитории)
assets/scss/          Исходники SCSS
bin/seed.php          CLI для заполнения базы
config/config.php     Конфигурация приложения
database/schema.sql   Схема MySQL
database/seed/        JSON-данные для seed
public/               Корень сайта (index.php, css, uploads)
templates/            Шаблоны Smarty
```

## Решение проблем

**Ошибка подключения к базе данных**

- Убедитесь, что MySQL запущен и данные в `.env` верны.
- В Docker внутри контейнера `app` используйте `MYSQL_HOST=mysql` (уже задано в `docker-compose.yml`).
- На хосте используйте `MYSQL_HOST=127.0.0.1`.

**Таблицы не существуют**

- Импортируйте схему: `database/schema.sql`.

**Отсутствуют стили**

- Выполните `npm install && npm run sass:build`.

**Пустая главная страница**

- Запустите seeder: `php bin/seed.php`.
- На главной скрываются категории без статей.

---

# Test Blog (English)

A small travel blog built with PHP 8.4, MySQL, and Smarty templates.

## Requirements

- **PHP** 8.4+ with extensions: `pdo`, `pdo_mysql`, `zip`
- **Composer** 2
- **MySQL** 8+
- **Node.js** 18+ and npm (only for compiling SCSS)

Optional:

- **Docker** and Docker Compose (recommended for local setup)

## Quick start (Docker)

### 1. Clone and configure environment

```bash
cp .env.example .env
```

Default values work with Docker Compose. The app will be available at [http://localhost:8000](http://localhost:8000).

### 2. Start containers

```bash
docker compose up -d --build
```

This starts:

- `app` — PHP built-in server on port `8000`
- `mysql` — MySQL on port `3306`

### 3. Install PHP dependencies

If `vendor/` is missing, install dependencies inside the container:

```bash
docker compose exec app composer install
```

### 4. Create database schema

Import the schema into MySQL (only needed on first setup):

```bash
docker compose exec -T mysql mysql -uroot -proot blog < database/schema.sql
```

If you changed `MYSQL_ROOT_PASSWORD` in `.env`, use that password instead of `root`.

### 5. Build frontend styles

On the host:

```bash
npm install
npm run sass:build
```

Compiled CSS is written to `public/css/app.css`.

### 6. Seed the database

Inside the container:

```bash
docker compose exec app php bin/seed.php
```

Or from the host (with MySQL exposed on `127.0.0.1:3306`):

```bash
composer install
php bin/seed.php
```

Open [http://localhost:8000](http://localhost:8000).

## Local setup (without Docker)

### 1. Environment

```bash
cp .env.example .env
```

Ensure `.env` points to your local MySQL:

```env
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_DATABASE=blog
MYSQL_USER=blog
MYSQL_PASSWORD=blog
APP_URL=http://localhost:8000
```

Create the database and user in MySQL, then import the schema:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p blog < database/schema.sql
```

### 2. Install dependencies

```bash
composer install
npm install
npm run sass:build
```

### 3. Seed data

```bash
php bin/seed.php
```

### 4. Run the app

```bash
php -S localhost:8000 -t public
```

Visit [http://localhost:8000](http://localhost:8000).

## Seeding data

Seed files live in `database/seed/`:

- `categories.json` — blog categories
- `articles.json` — articles with category links and image paths

### Fresh seed (default)

Clears existing categories, articles, and links, then inserts data from JSON:

```bash
php bin/seed.php
```

Or via Composer:

```bash
composer seed
```

In Docker:

```bash
docker compose exec app php bin/seed.php
```

### Append mode

Adds records without truncating tables:

```bash
php bin/seed.php --append
```

Use this only when you intentionally want to keep existing rows. The default **fresh** mode is recommended for development.

### After changing seed JSON or images

1. Update `database/seed/*.json` and/or files in `public/uploads/`
2. Run `php bin/seed.php` again to reload data

Article images are stored in `public/uploads/` and referenced in `articles.json` as paths like `/uploads/prague.jpg`.

To re-download all article images:

```bash
python3 bin/download_article_images.py
```

## Frontend assets

Compile SCSS once:

```bash
npm run sass:build
```

Watch for changes during development:

```bash
npm run sass:watch
```

Source: `assets/scss/app.scss`  
Output: `public/css/app.css`

## Useful commands

| Command | Description |
|---------|-------------|
| `docker compose up -d` | Start app and MySQL |
| `docker compose down` | Stop containers |
| `docker compose logs -f app` | View app logs |
| `composer install` | Install PHP dependencies |
| `php bin/seed.php` | Fresh database seed |
| `npm run sass:build` | Compile styles |

## Project structure

```
app/                  Application code (controllers, models, repositories)
assets/scss/          SCSS sources
bin/seed.php          Database seeder CLI
config/config.php     App configuration
database/schema.sql   MySQL schema
database/seed/        JSON seed data
public/               Web root (index.php, css, uploads)
templates/            Smarty templates
```

## Troubleshooting

**Database connection failed**

- Check that MySQL is running and credentials in `.env` match your setup.
- In Docker, use `MYSQL_HOST=mysql` inside the `app` container (already set in `docker-compose.yml`).
- On the host, use `MYSQL_HOST=127.0.0.1`.

**Tables do not exist**

- Import the schema: `database/schema.sql`.

**Styles are missing**

- Run `npm install && npm run sass:build`.

**Empty homepage**

- Run the seeder: `php bin/seed.php`.
- Categories without articles are hidden on the home page.
