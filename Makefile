up:
	touch .env
	mkdir -p temp/cache
	mkdir -p temp/sessions
	mkdir -p log
	composer install
	docker-compose up -d
	@echo "App is running on http://localhost:8091"

stop:
	docker-compose stop

clean:
	docker-compose down
	rm -rf temp/
	rm -rf log/