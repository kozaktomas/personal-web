up:
	touch .env
	mkdir -p temp/cache
	mkdir -p temp/sessions
	mkdir -p log
	composer install
	docker-compose up -d
	@echo "App is running on http://localhost:8091"

test:
	docker-compose up -d
	docker-compose exec web php vendor/bin/tester -c tests/php.ini tests/
	docker-compose exec web php vendor/bin/phpstan analyse -c phpstan.neon -l max app

cmd:
	docker-compose up -d
	docker-compose exec web bash

stop:
	docker-compose stop

clean:
	docker-compose down
	rm -rf temp/
	rm -rf log/