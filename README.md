# Notification Service

Сервис уведомлений на Laravel с асинхронной обработкой очередей через RabbitMQ.

Проект реализован как отдельный Notification Service с поддержкой Email и SMS уведомлений, очередей, retry-механизма, Redis idempotency и PostgreSQL.

---

# Стек проекта

- PHP 8.1
- Laravel 10
- PostgreSQL 15
- Redis 7
- RabbitMQ 3 Management
- Docker / Docker Compose
- Postman

---

# Основной функционал

- Массовая отправка Email/SMS уведомлений
- Асинхронная обработка уведомлений
- RabbitMQ очереди
- Retry механизм
- Redis idempotency защита
- Очереди приоритетов
- Batch уведомления
- Feature тесты
- Docker инфраструктура
- Postman collection

---

# Архитектура проекта

```text
API Request
    ↓
FormRequest Validation
    ↓
DTO
    ↓
NotificationService
    ↓
Repository
    ↓
PostgreSQL
    ↓
RabbitMQ Queue
    ↓
Queue Worker
    ↓
Mock Provider
    ↓
Обновление статуса


---

Слои проекта

Controller
FormRequest
DTO
Service
Repository
Job
Provider Interface
Mock Providers
Status Service
Redis Idempotency Service


---

Статусы уведомлений

queued
sent
delivered
failed


---

Запуск проекта

1. Клонирование репозитория

git clone <repository-url>
cd notification-service


---

2. Создание .env

cp .env.example .env


---

3. Запуск Docker контейнеров

docker compose up -d --build


---

4. Вход в контейнер приложения

docker exec -it notification_app bash


---

5. Установка зависимостей

composer install


---

6. Генерация APP_KEY

php artisan key:generate


---

7. Выполнение миграций

php artisan migrate


---

Docker сервисы

Сервис	Порт

Laravel / Nginx	8001
PostgreSQL	5433
Redis	6379
RabbitMQ	5672
RabbitMQ UI	15672



---

RabbitMQ Management UI

http://localhost:15672

Данные для входа:

guest / guest


---

Запуск Queue Worker

php artisan queue:work rabbitmq --queue=notifications_high,notifications_default -v


---

Очереди приоритетов

High priority:

notifications_high

Normal priority:

notifications_default


---

API Endpoints


---

Создание уведомлений

POST /api/v1/notifications

Пример запроса

{
  "channel": "email",
  "message": "Hello from API",
  "priority": "high",
  "recipients": [
    "john@gmail.com",
    "kate@gmail.com"
  ],
  "idempotency_key": "unique-request-key"
}


---

Пример ответа

{
  "success": true,
  "message": "Notifications queued successfully",
  "batch_id": 1
}


---

Получение уведомления по ID

GET /api/v1/notifications/{id}


---

Получение уведомлений пользователя

GET /api/v1/recipients/{recipient}/notifications


---

Idempotency защита

Redis используется для защиты от дублирующих запросов.

Если одинаковый idempotency_key отправляется повторно:

409 Conflict


---

Retry механизм

Jobs автоматически повторяются при временных ошибках провайдера.

После превышения количества попыток:

status = failed

Текст ошибки сохраняется в:

last_error


---

Mock Providers

Вместо реальных внешних сервисов используются mock providers:

MockEmailProvider
MockSmsProvider

Они имитируют:

успешную отправку

временные ошибки

задержку сети



---

Тестирование

Запуск тестов:

php artisan test


---

Покрытие тестами

Проверяются:

создание уведомлений

validation

duplicate requests

queue dispatch

routes

status lifecycle



---

Postman Collection

Файл коллекции:

docs/NotificationService.postman_collection.json

Коллекцию можно импортировать в Postman для ручного тестирования API.


---

Надежность сервиса

В проекте используются:

PostgreSQL transactions

RabbitMQ очереди

Redis deduplication

Retry механизм Laravel Queue

Failed Jobs handling

Статусы доставки уведомлений



---

Архитектурное решение

Проект реализован как монолитный Laravel сервис, но структура разделена по слоям и приближена к microservice-style архитектуре.

Основная цель проекта:

показать работу с очередями

асинхронную обработку

Docker инфраструктуру

RabbitMQ

Redis

надежность доставки уведомлений

тестирование backend системы
