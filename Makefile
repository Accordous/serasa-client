p=

composer-install:
	docker run --rm --user 1000 -v $(PWD):/app composer/composer:latest install

phpunit:
	docker run --rm --user 1000 -v $(PWD):/app -w /app php:cli php vendor/bin/phpunit ${p}