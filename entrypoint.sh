#!/bin/bash

# turn on bash's job control
set -m

# if any of the commands in your code fails for any reason, the entire script fails
set -o errexit

# fail exit if one of your pipe command fails
set -o pipefail

# exits if any of your variables is not set
set -o nounset

sleep 30

# Swagger docs generate
php /var/www/html/artisan swagger-lume:generate

# Run Pubsub Queue
php /var/www/html/artisan queue:listen rabbitmq --queue=Staging.WaitingListsMS --timeout=0

# now we bring the primary process back into the foreground and leave it there
fg %1
