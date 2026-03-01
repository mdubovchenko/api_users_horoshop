build: up composer-install migrate fixtures

up:
	docker compose up -d --build

down:
	docker compose down

composer-install:
	docker exec users_php composer install

bash:
	docker exec -it users_php bash

migrate:
	docker exec users_php php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	docker exec users_php php bin/console doctrine:fixtures:load --no-interaction
