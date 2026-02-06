.PHONY: up down restart stop start exec logs build tools-install phpstan fix test

DC = docker-compose

.DEFAULT_GOAL := help

help:
	@echo "Available commands:"
	@echo ""
	@echo "  make up         - Start all containers"
	@echo "  make down       - Stop and remove containers"
	@echo "  make stop       - Stop containers (without removing)"
	@echo "  make start      - Start stopped containers"
	@echo "  make restart    - Restart containers"
	@echo "  make exec       - Enter application container"
	@echo "  make logs       - Show logs from all containers"
	@echo "  make build      - Build containers from scratch"
	@echo "  make tools-install - Install development tools from .tools/"
	@echo "  make phpstan       - Run PHPStan static analysis"
	@echo "  make fix        - Fix code style with PHP-CS-Fixer"
	@echo "  make test       - Run phpunit tests"

up:
	$(DC) up -d

down:
	$(DC) down

stop:
	$(DC) stop

start:
	$(DC) start

restart:
	$(DC) restart

exec:
	$(DC) exec app bash

logs:
	$(DC) logs -f

build:
	$(DC) build

tools-install:
	$(DC) exec app composer install --working-dir=.tools

phpstan:
	$(DC) exec app .tools/vendor/bin/phpstan analyse -l 6 src tests

fix:
	$(DC) exec app .tools/vendor/bin/php-cs-fixer fix --allow-risky=yes

test:
	$(DC) exec app vendor/bin/phpunit
