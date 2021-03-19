vendor/autoload.php:
	./composer.phar install

test: vendor/autoload.php
	vendor/phpunit/phpunit/phpunit tests