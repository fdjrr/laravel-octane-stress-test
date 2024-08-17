# Laravel Octane Example Stress Test

## Installation :

```bash
$ git clone https://github.com/fdjrr/laravel-octane-stress-test
$ cd laravel-octane-stress-test
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```

```bash
$ php artisan octane:install
```

Note: Change env variable `OCTANE_SERVER` to frankenphp, roadrunner or swoole.

## Configuration

Note: If you test using Nginx + PHP-FPM use this nginx conf.

```bash
$ sudo nano /etc/nginx/sites-available/laravel
```

Paste it.

```bash
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /srv/example.com/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
$ sudo ln -s /etc/nginx/sites-available/nginx /etc/nginx/sites-enable/nginx
$ sudo nginx -t
$ sudo systemctl reload nginx
```

Note: If you want using Swoole or OpenSwoole install extension

```bash
$ sudo pecl install swoole
$ sudo pecl install openswoole
```

Add `extension=swoole.so` or `extension=openswoole.so` on your `/etc/php/8.3/cli/php.ini`

## Running Stress Test :

```bash
$ /vendor/bin/pest stress http://127.0.0.1:8000/api/v1/posts --concurrency=10 --duration=60
```

## Comparison & Result

| Server          | Request per Second | Total Request |
| --------------- | ------------------ | ------------- |
| Native          | 22.61 reqs/s       | 1368 requests |
| Nginx & PHP-FPM | 109.61 reqs/s      | 6586 requests |
| FrankenPHP      | 89.21 reqs/s       | 5358 requests |
| RoadRunner      | 116.85 reqs/s      | 7017 requests |
| Swoole          | 157.3 reqs/s       | 9444 requests |
| Open Swoole     | 153.64 reqs/s      | 9226 requests |
