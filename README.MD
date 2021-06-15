# PHP Sandbox
(Updated June/21)
El meu repositori amb projectes basats en PHP.

## Branques
- `cyclists-api` is a (kind of) Rest API developed with PHP, MySql, Apache
- `vue-cli` Front desenvolupat amb **Vue Cli**

## Start the server
```bash
docker start php-sandbox
```

## Access Data
- Carpeta amb codi (directori public): `app`
- Servidor: http://localhost
- phpmyadmin: http://localhost/phpmyadmin
  - User: admin
  - Password:  syPv4JseWHh4  (el password es regenera cada vegada que es fa `docker run`)

## Cyclists API
- Database name: `api_rest_php` (fitxer `.sql` a `/sql`)

### API

GET | All Cyclists | http://localhost/api/cyclists
GET | Cyclist by ID | http://localhost/api/cyclist/1

## Installation & Run
Apache, MySQL i PHP 7.4.8 a servidor LAMP amb **Docker** obtingut de https://hub.docker.com/r/mattrayner/lamp (la màquina virtual basada en 18.04)

Per llençar la mv:
```bash
## Launch a 18.04 based image
docker run -p "80:80" -v ${PWD}/app:/app mattrayner/lamp:latest-1804 -name php-sandbox
```

## Xdebug
Installed _XDebug_ in order to debug PHP code. Start the container and exec it to install xdebug:

```bash
docker start php-sandbox
docker exec -it php-sandbox /bin/bash
```

Following [these instruccions](https://stackoverflow.com/questions/53133005/how-to-install-xdebug-on-ubuntu)

```bash
apt install php-xdebug
apt install nano
nano /etc/php/7.4/mods-available/xdebug.ini
```

Copy these lines inside _xdebug.ini_:

```bash
zend_extension=xdebug.so
zend_extension=/usr/lib/php/20151012/xdebug.so
xdebug.remote_autostart = 1
xdebug.remote_enable = 1
xdebug.remote_handler = dbgp
xdebug.remote_host = 127.0.0.1
xdebug.remote_log = /tmp/xdebug_remote.log
xdebug.remote_mode = req
xdebug.remote_port = 9004 #if you want to change the port you can change
xdebug.force_display_errors = 1
```
Last line is very important: without it, errors won't be shown. There are other possible values to show or hide notices, warnings, errors...