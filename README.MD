# Proves PHP per Skills

Preparatori per curs Skills (Nov 2020).

El directori públic és `/apps/`. Apache, MySQL i PHP 7.4.8 a servidor LAMP amb **Docker** obtingut de https://hub.docker.com/r/mattrayner/lamp

Per llençar la màquina virtual basada en 18.04 i crear el contenidor,

    ## Launch a 18.04 based image
    docker run -p "80:80" -v ${PWD}/app:/app mattrayner/lamp:latest-1804

Per tornar a engegar la màquina,

    sudo docker start nomdelcontenidor  // En el cas actual, `priceless_sanderson`

## Dades servidor:

- Carpeta codi:     `apps`
- Servidor:  		 localhost
- phpmyadmin         localhost/phpmyadmin   
  - Accés local: admin -  IUNzDJXK4bKJ
  
## Xdebug

He hagut d'afegir XDebug, ja que en cas d'error no apareixia res al viewport del navegador. 

Per instal·lar XDebug, inicia la maquina virtual i executa-la per poder accedir a dins,

    sudo docker start priceless_sanderson
    sudo docker exec -it priceless_sanderson /bin/bash

Ja dins la màquina, he seguit [les instruccions](https://stackoverflow.com/questions/53133005/how-to-install-xdebug-on-ubuntu) 

    sudo apt install php-xdebug
    nano /etc/php/7.4/mods-available/xdebug.ini

A dins, copiar i aferrar la config de xdebug:

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

La darrera línia és important, si no no mostrarà cap error. Per cert, aquesta opció és la més forçada, es pot afinar per a que mostri o no notices, warnings i errors, etc (per més endavant).

## Informació Branques

A la branch disponible hi ha una versió d'un programa de gestió d'incidències creat amb MVC (2014).



