## SpagoBI Docker

###SpagoBI 5.1 with MySQL

If not yet on your computer, install the [Docker Quickstart Terminal](https://docs.docker.com/). 

#### Build your image

Open your Docker Quickstart Terminal, travel to your folder containing the Dockerfile and her dependencies, and run the command
```
docker build -t $YOUR_IMAGE_NAME .
```

#### Run your container

To start-up the docker container run the command. Mind the `-P` flag to open the ports

```
docker run -P $YOUR_IMAGE_NAME
```

Once the Terminal shows 
	INFO: Server startup in 301864 ms
one can execute the following steps.

#### Get your IP
- If you are running a Virtual Machine (on Mac OS for example)

Get the IP of the VM:

```
[~/ge_spagobi]$ docker-machine ls

NAME      ACTIVE   DRIVER       STATE     URL                         SWARM
default   *        virtualbox   Running   tcp://$THIS_IS_YOUR_IP:2376   
```

The IP address `$THIS_IS_YOUR_IP` is used to access the SpagoBI installation.

- If your computer runs already on Linux

Get the IP of the container:
```
docker inspect --format '{{ .NetworkSettings.IPAddress }}' spagobi
```

#### Get your PORT

```
[~/ge_spagobi]$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                                  NAMES
85a6629fbe32        $YOUR_IMAGE_NAME        "./entrypoint.sh ./st"   29 minutes ago      Up 29 minutes       0.0.0.0:$THIS_IS_YOUR_PORT->8080/tcp   fantastic_fiware
```

The port number `$THIS_IS_YOUR_PORT` is the port to access the SpagoBI installation.

#### Play with SpagoBI

Finally start your Webbrowser and go to `$THIS_IS_YOUR_IP:$THIS_IS_YOUR_PORT/SpagoBI` and you should be provided with the SpagoBI welcome screen.



The initial files {Dockerfile, entrypoint.sh and docker-compose.yml} were taken from https://github.com/SpagoBILabs/SpagoBI/tree/master/docker/5.1-fiware-all-in-one
