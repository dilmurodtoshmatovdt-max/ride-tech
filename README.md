# RideTech RESTful API

RESTful API для сервиса райдшеринга RideTech. Сервис позволяет пассажирам создавать поездки, а водителям — управлять поездками и транспортными средствами.  

---

## 🔹 Технологии

- Backend: PHP 8+, Laravel 11  
- База данных: MySQL  
- Аутентификация:  JWT
- ORM: Eloquent  
- Документация API: Laravel Docs 
- Тестирование: Feature Test  

---


### Аутентификация и управление пользователями

- Регистрация пользователя (пассажир / водитель)  
- Вход и выход из системы (JWT)  

### Управление поездками

**Пассажир:**

- Создание поездки (адрес отправления, адрес назначения, предпочтения)  
- Отмена поездки  
- Просмотр истории поездок  

**Водитель:**

- Просмотр доступных поездок (статус "ожидает водителя")  
- Принятие / отклонение поездки  
- Завершение поездки  

### Управление транспортными средствами (водители)

- Добавление машины (модель, марка, номерной знак)  
- Удаление / обновление информации о транспорте  

### Рейтинг и отзывы

- Пассажиры могут оставлять отзывы водителям  
- Просмотр отзывов о водителях  

### Дополнительно

- Пагинация списка поездок  
- Фильтрация поездок по статусу, дате, пассажиру, водителю  

---

## 🔹 API Endpoints

### Аутентификация

| Метод | URL | Описание |
|-------|-----|----------|
| POST | /api/v1/auth/register | Регистрация |
| POST | /api/v1/auth/login | Вход |
| POST | /api/v1/auth/logout | Выход |
| GET  | /api/v1/auth/refresh | Выход |

### Управление машинами

| Метод | URL | Описание |
|-------|-----|----------|
| GET  | /api/v1/cars | Список машин водителя |
| GET  | /api/v1/cars/{id} | Подробнее машины |
| POST | /api/v1/cars | Добавить машину |
| PUT  | /api/v1/cars/{id} | Изменение машины |
| DELETE | /api/v1/cars/{id} | Удалить машину |

### Управление поездками

| Метод | URL | Описание |
|-------|-----|----------|
| GET  | /api/v1/trips | Список поездок пользователя |
| GET  | /api/v1/trips/{id} | Детали поездки |
| POST | /api/v1/trips | Создать поездку |
| PUT  | /api/v1/trips/{id} | Обновить поездку |
| PUT  | /api/v1/trips/{id}/cancel | Отменить поездку |
| PUT  | /api/v1/trips/{id}/reject | Отклонить поездку |
| PUT  | /api/v1/trips/{id}/assign | Принять поездку |
| PUT  | /api/v1/trips/{id}/arrive | Приезжать |
| PUT  | /api/v1/trips/{id}/start | Начать поездку |
| PUT  | /api/v1/trips/{id}/finish | Завершит поездку |



### Рейтинг и отзывы

| Метод | URL | Описание |
|-------|-----|----------|
| POST | /api/v1/reviews/ | Оставить отзыв водителю |
| GET  | /api/v1/reviews/{driver_id} | Список отзывов |

---

## 🔹 Установка и запуск

1. Клонировать репозиторий:

```bash
git clone https://github.com/dilmurodtoshmatovdt-max/ride-tech.git
cd ride-tech

2. Создать .env файл в корне проекта (пример ниже):
APP_NAME=ride-tech
APP_ENV=local
APP_KEY=base64:9XgOhBHFPV4n1gSaPmi9qjNMT05jxZIKaP8XHQQdZ3A=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost
APP_HOST=0.0.0.0
APP_PORT=8000
APP_VERBOSE='--verbose'
APP_WATCH='--watch'
APP_URL=http://0.0.0.0
ASSET_URL=http://0.0.0.0:8000


PMA_PORT=8050

APP_LOCALE=ru
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=app
DB_PASSWORD=123
DB_ROOT_PASSWORD=123
DB_PREFIX=


SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis

CACHE_STORE=redis
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=host.docker.internal
REDIS_PASSWORD=e2a1413d-700e-4493-8c8b-841a64924c41
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

OCTANE_SERVER=swoole

TELESCOPE_ENABLED=true

JWT_SECRET_KEY=123
JWT_TTL=86400
JWT_REFRESH_TTL=2592000

JWT_SHORT_REFRESH_TTL=
JWT_BLACKLIST_ENABLED=true

PASSWORD_SALT=123

PASSWORD_LENGTH=4



3. Запускать bash комманду make (это команда запускает  Docker Compose с указанным проектом ride-tech)

4. Документация доступен по адресу http://127.0.0.1:8000/docs