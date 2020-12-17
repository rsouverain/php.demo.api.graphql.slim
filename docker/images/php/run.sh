#!/bin/sh

cd /project && composer install && php -S 0.0.0.0:9632 -t /project/app/public /project/app/public/index.php
