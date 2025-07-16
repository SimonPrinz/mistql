DC = docker compose

start:
	$(DC) up -d
stop:
	$(DC) down

rebuild: stop
	$(DC) up -d --build

exec: start
	$(DC) exec -it php bash

test: start
	$(DC) exec -it php composer run test
