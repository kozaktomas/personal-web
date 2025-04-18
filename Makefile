up:
	touch .env
	mkdir -p temp/cache
	mkdir -p temp/sessions
	mkdir -p log
	podman compose up -d php lb
	podman compose exec php composer install
	@echo "App is running on http://localhost:8081"

prerelease: test e2e upload-static

test:
	podman compose exec php php vendor/bin/tester -c tests/unit/php.ini tests/unit/
	podman compose exec php php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=256M -l max app

e2e:
	@echo "Running E2E tests"
	podman compose run e2e

e2e-local-open:
	@echo "npm needs to be installed"
	npm install --prefix tests/e2e
	node tests/e2e/node_modules/.bin/cypress open --env APP_BASE_URL=http://localhost:8081 -P tests/e2e/

e2e-local-run:
	@echo "npm needs to be installed"
	npm install --prefix tests/e2e
	node tests/e2e/node_modules/.bin/cypress run --env APP_BASE_URL=http://localhost:8081 -P tests/e2e/

cmd:
	podman compose up -d
	podman compose exec php bash

stop:
	podman compose stop

clean:
	podman compose exec php rm -rf temp/
	podman compose exec php rm -rf log/
	podman compose down

upload-static:
	rsync -auvz public root@prodvps:/root/apps/vps/static/static/kozak-in/
