install:
	composer install
setup:
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm ci
	npm run build
check:
	composer validate
	composer exec --verbose phpcs -- --standard=PSR12 routes app tests
	php artisan test
start:
	php artisan serve --host 0.0.0.0
seed:
	php artisan db:seed
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 app
test:
	php artisan test
dev:
	npm run dev
build:
	npm run build
test-coverage:
	mkdir -p build/logs
	XDEBUG_MODE=coverage php artisan test --coverage-clover build/logs/clover.xml || (echo "Coverage failed, report not generated"; exit 1)