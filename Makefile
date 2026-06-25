help:
	@awk ' \
		/^##@/ { \
			printf "\n\033[33m%s\033[0m\n", substr($$0, 5); \
			next; \
		} \
		/^[0-9a-zA-Z_-]+:.*##/ { \
			split($$0, parts, ":.*## "); \
			printf "  \033[32m%-28s\033[0m %s\n", parts[1], parts[2]; \
			next; \
		} \
		END { printf "\n" } \
	' $(MAKEFILE_LIST)
.PHONY: help
.DEFAULT_GOAL := help

##@ Infrastructure

build: ## Собрать и запустить контейнеры
	docker compose up --build -d --remove-orphans
.PHONY: build

up: ## Запустить контейнеры
	docker compose up -d --remove-orphans
.PHONY: up

down: ## Остановить и удалить контейнеры
	docker compose down --remove-orphans
.PHONY: down

down-clean: ## Остановить контейнеры и удалить volumes
	docker compose down --remove-orphans -v
.PHONY: down-clean

##@ Development

init: ## Развернуть проект
	$(MAKE) build
	$(MAKE) composer-install
	docker compose exec php cp .env.example .env
	$(MAKE) artisan cmd="key:generate"
	$(MAKE) artisan cmd="migrate"
	$(MAKE) octane-install
.PHONY: init

php: ## Войти в PHP контейнер через bash
	docker compose exec php bash
.PHONY: php

composer-install: ## Установить PHP зависимости
	docker compose exec php composer install
.PHONY: composer-install

octane-install: ## Установить Octane и настроить Swoole
	docker compose exec php composer require laravel/octane
	$(MAKE) artisan cmd="octane:install --server=swoole"
.PHONY: octane-install

octane-up: ## Запустить Octane
	$(MAKE) artisan cmd="octane:start --server=swoole --host=0.0.0.0 --port=8000"
.PHONY: octane-up

octane-reload: ## Перезагрузить Octane
	$(MAKE) artisan cmd="octane:reload"
.PHONY: octane-reload

artisan: ## Выполнить artisan команду / <make artisan cmd="migrate">
	docker compose exec php php artisan $(cmd)
.PHONY: artisan

##@ Monitoring

logs: ## Смотреть логи всех контейнеров
	docker compose logs -f
.PHONY: logs
