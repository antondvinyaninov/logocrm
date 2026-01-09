# Инфраструктура проектов

## Обзор

**Сервер:** 88.218.121.213  
**Панель управления:** Easypanel  
**Провайдер:** hoztnode.net  
**ОС:** Ubuntu 24.04 LTS  
**Docker:** Установлен  
**Firewall:** Отключен (ufw disabled)

## Проекты на сервере

### 1. LogoCRM - CRM для логопедических центров ✅
**Статус:** Production  
**URL:** https://test-logo-crm.crv1ic.easypanel.host  
**Тип:** Laravel 12 + PHP 8.4 + PostgreSQL  
**GitHub:** https://github.com/antondvinyaninov/logocrm

### 2. АСУП - Автоматизированная Система Управления Приютом
**Статус:** Deployed (требует настройки)  
**URL:** https://test-priut.crv1ic.easypanel.host  
**Тип:** Laravel 12 + PHP 8.4 + SQLite

## SSH Доступ

**IP:** 88.218.121.213  
**User:** root  
**Команда подключения:**
```bash
ssh root@88.218.121.213
```

## VPN Сервисы

### 1. Outline VPN ✅

**Статус:** Работает

**Management Key:**
```json
{
  "apiUrl": "https://88.218.121.213:37375/rQ8eWVlqpfT2LoIa_4VGRQ",
  "certSha256": "51CAE6A9E60CA22C3B4B68F28525ADEB236456BE9691E2925A0408126E0CF108"
}
```

**Порты:**
- TCP: 37375 (управление)
- TCP/UDP: 26144 (VPN)

### 2. Pritunl VPN (OpenVPN) ✅

**URL:** https://88.218.121.213:9443  
**Username:** pritunl  
**Password:** z0q37maU46zg  
**Setup Key:** 65112339eb2a40d7a77e5a9482a78ef7  
**Организация:** LK  
**Пользователь:** vpn  
**Порт VPN:** 1194/udp

**Для роутера:** Скачать .ovpn файл из веб-интерфейса

#### Настройка роутера Keenetic для VPN

