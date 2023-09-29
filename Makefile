composer:
	docker run -it --rm -v "${PWD}":/app -w /app composer --ignore-platform-reqs $(args)

build:
	docker build --tag random-things-php - < Dockerfile

run-dev:
	docker run -it --rm --name random-things-dev -e 8000:8000 -v "${PWD}":/app -w /app random-things-php

psalm:
	docker run -it --rm -v "${PWD}":/app -w /app random-things-php /app/vendor/bin/psalm

format:
	docker run -it --rm -v "${PWD}":/app -w /app random-things-php /app/vendor/bin/ecs --fix

prod-cache-clear:
	rm var/tmp/CompiledContainer.php
	@echo "cache cleared"