up:
	touch .env
	mkdir -p temp/cache
	mkdir -p temp/sessions
	mkdir -p log
	composer install
	docker-compose up -d
	@echo "App is running on http://localhost:8091"

prerelease: test e2e

test:
	docker-compose exec web php vendor/bin/tester -c tests/unit/php.ini tests/unit/
	docker-compose exec web php vendor/bin/phpstan analyse -c phpstan.neon -l max app

e2e: up
	@echo "Running E2E tests"
	docker-compose run e2e

e2e-local-open: up
	@echo "npm needs to be installed"
	npm install --prefix tests/e2e
	node tests/e2e/node_modules/.bin/cypress open --env APP_BASE_URL=http://localhost:8091 -P tests/e2e/

e2e-local-run: up
	@echo "npm needs to be installed"
	npm install --prefix tests/e2e
	node tests/e2e/node_modules/.bin/cypress run --env APP_BASE_URL=http://localhost:8091 -P tests/e2e/

cmd:
	docker-compose up -d
	docker-compose exec web bash

stop:
	docker-compose stop

clean:
	docker-compose down
	rm -rf temp/
	rm -rf log/