1. Установить компонент OpenVPN-клиент
2. Скачать .ovpn файл из Pritunl (https://88.218.121.213:9443)
3. В роутере: Интернет → Другие подключения → VPN-подключения
4. Добавить подключение → OpenVPN → Загрузить файл конфигурации
5. Включить опцию "Использовать для выхода в интернет"
6. Применить и подключиться

## Easypanel - Панель управления

**URL:** http://88.218.121.213:3000  
**Статус:** Работает

Easypanel - это панель управления для деплоя Docker-приложений. Позволяет:
- Деплоить приложения из GitHub
- Управлять переменными окружения
- Просматривать логи
- Управлять базами данных и сервисами

## Структура сервисов

### 1. LogoCRM - Production ✅

**Название сервиса:** `logo_crm`  
**URL:** https://test-logo-crm.crv1ic.easypanel.host  
**Тип:** Laravel 12 + PHP 8.4 + PostgreSQL  
**Статус:** ✅ Работает

#### GitHub подключение:
- **Repository:** `https://github.com/antondvinyaninov/logocrm.git`
- **Branch:** `main`

#### База данных PostgreSQL:
- **Service Name:** `logoCRM`
- **Internal Host:** `test_logocrm`
- **Database:** `logo`
- **Username:** `logopos`
- **Password:** `dxG0BBG0`
- **Port:** 5432

#### Переменные окружения:
```env
APP_NAME=LogoCRM
APP_ENV=production
APP_DEBUG=false
APP_URL=https://test-logo-crm.crv1ic.easypanel.host
ASSET_URL=https://test-logo-crm.crv1ic.easypanel.host
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
FILESYSTEM_DISK=public
```

#### Тестовые учетные записи:
- **SuperAdmin:** `superadmin@logoped.test` / `password`
- **Владелец центра:** `owner@rechevichok.ru` / `password`
- **Специалист:** `specialist@logoped.test` / `password`
- **Родитель:** `parent@logoped.test` / `password`

### 2. АСУП - Приют

**Название сервиса:** `test-priut`  
**URL:** https://test-priut.crv1ic.easypanel.host  
**Тип:** Laravel 12 + PHP 8.4 + SQLite  
**Статус:** Deployed (требует настройки)

#### GitHub подключение:
- **Repository:** `https://github.com/antondvinyaninov/pet-priyut.git`
- **Branch:** `main`

#### Переменные окружения:
```env
APP_NAME="АСУП"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://test-priut.crv1ic.easypanel.host

# ВАЖНО: Сгенерировать командой: php artisan key:generate --show
APP_KEY=

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

# Yandex Maps API
YANDEX_MAPS_API_KEY=ece8ef8e-8782-426f-951d-79e965468547

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# File uploads
UPLOAD_MAX_FILESIZE=120M
POST_MAX_SIZE=120M
```

### 3. Другие сервисы на сервере

#### Blog (Astro + React)
**Название сервиса:** `test-github`
**URL:** https://test-github.crv1ic.easypanel.host
**Repository:** `https://github.com/antondvinyaninov/antondvinyaninov.github.io.git`

#### Supabase
**Название сервиса:** `baze-supabase`
**URL:** https://baze-supabase.crv1ic.easypanel.host
**Username:** `supabase`
**Password:** `this_password_is_insecure_and_should_be_updated`

## Процесс деплоя АСУП

### Первоначальная настройка в Easypanel

1. **Подключение к серверу:**
   ```bash
   ssh root@88.218.121.213
   ```

2. **Открыть Easypanel:**
   - URL: http://88.218.121.213:3000

3. **Создать новый App:**
   - Нажать "Create" → "App"
   - Выбрать "GitHub" как источник
   - Подключить репозиторий `antondvinyaninov/pet-priyut`
   - Выбрать ветку `main`
   - Easypanel автоматически обнаружит `Dockerfile`

4. **Настроить переменные окружения:**
   - В настройках приложения добавить переменные из `.env.production`
   - Сгенерировать `APP_KEY`:
     ```bash
     php artisan key:generate --show
     ```

5. **Настроить домен:**
   - Easypanel автоматически создаст поддомен `asup-shelter.crv1ic.easypanel.host`
   - Или настроить свой домен

6. **Deploy:**
   - Нажать "Deploy"
   - Easypanel соберет Docker образ и запустит контейнер

### Обновление проекта

#### Автоматический деплой через GitHub:
```bash
# Локально
git add .
git commit -m "Update: описание изменений"
git push origin main
```

Easypanel автоматически подхватит изменения и пересоберет контейнер (если настроен webhook).

#### Ручной деплой через Easypanel:
1. Зайти в панель Easypanel
2. Открыть приложение `asup-shelter`
3. Нажать "Rebuild" или "Redeploy"

### Первый запуск приложения

После деплоя нужно импортировать базу данных:

#### Вариант 1: Импорт production базы данных (рекомендуется)

```bash
# Подключиться к контейнеру через Easypanel Terminal

# Выполнить скрипт импорта
bash /var/www/html/database/import_production.sh

# Или вручную:
cd /var/www/html
rm database/database.sqlite
touch database/database.sqlite
sqlite3 database/database.sqlite < database/production_dump.sql
chown www-data:www-data database/database.sqlite
chmod 664 database/database.sqlite
```

#### Вариант 2: Создать новую базу с тестовыми данными

```bash
# Подключиться к контейнеру через Easypanel Terminal или SSH
docker exec -it <container_name> bash

# Выполнить миграции
php artisan migrate --force

# Загрузить начальные данные
php artisan db:seed --force

# Создать символическую ссылку для storage
php artisan storage:link

# Импортировать карточки животных (опционально)
php artisan animals:import-cards
```

**Учетные данные по умолчанию:**
- Email: `admin@admin.ru`
- Password: `admin`

### Просмотр логов

В Easypanel:
1. Открыть приложение
2. Перейти в раздел "Logs"
3. Выбрать контейнер для просмотра логов

## Troubleshooting

### Проблема: Изображения не загружаются

**Решение:**
1. Проверить политики RLS в Supabase Storage
2. Убедиться что bucket `blog-images` публичный
3. Проверить переменные окружения в Easypanel

### Проблема: Ошибка при деплое

**Решение:**
1. Проверить логи в Easypanel
2. Убедиться что все зависимости установлены в `package.json`
3. Проверить что `Dockerfile` корректный

### Проблема: База данных недоступна

**Решение:**
1. Проверить что Supabase сервис запущен в Easypanel
2. Проверить переменные `SUPABASE_URL` и ключи
3. Проверить логи Supabase контейнера

## Backup и восстановление

### Backup базы данных

В Supabase Studio:
1. Database → Backups
2. Create backup

### Backup изображений

Изображения хранятся в Supabase Storage bucket `blog-images`. Для backup:
1. Использовать Supabase CLI
2. Или скачать через Storage API

## Мониторинг

- **Логи приложения:** Easypanel → App → Logs
- **Логи Supabase:** Easypanel → Supabase service → Logs
- **Метрики:** Easypanel показывает CPU/Memory usage

### Полезные команды для мониторинга

```bash
# Список всех Docker контейнеров
docker ps -a

# Просмотр логов контейнера
docker logs -f <container_name>

# Использование диска
df -h

# Использование памяти
free -h

# Процессы
htop

# Сетевые подключения
netstat -tulpn

# Перезапуск контейнера
docker restart <container_name>
```

## Резервное копирование

### Важные директории

- `~/pritunl/` - данные Pritunl
- Easypanel и Supabase управляются через Docker volumes

### Создание бэкапа

```bash
# Бэкап Pritunl
tar -czf pritunl-backup-$(date +%Y%m%d).tar.gz ~/pritunl/

# Скачать на локальный компьютер
scp root@88.218.121.213:~/pritunl-backup-*.tar.gz ~/Downloads/
```

### Backup базы данных

В Supabase Studio:
1. Database → Backups
2. Create backup

### Backup изображений

Изображения хранятся в Supabase Storage bucket `blog-images`. Для backup:
1. Использовать Supabase CLI
2. Или скачать через Storage API

## Безопасность

⚠️ **Рекомендации:**

- [ ] Сменить пароль Pritunl (текущий: z0q37maU46zg)
- [ ] Сменить пароль Supabase Dashboard
- [ ] Настроить firewall (ufw) - сейчас отключен
- [ ] Регулярно обновлять систему: `apt update && apt upgrade`
- [ ] Настроить автоматические бэкапы

### Обновление системы

```bash
# Обновить пакеты
apt update && apt upgrade -y

# Перезагрузка (если требуется)
reboot
```

## Активные Docker контейнеры

- `outline` - Outline VPN
- `pritunl` - Pritunl VPN
- `easypanel` - Панель управления
- `supabase-*` - Множество контейнеров Supabase (db, auth, rest, storage, kong, studio и др.)
- `n8n` - Автоматизация и интеграции

## Контакты и доступы

### Сервер
- **IP:** 88.218.121.213
- **SSH:** `ssh root@88.218.121.213`
- **Провайдер:** hoztnode.net

### Панели управления
- **Easypanel:** http://88.218.121.213:3000
- **Supabase Studio:** https://baze-supabase.crv1ic.easypanel.host
- **Pritunl VPN:** https://88.218.121.213:9443
- **Production site:** https://test-github.crv1ic.easypanel.host

### GitHub
- **Repository:** https://github.com/antondvinyaninov/antondvinyaninov.github.io.git
- **Remote:** origin2

### Supabase
- **Username:** supabase
- **Password:** this_password_is_insecure_and_should_be_updated

### Pritunl VPN
- **Username:** pritunl
- **Password:** z0q37maU46zg
- **Setup Key:** 65112339eb2a40d7a77e5a9482a78ef7

---

**Дата создания:** 17 декабря 2025  
**Последнее обновление:** 9 января 2026
