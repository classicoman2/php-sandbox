## Servidor LAMP amb Docker per a proves PHP

https://hub.docker.com/r/mattrayner/lamp

Que he fet:

Primer de tot, seguint el tutorial,

# Launch a 18.04 based image
docker run -p "80:80" -v ${PWD}/app:/app mattrayner/lamp:latest-1804

--> En correr això, se me creen els directoris de feina que indica la documentació del hub de docker (enllaç inicial)


# docker ps -a

he trobat que la imatge se diu `priceless_sanderson`

Dades:

Servidor:  		 localhost
phpmyadmin -->   admin,  IUNzDJXK4bKJ


