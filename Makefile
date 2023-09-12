run-dev:
	docker run -it --rm --name random-things-dev -e 8000:8000 -v "${PWD}":/app -w /app php:8.2-cli php -S0.0.0.0:8000 -tpublic
