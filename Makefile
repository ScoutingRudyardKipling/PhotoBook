# Misc
.DEFAULT_GOAL = help
.PHONY: backend/lang
MAKEFLAGS     += --no-print-directory

# Executables (local)
DOCKER_COMPOSE = docker compose -f docker-compose.yml -f docker-compose-helpers.yml --env-file .env
PHP_CONTAINER = $(DOCKER_COMPOSE) exec --user="www-data:www-data" fpm

## —— ⎈🎵 🐳 The PhotoBook Makefile 🐳 🎵⎈ ————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '^([\w\d/]+:)?([a-zA-Z0-9/_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS  = ":"}{printf "\033[32m%-25s\033[0m %s\n", $$2, $$3}' | sed -e 's/\[32m##/[33m/'

up: ## Start project
	./scripts/traefik.sh
	@$(DOCKER_COMPOSE) up --detach --force-recreate
	@cat info.txt

down: backend/down ## Stop project

sh: ## Connect to the PHP container with sh
	@$(PHP_CONTAINER) sh

phpunit: ## Run phpunit
	@$(PHP_CONTAINER) ./vendor/bin/phpunit

lint: ## Run linter
	@$(PHP_CONTAINER) composer lint

build-images: ## Build the docker images required by this project
	@$(DOCKER_COMPOSE) build

setup: build-images setup/configuration up setup/run-initial-commands up ## Setup and start the project

setup/configuration: ## Make sure this environment configuration is present
	cp .env.example .env

setup/run-initial-commands: ## Setup this environment after its first startup
	sleep 10
	@$(PHP_CONTAINER) php artisan key:generate
	@$(PHP_CONTAINER) php artisan migrate
	@$(PHP_CONTAINER) php artisan db:seed
