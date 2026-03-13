##################
#      GIT       #
##################

git_clear:
	git branch --merged | egrep -v "(master|release|main)" | xargs git branch -d

git_projects_pull:
	find ./projects/ -mindepth 1 -maxdepth 1 -type d -print -exec git -C {} pull \;

##################
# DOCKER COMPOSE #
##################
DC = docker compose
DC_DOWN = ${DC} down
DC_UP = ${DC} up -d --build
DC_RESTART = ${DC_DOWN} && ${DC_UP}

dc_restart:
	${DC_RESTART}

dc_setup:
	${DC_UP}
	${DC} exec php composer install
	${DC} exec php ./vendor/bin/phinx migrate ${PHINX_CONF}

##################
#  CODE QUALITY  #
##################
phpcs:
	${DC} exec php ./vendor/bin/phpcs -n -p --no-cache
phpcbf:
	${DC} exec php ./vendor/bin/phpcbf -n -p
phpstan:
	${DC} exec php ./vendor/bin/phpstan analyse -l 5 src
rector:
	${DC} exec php ./vendor/bin/rector src --dry-run
rectorbf:
	${DC} exec php ./vendor/bin/rector src
code_quality:
	make phpcs phpstan rector
##################
#    MIGRATIONS   #
##################
PHINX = ${DC} exec php ./vendor/bin/phinx
PHINX_CONF = -c phinx/dts/phinx.php

phinx_create:
	${PHINX} create $(name) ${PHINX_CONF}

phinx_migrate:
	${PHINX} migrate ${PHINX_CONF}

phinx_rollback:
	${PHINX} rollback ${PHINX_CONF}

##################
#    TESTING     #
##################
test:
	${DC} exec php ./vendor/bin/phpunit

test_filter:
	${DC} exec php ./vendor/bin/phpunit --filter $(filter)

##################
#      APP       #
##################
app_run:
	${DC} exec php php cli.php
