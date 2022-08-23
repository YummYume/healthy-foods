DOCKER=docker
COMPOSE=docker-compose -f docker-compose.yml
EXECAPP=$(COMPOSE) exec app
EXECNGINX=$(COMPOSE) exec nginx
EXECREACT=$(COMPOSE) exec react
EXECSVELTE=$(COMPOSE) exec svelte

start: up perm vendor db cc sync-all

up:
	docker kill $$(docker ps -q) || true
	$(COMPOSE) build --force-rm
	$(COMPOSE) up -d

stop:
	$(COMPOSE) stop
	$(COMPOSE) kill

rm:
	make stop
	$(COMPOSE) rm

vendor: wait-for-db
	$(EXECAPP) composer install -n
	$(EXECAPP) yarn install --pure-lockfile
	make perm

ssh:
	$(EXECAPP) sh

ssh-react:
	$(EXECREACT) sh

ssh-svelte:
	$(EXECSVELTE) sh

db: wait-for-db
	$(EXECAPP) bin/console doctrine:database:drop --if-exists --force
	$(EXECAPP) bin/console doctrine:database:create --if-not-exists
	$(EXECAPP) bin/console doctrine:schema:update --force
	$(EXECAPP) bin/console doctrine:fixtures:load -n

perm:
ifeq ($(ENVIRONMENT),Linux)
	sudo chown -R www-data:$(USER) .
	sudo chmod -R g+rwx .
else
	$(EXECAPP) chown -R www-data:root .
	$(EXECAPP) chown -R www-data:root public/
endif

cc:
	$(EXECAPP) bin/console c:cl --no-warmup
	$(EXECAPP) bin/console c:warmup

sync-react-node-modules:
	@echo Syncing React node_modules dependencies...
ifeq ($(OS)$(SHELL),Windows_NTsh.exe)
	if exist react\node_modules rmdir react\node_modules /S /Q
else
	rm -rf react\node_modules
endif
	mkdir react\node_modules
	$(DOCKER) cp react-demo:/usr/src/app/node_modules ./react/
	@echo React node_modules dependencies synced!

sync-svelte-node-modules:
	@echo Syncing Svelte node_modules dependencies...
ifeq ($(OS)$(SHELL),Windows_NTsh.exe)
	if exist svelte\node_modules rmdir svelte\node_modules /S /Q
else
	rm -rf svelte\node_modules
endif
	mkdir svelte\node_modules
	$(DOCKER) cp svelte-demo:/usr/src/app/node_modules ./svelte/
	@echo Svelte node_modules dependencies synced!

sync-node-modules:
	@echo Syncing App node_modules dependencies...
ifeq ($(OS)$(SHELL),Windows_NTsh.exe)
	if exist symfony-inertia-svelte\node_modules rmdir symfony-inertia-svelte\node_modules /S /Q
else
	rm -rf symfony-inertia-svelte\node_modules
endif
	mkdir symfony-inertia-svelte\node_modules
	$(DOCKER) cp symfony-inertia-svelte-demo:/app/node_modules ./symfony-inertia-svelte/
	@echo App node_modules dependencies synced!

sync-all:
	@echo Syncing dependencies...
	make sync-react-node-modules
	make sync-svelte-node-modules
	make sync-node-modules
	@echo Dependencies synced!

wait-for-db:
	$(EXECAPP) php -r "set_time_limit(60);for(;;){if(@fsockopen(\"db\",3306)){break;}echo \"Waiting for DB\n\";sleep(1);}"

# PRODUCTION
update-prod:
	cd symfony-inertia-svelte && \
	composer require symfony/requirements-checker && \
	APP_ENV=prod APP_DEBUG=0 composer install --no-dev --optimize-autoloader && \
	php bin/console assets:install && \
	yarn install --production --no-progress && \
	yarn build && \
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear && \
	cd ../svelte && \
	yarn install --production --no-progress && \
	yarn build && \
	cd ../svelte && \
	yarn install --production --no-progress && \
	yarn build
