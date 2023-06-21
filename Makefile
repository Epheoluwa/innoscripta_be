setup:
	@make build
	@make up 
	@make composer-update
build:
	docker-compose build --no-cache --force-rm
stop:
	docker-compose stop
up:
	docker-compose up -d
composer-update:
	docker exec innoscripta-be bash -c "composer update"
migration:
	docker exec innoscripta-be bash -c "php artisan migrate"
