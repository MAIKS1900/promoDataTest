## Быстрый старт (Docker)

Требования: Docker, Docker Compose.
### 1. Скопировать .env и при необходимости отредактировать
```shell
cp .env.example .env
```
### 2. Поднять контейнеры (PHP-FPM, Nginx, БД)
```shell
docker-compose up -d app, nginx, postgres
```
### 3. Установить PHP-зависимости
```shell
docker-compose exec app composer install
```
### 4. Сгенерировать ключ приложения
```shell
docker-compose exec app php artisan key:generate
```
### 5. Накатить миграции + сиды
```shell
docker-compose exec app php artisan migrate --seed
```
### 6. Создать линк для хранилища
```shell
php artisan storage:link
```
### 7. Билд фронтенда
```shell
docker-compose up -d node-build
```
---
### 8. Запуск команды генерации отчета
```shell
php artisan app:last-week-category-prices-report-create -C 1
```
### 9. Просмотр отчета
http://localhost:8080