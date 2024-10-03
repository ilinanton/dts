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

##################
#  CODE QUALITY  #
##################
phpcs:
	${DC} exec php ./vendor/bin/phpcs -n -p --no-cache
phpcbf:
	${DC} exec php ./vendor/bin/phpcbf -n -p

##################
#      APP       #
##################
app_run:
	${DC} exec php php cli.php
