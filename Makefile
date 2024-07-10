setup:
	@make build
	@make start
	@make app-composer-update

build:
	docker-compose build --no-cache --force-rm

stop:
	docker-compose stop

start:
	docker-compose up -d

app-exec:
	docker exec -it amp-app bash

app-generate-key:
	docker exec amp-app bash -c "php artisan key:generate"

app-start:
	docker exec amp-app bash -c "php artisan serve"

app-composer-update:
	docker exec amp-app bash -c "composer update"

app-migrate:
	docker exec amp-app bash -c "php artisan migrate"

app-seed:
	docker exec amp-app bash -c "php artisan db:seed"