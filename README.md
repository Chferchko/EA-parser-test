### Требования

- `git`
- `docker`
- `docker compose`
- `make`
---
### Установка

1. Склонировать репозиторий
```text
git clone <repo-url>
```

2. Собрать приложение
```bash
  make init
```

3. Запустить Octane
```bash
  make octane-up
```

После установки приложение будет доступно по адресу
```text
http://localhost:8000
```
---
### Хэлперы

Список доступных make таргетов
```bash
  make help
```
---
### Доступы

- `Adminer` панель ДБ: `http://89.108.71.133:8080`
- `Adminer` сервер ДБ: `mysql`
- Database: `parser_test`
- User: `reviewer`
- Password: `q^sei~BAxP0`
- Host: `89.108.71.133`
- Port: `3306`
