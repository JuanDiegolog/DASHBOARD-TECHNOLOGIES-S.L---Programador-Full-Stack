.PHONY: up down restart build install test

up:
	docker-compose up -d

down:
	docker-compose down

restart: down up

build:
	docker-compose build

install:
	docker-compose exec php composer install

test:
	docker-compose exec php vendor/bin/phpunit

migrate:
	docker-compose exec php vendor/bin/doctrine orm:schema-tool:update --force

init: up install migrate 