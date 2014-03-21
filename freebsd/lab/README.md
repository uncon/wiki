# FreeBSD HTTP Lab
Use this index.php file on a [NGINX server](../nginx.md) with the following configuration.

1. Download PHP file.

		curl 'https://raw.githubusercontent.com/uncon/wiki/master/freebsd/lab/index.php' > /usr/local/www/nginx/index.php


1. Edit `/usr/local/etc/nginx/sites/default.conf`.

		server {
			limit_req zone=gulag burst=200 nodelay;
			expires max;

			listen 80;
			listen 443 ssl;
			include conf.d/ssl;

			location / {
				fastcgi_pass unix:/var/run/php-fpm.sock;
				fastcgi_param SCRIPT_FILENAME /usr/local/www/nginx/index.php;
				include fastcgi_params;
			}
		}

