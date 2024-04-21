# Arena feedback form #

## Установка ##


Ставим пакеты:

```
sudo -s

apt update && apt upgrade

apt install nginx php7.4-bcmath php7.4-bz2 php7.4-cli php7.4-common php7.4-curl php7.4-dba php7.4-dev php7.4-enchant php7.4-fpm php7.4-gd php7.4-gmp php7.4-imap php7.4-intl php7.4-json php7.4-mbstring php7.4-mysql php7.4-opcache php7.4-phpdbg php7.4-pspell php7.4-readline php7.4-snmp php7.4-soap php7.4-sqlite3 php7.4-sybase php7.4-tidy php7.4-xml php7.4-xmlrpc php7.4-xsl php7.4-zip net-tools nginx mysql-server php-imagick php-memcache -y
```

Подготавливаем БД

```
mysql
CREATE DATABASE arenalan CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
```

Генерируем самоподписанные ssl сертификаты:

```
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt

openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
```

Толкаем параметры ssl в nginx (nano /etc/nginx/snippets/self-signed.conf):

```
ssl_certificate /etc/ssl/certs/nginx-selfsigned.crt;

ssl_certificate_key /etc/ssl/private/nginx-selfsigned.key;

ssl_protocols TLSv1.2;

ssl_prefer_server_ciphers on;

ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384;

ssl_session_timeout 10m;

ssl_session_cache shared:SSL:10m;

ssl_session_tickets off;

ssl_stapling on;

ssl_stapling_verify on;

#resolver 8.8.8.8 8.8.4.4 valid=300s;

#resolver_timeout 5s;

add_header X-Frame-Options DENY;

add_header X-Content-Type-Options nosniff;

add_header X-XSS-Protection "1; mode=block";

ssl_dhparam /etc/ssl/certs/dhparam.pem;

ssl_ecdh_curve secp384r1;

```

Подготавливаем БД

```
mysql
CREATE DATABASE arenalan CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
CREATE USER 'arenalandbadmin'@'localhost'IDENTIFIED WITH mysql_native_password BY 'password';
GRANT ALL PRIVILEGES ON arenalan . * TO 'arenalandbadmin'@'localhost';
flush privileges;
```

Распаковываем все файлы в /var/www и устанавливаем все права на www-data через chown www-data:www-data /var/www/arenafeedback -R

Заливаем БД:

```
cd /var/www/arenafeedback/db
mysql arenalan < arenalan.sql
```

Готовим конфиг nginx'a (nano /etc/nginx/sites-available/arena.lan):

```
server {
        listen 443 ssl;

        include snippets/self-signed.conf;

        root /var/www/arenafeedback/web/web;

        index index.php index.html index.htm index.nginx-debian.html;

        server_name arena.lan;

        location / {
                try_files $uri $uri/ /index.php?$args;
        }

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        }

        location ~ /\.ht {
                deny all;
        }
}


server {
    listen 80;
    listen [::]:80;

    server_name arena.lan;

    return 302 https://$server_name$request_uri;
}

```

Включаем конфиг:

```
cd /etc/nginx/sites-enabled/
rm default && ln -s ../sites-available/arena.lan arena.lan
```

## Настройка ##

Параметры подключения к бд в файле /var/www/arenafeedback/web/config/db.php

Пользователи в файле /var/www/arenafeedback/web/models/User.php , в массиве $users

Темы сообщений, настройки отсылки в телеграм, максимальный объём сохраняемых файлов находятся в файле /var/www/arenafeedback/web/config/params.php

Максимальная продолжительность звукового сообщения, битрейт и кодек в файле /var/www/arenafeedback/web/web/js/voiceform.js