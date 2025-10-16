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
    APP_ENV=production
    APP_KEY=base64:9XgOhBHFPV4n1gSaPmi9qjNMT05jxZIKaP8XHQQdZ3A=
    APP_DEBUG=true
    APP_URL=http://localhost
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=app
    DB_USERNAME=app
    DB_PASSWORD=123
    REDIS_HOST=host.docker.internal
    REDIS_PASSWORD=e2a1413d-700e-4493-8c8b-841a64924c41
    REDIS_PORT=6379
    JWT_SECRET_KEY=123


3. Запускать bash комманду make (это команда запускает  Docker Compose с указанным проектом ride-tech)

4. Документация доступен по адресу http://127.0.0.1:8000/docs