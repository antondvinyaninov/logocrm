# Деплой LogoCRM на Easypanel

## Подготовка

### 1. Генерация APP_KEY

Локально выполните:
```bash
php artisan key:generate --show
```

Скопируйте полученный ключ (например: `base64:...`)

### 2. Создание дампа базы данных (опционально)

Если хотите перенести текущую базу:
```bash
sqlite3 database/database.sqlite .dump > database/production_dump.sql
```

## Деплой через Easypanel

### Шаг 1: PostgreSQL база данных

База данных уже создана в Easypanel:
- **Service Name:** `logoCRM`
- **Internal Host:** `test_logocrm`
- **Database:** `logo`
- **Username:** `logopos`
- **Password:** `dxG0BBG0`
- **Port:** 5432

### Шаг 2: Создание приложения в Easypanel

Приложение уже создано:
- **Repository:** `https://github.com/antondvinyaninov/logocrm`
- **Branch:** `main`
- **Dockerfile:** Обнаружен автоматически

### Шаг 3: Настройка переменных окружения

В Easypanel в разделе **Environment** убедитесь что установлены переменные:

```env
APP_NAME=LogoCRM
APP_ENV=production
APP_DEBUG=false
APP_URL=https://logocrm.crv1ic.easypanel.host
APP_KEY=base64:NooEi4osW85mAudAtNsz9JV06PUi1bjG7n9TlnEf8pA=

DB_CONNECTION=pgsql
DB_HOST=test_logocrm
DB_PORT=5432
DB_DATABASE=logo
DB_USERNAME=logopos
DB_PASSWORD=dxG0BBG0

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error

UPLOAD_MAX_FILESIZE=120M
POST_MAX_SIZE=120M
FILESYSTEM_DISK=public

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@logocrm.ru
MAIL_FROM_NAME=LogoCRM
```

**ВАЖНО:** Убедитесь что `DB_HOST=test_logocrm` (это внутренний hostname PostgreSQL сервиса)

### Шаг 4: Deploy

Приложение сейчас деплоится в Easypanel. Дождитесь завершения сборки (5-10 минут).

Проверить статус можно в Easypanel → Logs

### Шаг 5: Инициализация базы данных

После успешного деплоя:

1. Откройте **Terminal** в Easypanel для вашего приложения
2. Выполните:

```bash
# Импорт базы данных
bash /var/www/html/database/import_production.sh
```

Или вручную:

```bash
cd /var/www/html

# Если есть дамп
rm database/database.sqlite
touch database/database.sqlite
sqlite3 database/database.sqlite < database/production_dump.sql

# Если нет дампа - создать новую БД
php artisan migrate --force
php artisan db:seed --force

# Права доступа
chown www-data:www-data database/database.sqlite
chmod 664 database/database.sqlite

# Символическая ссылка для storage
php artisan storage:link

# Кеширование
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Шаг 7: Проверка

Откройте URL приложения (например: https://logocrm.crv1ic.easypanel.host)

**Учетные данные по умолчанию:**
- Email: `admin@admin.ru`
- Password: `admin`

## Обновление приложения

### Автоматический деплой через GitHub

```bash
git add .
git commit -m "Update: описание изменений"
git push origin main
```

Easypanel автоматически пересоберет контейнер (если настроен webhook).

### Ручной деплой

1. Зайти в Easypanel
2. Открыть приложение
3. Нажать **"Rebuild"**

## Troubleshooting

### Ошибка 500

Проверьте логи:
1. В Easypanel откройте **Logs**
2. Проверьте права на файлы:
```bash
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

### База данных не работает

```bash
# Проверить существование файла
ls -la /var/www/html/database/database.sqlite

# Проверить права
chmod 664 /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
```

### Изображения не загружаются

```bash
# Создать символическую ссылку
php artisan storage:link

# Проверить права
chmod -R 775 /var/www/html/storage
```

## Backup

### Создание бэкапа базы данных

```bash
# В терминале контейнера
sqlite3 /var/www/html/database/database.sqlite .dump > /tmp/backup_$(date +%Y%m%d).sql

# Скачать на локальный компьютер через Easypanel File Manager
```

### Восстановление из бэкапа

```bash
rm /var/www/html/database/database.sqlite
touch /var/www/html/database/database.sqlite
sqlite3 /var/www/html/database/database.sqlite < /path/to/backup.sql
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite
```

## Мониторинг

- **Логи приложения:** Easypanel → App → Logs
- **Метрики:** Easypanel показывает CPU/Memory usage
- **Laravel логи:** `/var/www/html/storage/logs/laravel.log`

## Безопасность

После деплоя:

1. ✅ Смените пароль администратора
2. ✅ Настройте регулярные бэкапы
3. ✅ Настройте HTTPS (Easypanel делает автоматически)
4. ✅ Отключите APP_DEBUG в production
5. ✅ Настройте email для уведомлений

---

**Дата создания:** 7 января 2026
