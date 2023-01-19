.PHONY: help \
artisan-auth-create artisan-authorize-user \
artisan-fetch artisan-install artisan-key \
artisan-migrate composer-install config \
install npm-install npm-run-dev \
npm-watch shell tinker uninstall \
up upd stop

.DEFAULT_GOAL := help

PHP_CONTAINER := kremmania-admin-php-fpm
NODE_CONTAINER := node:19
NODE_VITE_PORT := 5173:5173

# Set dir of Makefile to a variable to use later
MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

NODE := docker run -u $(USER_ID):$(GROUP_ID) -it -v $(PWD):/application -w /application -p $(NODE_VITE_PORT) --rm node:19

help: ## * Show help (Default task)
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

config: ## Setup step #1: Create .env for the admin project
	cp -f .env.example .env
	cp -f keycloak.json.example public/keycloak.json
	sudo chmod -R 777 storage

composer-install: ## Setup step #2: Run composer install
	docker exec -it $(PHP_CONTAINER) composer install

artisan-key: ## Setup step #3.1: Run artisan key:generate
	docker exec -it $(PHP_CONTAINER) php artisan key:generate

artisan-migrate: ## Setup step #3.2: Run artisan migrate
	docker exec -it $(PHP_CONTAINER) php artisan migrate

artisan-auth-create: ## Setup step #3.3: Run artisan auth:create-roles-and-permissions
	docker exec -it $(PHP_CONTAINER) php artisan auth:create-roles-and-permissions

artisan-install: artisan-key artisan-migrate artisan-seed ## Setup step #3: Run initial artisan commands

artisan-authorize-user: ## Create admin user: make artisan-authorized-user ID={id-from-users-table}
	docker exec -it $(PHP_CONTAINER) php artisan auth:authorize-user --id=$(ID) super-admin

artisan-seed: ## Run php artisan seed
	docker exec -it $(PHP_CONTAINER) php artisan db:seed

npm-install: ## Setup step #4: Run npm install
	$(NODE) npm install

npm-dev: ## Setup step #5: Run npm run dev
	$(NODE) npm run dev

npm-build: ## Run npm run build
	$(NODE) npm run build

npm-lint-fix: ## Run npm run lint
	$(NODE) npm run lint-fix

npm-test: ## Run npm run test
	$(NODE) npm run test

npm-test-watch: ## Run npm run test in watch mode
	$(NODE) npm run test-watch

npm-test-coverage: ## Run npm run test coverage
	$(NODE) npm run coverage


install: config composer-install artisan-install npm-install ## Run the setup steps automatically

uninstall: ## Cleanup project by removing .env, PHP packages, node modules, files under the storage directory, etc.
	rm -f .env
	rm -f public/keycloak.json
	rm -f storage/logs/*.log
	rm -f storage/app/public/*
	rm -rf storage/framework/cache/data/*
	rm -rf vendor
	rm -rf node_modules

shell: ## Connect to the php container with a bash prompt
	docker exec -it $(PHP_CONTAINER) bash

tinker: ## Run Artisan Tinker
	docker exec -it $(PHP_CONTAINER) php artisan tinker

up: ## Run docker-compose up with
	cd ../km-main && docker-compose -p kremmania up
	cd -

upd: ## Run docker-compose up with -d
	cd ../km-main && docker-compose -p kremmania up -d
	cd -

stop: ## Stop docker containers
	cd ../km-main && docker-compose -p kremmania stop
	cd -

test: ## Run PHP tests
	docker exec -it $(PHP_CONTAINER) ./vendor/bin/phpunit --testdox --configuration phpunit.xml

dry-format: ## Check PHP format with Php-cs-fixer
	docker exec -it $(PHP_CONTAINER) composer dry-format

format: ## Format PHP with Php-cs-fixer
	docker exec -it $(PHP_CONTAINER) composer format

analyze: ## Check PHP format with Larastan
	docker exec -it $(PHP_CONTAINER) composer analyze

api-docs: ## Run php artisan l5-swagger:generate
	docker exec -it $(PHP_CONTAINER) php artisan l5-swagger:generate
