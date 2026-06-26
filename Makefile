SHELL := /bin/bash
EXEC_PHP := docker compose exec -it php
APP_DIR :=
limit_arg := $(if $(limit), --limit=$(limit),)

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

lint: ## Проверить code style (Pint)
	$(EXEC_PHP) $(APP_DIR)vendor/bin/pint --test $(APP_DIR)app $(APP_DIR)tests
.PHONY: lint

fix-lint: ## Исправить code style (Pint)
	$(EXEC_PHP) $(APP_DIR)vendor/bin/pint $(APP_DIR)app $(APP_DIR)tests
.PHONY: fix-lint

artisan: ## Выполнить artisan команду / <make artisan cmd="migrate">
	docker compose exec php php artisan $(cmd)
.PHONY: artisan

##@ WB-api

wb-sync-stocks: ## Синхронизировать stocks из WB API
	$(MAKE) artisan cmd="wb:sync-stocks$(limit_arg)"
.PHONY: wb-sync-stocks

wb-sync-incomes: ## Синхронизировать incomes из WB API
	$(MAKE) artisan cmd="wb:sync-incomes$(limit_arg)"
.PHONY: wb-sync-incomes

wb-sync-sales: ## Синхронизировать sales из WB API
	$(MAKE) artisan cmd="wb:sync-sales$(limit_arg)"
.PHONY: wb-sync-sales

wb-sync-orders: ## Синхронизировать orders из WB API
	$(MAKE) artisan cmd="wb:sync-orders$(limit_arg)"
.PHONY: wb-sync-orders

##@ Monitoring

logs: ## Смотреть логи всех контейнеров
	docker compose logs -f
.PHONY: logs
