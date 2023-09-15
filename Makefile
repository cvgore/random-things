run-dev:
	docker run -it --rm --name random-things-dev -e 8000:8000 -v "${PWD}":/app -w /app php:8.2-cli php -S0.0.0.0:8000 -tpublic

psalm:
	docker run -it --rm -v "${PWD}":/app -w /app php:8.2-cli /app/vendor/bin/psalm

format:
	docker run -it --rm -v "${PWD}":/app -w /app php:8.2-cli /app/vendor/bin/ecs --fix

prod-cache-clear:
	rm var/tmp/CompiledContainer.php
	echo "cache cleared"