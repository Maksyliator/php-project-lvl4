start:
	php artisan serve

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm install
	npm run prod

lint:
	composer phpcs

lint-fix:
	composer phpcbf

test:
	composer exec --verbose phpunit tests

test-text:
	composer exec --verbose phpunit tests -- --coverage-text

test-coverage:
	vendor/bin/phpunit --coverage-clover coverage.xml

deploy:
	git push heroku
