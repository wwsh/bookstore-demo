run:
	docker-compose build
	docker-compose up dashboard.composer
	docker-compose up api.composer
	docker-compose up -d
	docker-compose exec api php bin/console doctrine:schema:create
	docker-compose exec api php bin/console doctrine:fixtures:load -q
	docker-compose exec dashboard yarn install
	docker-compose exec dashboard yarn dev

stop:
	docker-compose stop

reset:
	docker-compose down -v
