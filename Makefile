.PHONY: composer build run-dev psalm format prod-cache-clear

composer:
	docker run -it --rm -v "${PWD}":/app -w /app composer --ignore-platform-reqs $(args)

build:
	docker build --tag random-things-php - < Dockerfile

run-dev:
	docker run -it --rm --name random-things-dev -e 8000:8000 -v "${PWD}":/app -w /app random-things-php

run-dev-cli:
	docker run -it --rm --name random-things-dev-cli -e 8000:8000 -v "${PWD}":/app -w /app random-things-php bash

psalm:
	docker run -it --rm -v "${PWD}":/app -w /app random-things-php /app/vendor/bin/psalm

format:
	docker run -it --rm -v "${PWD}":/app -w /app random-things-php /app/vendor/bin/ecs --fix

prod:
	rm var/tmp/CompiledContainer.php
	php83 cli.php migrate

prod-cache-clear:
	rm var/tmp/CompiledContainer.php
	@echo "cache cleared"