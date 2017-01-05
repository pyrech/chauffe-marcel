
# HELP MENU
all: help
help:
	@echo ""
	@echo "# Help Menu"
	@echo ""
	@echo "   Need some help?"
	@echo ""
	@echo "   ## Installation"
	@echo ""
	@echo "   .  make install              - Install dependencies"
	@echo ""
	@echo "   ## Build"
	@echo ""
	@echo "   .  make build                - Build application"
	@echo "   .  make generate-api         - Generate API models from json schema"
	@echo ""
	@echo "   ## Run"
	@echo ""
	@echo "   .  make start                - Start the development server"
	@echo "   .  make stop                 - Stop the development server"
	@echo ""

install:
	@echo ""
	@echo "====================== Installing project dependencies ======================="
	@echo ""
	composer install

build:
	@echo ""
	@echo "====================== Building application ======================="
	@echo ""
	rm -rf var/cache/dev/ && rm -rf var/cache/prod/
	php bin/console assets:install

generate-api:
	@echo ""
	@echo "====================== Generating API ======================="
	@echo ""
	rm -rf generated/*
	./vendor/bin/jane generate ../config/schema.json ChauffeMarcel ChauffeMarcel\\Api generated/

start:
	@echo ""
	@echo "====================== Start server ======================="
	@echo ""
	php bin/console server:start

stop:
	@echo ""
	@echo "====================== Stop server ======================="
	@echo ""
	php bin/console server:stop
