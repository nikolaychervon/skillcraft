# GradeUP 🧠⚡

**GradeUP** — это AI-платформа для профессионального роста разработчиков.  
Персональный ментор, диагностика навыков и умный план развития — всё в одном месте.

---

## 🐳 Установка

1. Клонируй репозиторий:
```shell
  git clone https://github.com/nikolaychervon/gradeup-backend.git
```

2. Неожидано, не так ли:
```shell
  cd gradeup-backend
```

3. Запускай сборку:
```shell
  make setup
```

P.S. Все доступные команды make, можно посмотреть так:
```shell
  make help
```

Сайт: http://localhost

Telescope: http://localhost/telescope

Horizon: http://localhost/horizon

---
## 📂 Структура проекта

```bash
  gradeup-backend/
    ├── 🐳 docker/ # Настройки контейнеров
    ├── 📁 src/
    │   ├── app/
    │   │   ├── Application/    # Приложение
    │   │   │   ├── Auth/           # Аутентификация
    │   │   │   ├── .../            # Слой для n
    │   │   │   ├── Shared/         # Слой для общего кода
    │   │   ├── Domain/         # Бизнес логика
    │   │   │   ├── Auth/           # Аутентификация
    │   │   │   ├── .../            # Слой для n
    │   │   ├── Infrastructure/ # Инфраструктура
    │   │   │   ├── Auth/           # Аутентификация
    │   │   │   ├── Notification/   # Уведомления
    │   │   │   ├── .../            # Слой для n
    │   │   ├── Http/           # Controllers, Requests, Responses, Middlewares
    │   │   ├── Models/         # Общие модели
    │   │   ├── Providers/      # Провайдеры Laravel
    │   ├── config/   # Конфиги
    │   ├── database/ # Миграции, Сидеры, Фабрики
    │   ├── lang/     # Localization (ru/en)
    │   ├── routes/   # api.php (v1)
    │   ├── tests/    # Tests
    │   └── .env (.env.example) # Окружение
    ├── 🐘 docker-compose.yml # Упаковка контейнеров
    ├── 📜 Makefile # Команды для управления проектом
    └── 📖 README.md # Ты тут :)
```