#!/bin/sh
set -eu

listen_port="${PORT:-80}"
sed -ri "s/^Listen [0-9]+/Listen ${listen_port}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${listen_port}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
