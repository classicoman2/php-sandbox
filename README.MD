# PHP Sandbox
(Actualitzat el juny 2021)
El meu repositori amb projectes basats en PHP.

## Branques
- `devRESTapi` conté la API REST en **PHP**
- `vue-cli` conté els canvis en el front desenvolupat amb **Vue Cli**

## Start the server
```bash
# Al PC
docker start priceless_sanderson
# Al portàtil
docker start silly_torvalds
```

## Access Data
- Carpeta amb codi (directori public): `app`
- Servidor: http://localhost
- phpmyadmin: http://localhost/phpmyadmin
  - User: admin ('root' al portatil)
  - Password:  IUNzDJXK4bKJ  

## API Rest
- Database name: `api_rest_php` (fitxer `.sql` a `/sql`)

## Installation & Run
Apache, MySQL i PHP 7.4.8 a servidor LAMP amb **Docker** obtingut de https://hub.docker.com/r/mattrayner/lamp (la màquina virtual basada en 18.04)

Per llençar la mv:
```bash
## Launch a 18.04 based image
docker run -p "80:80" -v ${PWD}/app:/app mattrayner/lamp:latest-1804
```

Per tornar a engegar la màquina
```bash
# Al PC
docker start priceless_sanderson
# Al portàtil
docker start silly_torvalds
```

## Xdebug
He hagut d'afegir XDebug, perquè en cas d'error no apareixia res al viewport del navegador.

Per instal·lar XDebug, inicia la maquina virtual i executa-la per poder accedir a dins:

```bash
docker start priceless_sanderson
docker exec -it priceless_sanderson /bin/bash
```

Ja dins la màquina, he seguit [les instruccions](https://stackoverflow.com/questions/53133005/how-to-install-xdebug-on-ubuntu)

```bash
sudo apt install php-xdebug
nano /etc/php/7.4/mods-available/xdebug.ini
```

A dins, copiar i aferrar la config de xdebug:

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
La darrera línia és important, si no no mostrarà cap error. Per cert, aquesta opció és la més forçada, es pot afinar per a que mostri o no notices, warnings i errors, etc (per més endavant).