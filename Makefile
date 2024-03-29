#!/bin/bash

UID = $(shell id -u)
DOCKER_BE = php_twit_cqrs
DOCKER_NETWORK = myapp

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers in detached mode
	docker network create ${DOCKER_NETWORK} || true
	U_ID=${UID} docker-compose up -d

up: ## Start the containers in non-detached mode
	docker network create ${DOCKER_NETWORK} || true
	U_ID=${UID} docker-compose up

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	docker network create ${DOCKER_NETWORK} || true
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	$(MAKE) composer-install
	$(MAKE) migrations
	$(MAKE) migrations-test

run: ## starts the Symfony development server in detached mode
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony serve -d

logs: ## Show Symfony logs in real time
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:log

# Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction

.PHONY: migrations migrations-test worker
migrations: ## Run migrations for dev/prod environments
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:migration:migrate -n

migrations-test: ## Run migrations for test environments
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:migration:migrate -n --env=test

code-style:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/php-cs-fixer fix src --rules=@Symfony

code-style-check:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/php-cs-fixer fix src --rules=@Symfony --dry-run

worker: ## Run Symfony messenger worker
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} ./bin/console mess:con events_async commands_async projections_async async -vv
# End backend commands

ssh-be: ## bash into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

.PHONY: tests
tests:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/simple-phpunit -c phpunit.xml.dist


