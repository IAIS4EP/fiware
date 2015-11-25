## SpagoBI Docker

###SpagoBI 5.1 with MySQL

If not yet on your computer, install the [Docker Quickstart Terminal](https://docs.docker.com/). 

#### Build your image

Open your Docker Quickstart Terminal, travel to your folder containing the Dockerfile and her dependencies, and run the command
```bash
export SPAGOBI_CONTAINER_NAME=spagobi_container

docker build -t $SPAGOBI_CONTAINER_NAME .
```

#### Run a MySQL container to store the SpagoBI data
```bash
export MYSQL_IMAGE_NAME=spagobidb_image

export MYSQL_USER=spagobi_user
export MYSQL_PASSWORD=spagobi_password 
export MYSQL_DATABASE=spagobi_db 
export MYSQL_ROOT_PASSWORD=spagobi_root_password

docker run --name $MYSQL_IMAGE_NAME -e MYSQL_USER=$MYSQL_USER -e MYSQL_PASSWORD=$MYSQL_PASSWORD -e MYSQL_DATABASE=$MYSQL_DATABASE -e MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD -d mysql
```

#### Run your container

To start-up the docker container run the command. Mind the `-P` flag to open the ports and `--link` to connect to the MySQL container.
```bash
export SPAGOBI_IMAGE_NAME=spagobi_image
docker run --name $SPAGOBI_IMAGE_NAME --link $MYSQL_IMAGE_NAME:db -P $SPAGOBI_CONTAINER_NAME
```

Once the Terminal shows 
	INFO: Server startup in 301864 ms
one can execute the following steps.

#### Get your IP
- If you are running a Virtual Machine (on Mac OS for example)

Get the IP of the VM:
```bash
[~/ge_spagobi]$ docker-machine ls

NAME      ACTIVE   DRIVER       STATE     URL                         SWARM
default   *        virtualbox   Running   tcp://$THIS_IS_YOUR_IP:2376   
```

The IP address `$THIS_IS_YOUR_IP` is used to access the SpagoBI installation.

- If your computer runs already on Linux

Get the IP of the container:
```bash
docker inspect --format '{{ .NetworkSettings.IPAddress }}' spagobi
```

#### Get your PORT
```bash
[~/ge_spagobi]$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                                  NAMES
85a6629fbe32        $YOUR_IMAGE_NAME    "./entrypoint.sh ./st"   29 minutes ago      Up 29 minutes       0.0.0.0:$THIS_IS_YOUR_PORT->8080/tcp   fantastic_fiware
```

The port number `$THIS_IS_YOUR_PORT` is the port to access the SpagoBI installation.

#### Play with SpagoBI

Finally start your Webbrowser and go to `$THIS_IS_YOUR_IP:$THIS_IS_YOUR_PORT/SpagoBI` and you should be provided with the SpagoBI welcome screen.



The initial files {Dockerfile, entrypoint.sh and docker-compose.yml} were taken from https://github.com/SpagoBILabs/SpagoBI/tree/master/docker/5.1-fiware-all-in-one
http://spagobi.readthedocs.org/en/latest/docker/README/index.html#run-spagobi-with-mysql-database
