ifneq (,$(wildcard ./.env))
	include .env
	export
endif

run: up

up:
	docker compose \
        --project-name=ride-tech \
        --env-file=.env \
		-f .docker/docker-compose.yml \
		up  -d --remove-orphans

down:
	docker compose \
        --project-name=ride-tech \
        --env-file=.env \
		-f .docker/docker-compose.yml \
		down

stop:
	docker compose \
        --project-name=ride-tech \
        --env-file=.env \
		-f .docker/docker-compose.yml \
		stop

composer:
	docker exec -t ride-tech-app bash -c 'COMPOSER_MEMORY_LIMIT=-1 yes | composer install && php artisan optimize:clear && composer dump-autoload'

migration:
	docker exec -t ride-tech-app bash -c 'php artisan migrate'

seed:
	docker exec -t ride-tech-app bash -c 'php artisan db:seed'

permission:
	docker exec -t ride-tech-app bash -c 'php artisan feel-roles-with-permission'

test:
	docker exec -t ride-tech-app bash -c 'php artisan test'

prune:
	docker exec -t ride-tech-app bash -c 'php artisan telescope:prune'

clear:
	docker exec -t ride-tech-app bash -c 'php artisan optimize:clear'
