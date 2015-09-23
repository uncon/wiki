# Installing NGINX and PHP-FPM on FreeBSD

## PHP-FPM

1. Install PHP and extensions.

		pkg install php56 php56-extensions

1. Configure PHP.

		cp /usr/local/etc/php.ini-development /usr/local/etc/php.ini
		sed -i.orig -E -e 's/^(listen = ).*$/\1\/var\/run\/php-fpm.sock/' -e 's/^;(listen\.(owner|group|mode) )/\1/g' /usr/local/etc/php-fpm.conf
		sed -i.orig -e 's_^;\(date\.timezone =\)_\1 \"America/Chicago\"_' /usr/local/etc/php.ini
		
1. Enable PHP-FPM.

		printf 'php_fpm_enable="YES"\n' >> /etc/rc.conf
		
1. Start PHP-FPM.

		service php-fpm start

## NGINX

1. Install NGINX.

		pkg install nginx
		
1. Create NGINX root.

		rm /usr/local/www/nginx
		mkdir /usr/local/www/nginx
		echo '<?php phpinfo(); ?>' > /usr/local/www/nginx/index.php
				
1. Configure NGINX.

	1. Backup default configuration.

			mv /usr/local/etc/nginx/nginx.conf /usr/local/etc/nginx/nginx.conf.orig

	1. Edit `/usr/local/etc/nginx/nginx.conf`.

			worker_processes 1;
			worker_priority 15;

			error_log /var/log/nginx/error.log info;

			events {
				worker_connections  512;
				accept_mutex on;
				use kqueue;
			}

			http {
				include conf.d/options;
				include mime.types;
				default_type application/octet-stream;
				access_log /var/log/nginx/access.log main buffer=32k;
				include sites/*.conf;
			}

	1. Create paths.

			mkdir /usr/local/etc/nginx/conf.d /usr/local/etc/nginx/sites /var/log/nginx/
			
	1. Edit `/usr/local/etc/nginx/conf.d/options` with global options.

			client_body_timeout  5s;
			client_header_timeout  5s;
			keepalive_timeout  75s;
			send_timeout  15s;
			charset  utf-8;
			gzip  off;
			gzip_proxied  any;
			ignore_invalid_headers  on;
			keepalive_requests  50;
			keepalive_disable  none;
			max_ranges  1;
			msie_padding  off;
			open_file_cache  max=1000 inactive=2h;
			open_file_cache_errors  on;
			open_file_cache_min_uses  1;
			open_file_cache_valid  1h;
			output_buffers  1 512;
			postpone_output  1440;
			read_ahead  512K;
			recursive_error_pages  on;
			reset_timedout_connection  on;
			sendfile  on;
			server_tokens  off;
			server_name_in_redirect  off;
			source_charset  utf-8;
			tcp_nodelay  on;
			tcp_nopush  off;
			gzip_disable  "MSIE [1-6]\.(?!.*SV1)";
			limit_req_zone  $binary_remote_addr  zone=gulag:1m   rate=60r/m;
			log_format  main  '$remote_addr $host $remote_user [$time_local] "$request" $status $body_bytes_sent "$http_referer" "$http_user_agent" $ssl_cipher $request_time';

	1. Edit `/usr/local/etc/nginx/sites/default.conf` with the options for the default site.

			server {
				server_name domain.tld www.domain.tld;
				limit_req zone=gulag burst=200 nodelay;
				expires max;
			 
				listen 80;
				listen 443 ssl;
				include conf.d/ssl;
			 
				root /usr/local/www/nginx;
				index index.html index.htm index.php;
			 
				location ~ /\. {
					deny all;
					access_log off;
					log_not_found off;
				}
			 
				location / { 
					root /usr/local/www/nginx;
					try_files $uri $uri/ /index.php?$args;
			 		location ~ \.php$ { include conf.d/php-fpm; }
				}
			 			 
				location ~ \.php$ { include conf.d/php-fpm; }
			 
				error_page   500 502 503 504  /50x.html;
				location = /50x.html { root   /usr/local/www/nginx-dist; }
			}
			
	1. Edit `/usr/local/etc/nginx/conf.d/ssl` with the SSL options.

			ssl_certificate /usr/local/etc/nginx/ssl/self-signed.cert;
			ssl_certificate_key /usr/local/etc/nginx/ssl/self-signed.key;
			ssl_protocols SSLv3 TLSv1 TLSv1.1 TLSv1.2;
			ssl_ciphers EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:EECDH+RC4:RSA+RC4:!MD5;
			ssl_prefer_server_ciphers   on;

			
	1. Create self-signed SSL certificate.

		1. Generate the private key.

				mkdir /usr/local/etc/nginx/ssl
				openssl genrsa -out /usr/local/etc/nginx/ssl/self-signed.key 2048
				
		1. Create the certificate signing request.

				openssl req -new -key /usr/local/etc/nginx/ssl/self-signed.key -out /usr/local/etc/nginx/ssl/self-signed.req -sha256
				
		1. Generate the Certificate.

				openssl x509 -req -days 3650 -in /usr/local/etc/nginx/ssl/self-signed.req -signkey /usr/local/etc/nginx/ssl/self-signed.key -out /usr/local/etc/nginx/ssl/self-signed.cert -sha256

	1. Edit `/usr/local/etc/nginx/conf.d/php-fpm` with the PHP-FPM options.

			fastcgi_pass unix:/var/run/php-fpm.sock;
			fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
			try_files  $uri = 404;
			fastcgi_split_path_info  ^(.+\.php)(.*)$;
			fastcgi_index  index.php;
			fastcgi_intercept_errors  on;
			fastcgi_ignore_client_abort  off;
			fastcgi_connect_timeout  60;
			fastcgi_send_timeout  180;
			fastcgi_read_timeout  180;
			fastcgi_buffer_size  128k;
			fastcgi_buffers  4 256k;
			fastcgi_busy_buffers_size  256k;
			fastcgi_temp_file_write_size  256k;
			include fastcgi_params;

1. Enable NGINX.

		printf 'nginx_enable="YES"\n' >> /etc/rc.conf
		
1. Start NGINX.

		service nginx start
