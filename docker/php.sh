#!/bin/sh

cat $8 | docker compose exec app php -l
