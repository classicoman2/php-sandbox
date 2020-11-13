# Proves PHP per Skills

Preparatori per curs Skills (Nov 2020).

En directori `/apps/` hi ha el codi PHP, que s'executa gràcies al servidor Servidor LAMP amb **Docker** que executat al directori. La imatge es troba aqui: https://hub.docker.com/r/mattrayner/lamp

Per llençar una màquina basada en 18.04 i de passada crear el contenidor,

## Launch a 18.04 based image

    docker run -p "80:80" -v ${PWD}/app:/app mattrayner/lamp:latest-1804

I ja està. Per tornar a engegar la màquina, 

    sudo docker start nomdelcontenidor  // En el cas actual, `priceless_sanderson`

# Dades servidor:

- Carpeta codi:     `app`
- Servidor:  		 localhost
- phpmyadmin         localhost/phpmyadmin   (admin,  IUNzDJXK4bKJ)


