.PHONY: up down restart stop start exec logs build

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
