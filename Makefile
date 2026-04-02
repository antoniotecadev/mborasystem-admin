SHELL := /usr/bin/env bash

.PHONY: all doctor services-up bootstrap up serve test test-coverage help

all: up

doctor:
	@./bin/doctor.sh

services-up:
	@./bin/services-up.sh

bootstrap:
	@./bin/bootstrap.sh

up:
	@./bin/up.sh

serve:
	@php artisan serve

test:
	@php ./vendor/bin/phpunit --testdox

test-coverage:
	@php ./vendor/bin/phpunit --coverage-html storage/logs/coverage --testdox

help:
	@echo "Targets disponíveis:"
	@echo "  make doctor         - valida o ambiente local"
	@echo "  make services-up    - sobe MariaDB e phpMyAdmin"
	@echo "  make bootstrap      - instala deps, migra e faz seed"
	@echo "  make up             - executa doctor + services-up + bootstrap"
	@echo "  make serve          - inicia Laravel em modo local"
	@echo "  make test           - corre os testes"
	@echo "  make test-coverage  - corre testes com coverage"
