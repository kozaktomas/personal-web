up:
	touch .env
	mkdir -p temp/cache
	mkdir -p temp/sessions
	mkdir -p log
	chmod -R 777 temp/
	chmod -R 777 log/
	docker-compose up -d
	docker-compose exec web composer install
	@echo "App is running on http://localhost:8081"

prerelease: upload-static test e2e

test:
	docker-compose exec php php vendor/bin/tester -c tests/unit/php.ini tests/unit/
	docker-compose exec php php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M -l max app

e2e:
	@echo "Running E2E tests"
	docker-compose run e2e

e2e-local-open:
	@echo "npm needs to be installed"
	npm install --prefix tests/e2e
	node tests/e2e/node_modules/.bin/cypress open --env APP_BASE_URL=http://localhost:8081 -P tests/e2e/

e2e-local-run:
	@echo "npm needs to be installed"
	npm install --prefix tests/e2e
	node tests/e2e/node_modules/.bin/cypress run --env APP_BASE_URL=http://localhost:8081 -P tests/e2e/

cmd:
	docker-compose up -d
	docker-compose exec web bash

stop:
	docker-compose stop

clean:
	docker-compose exec web rm -rf temp/
	docker-compose exec web rm -rf log/
	docker-compose down

upload-static:
	rsync -auvz public root@49.13.69.212:/var/www/html/static/kozak-in/