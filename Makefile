DOCKER_COMPOSE=USER_ID=$(getID) GROUP_ID=$(getGroup) USERNAME=www-data docker-compose

getID := $(shell id -u)
getGroup := $(shell id -g)

composer-install:
	$(DOCKER_COMPOSE) exec php composer install --no-scripts --no-progress --no-interaction && composer check-platform-reqs

docker-shell:
	$(DOCKER_COMPOSE) exec php sh

docker-start:
	$(DOCKER_COMPOSE) up -d --build

docker-down:
	$(DOCKER_COMPOSE) down --remove-orphans

php-cs:
	$(DOCKER_COMPOSE) exec php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -v --allow-risky=yes --using-cache=no

php-stan:
	$(DOCKER_COMPOSE) exec php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=-1

unit-test:
	$(DOCKER_COMPOSE) exec php vendor/bin/phpunit --testsuite=unit

integration-test:
	$(DOCKER_COMPOSE) exec php vendor/bin/phpunit --testsuite=integration

run-test: unit-test integration-test

run-migration:
	$(DOCKER_COMPOSE) exec php bin/console doctrine:database:drop --if-exists --force
	$(DOCKER_COMPOSE) exec php bin/console doctrine:database:drop --if-exists --force --env=test
	$(DOCKER_COMPOSE) exec php bin/console doctrine:database:create --if-not-exists
	$(DOCKER_COMPOSE) exec php bin/console doctrine:database:create --if-not-exists --env=test
	$(DOCKER_COMPOSE) exec php bin/console doctrine:migrations:migrate --no-interaction
	$(DOCKER_COMPOSE) exec php bin/console doctrine:migrations:migrate --env=test --no-interaction

build-dev: docker-start composer-install run-migration
