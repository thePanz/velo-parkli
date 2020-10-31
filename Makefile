.PHONY: fix-perm
fix-perm:
	docker-compose run --rm php chown -R $$(id -u):$$(id -g) .

.PHONY: docker-shell
docker-shell:
	docker-compose exec php /bin/sh

.PHONY: docker-rebuild
docker-build:
	docker-compose build --pull

.PHONY: docker-up
docker-up:
	docker-compose up

.PHONY: docker-up-daemon
docker-up-daemon:
	docker-compose up -d

.PHONY: docker-down
docker-down:
	docker-compose down
