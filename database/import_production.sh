#!/bin/bash

# Скрипт для инициализации production базы данных PostgreSQL

echo "Инициализация production базы данных..."

cd /var/www/html

# Ждем пока PostgreSQL станет доступен
echo "Ожидание подключения к PostgreSQL..."
until php artisan migrate:status 2>/dev/null; do
    echo "PostgreSQL еще не готов, ждем..."
    sleep 2
done

echo "PostgreSQL доступен!"

# Запускаем миграции
echo "Запуск миграций..."
php artisan migrate --force

# Загружаем начальные данные
echo "Загрузка начальных данных..."
php artisan db:seed --force

# Создаем символическую ссылку для storage
echo "Создание символической ссылки для storage..."
php artisan storage:link

# Очищаем и кешируем конфигурацию
echo "Кеширование конфигурации..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Инициализация завершена!"
echo ""
echo "Учетные данные по умолчанию:"
echo "Email: admin@admin.ru"
echo "Password: admin"
echo ""
echo "ВАЖНО: Смените пароль администратора после первого входа!"
