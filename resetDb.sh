#!/bin/bash
# php yii migrate/down  << MIGRATE
# yes
# MIGRATE
# sleep 1
#
# php yii migrate/down --migrationPath=@vendor/ptech/yii2-pyrocms/migrations 4 << MIGRATE
# yes
# MIGRATE
# sleep 1

php yii migrate/up --migrationPath=@yii/log/migrations/ << MIGRATE
yes
MIGRATE
sleep 1

php yii migrate/up --migrationPath=@vendor/ptech/yii2-pyrocms/migrations << MIGRATE
yes
MIGRATE
sleep 1

php yii migrate << MIGRATE
yes
MIGRATE
sleep 1

php yii setup/migrate
