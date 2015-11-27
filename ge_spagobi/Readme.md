## SpagoBI Docker
The idea of SpagoBI is to provide users with insights and metrics using data from different databases. Unfortunately, SpagoBI lacks of usability for the setup of the Web service and the integration of different data sets. The aim of this document is to provide a formula to create an instance of SpagoBI in a Docker container with predefined data sources and visualisations. 
We will first create Docker containers for SpagoBI and a MySQL database. After setting up the different data sources and visualisations, we will extract the relevant information from the database and integrate it into the Docker files. 
These files can then be deployed to different servers and provide the pre-configured interface. 

### SpagoBI 5.1 with MySQL

If not yet on your computer, install the [Docker Quickstart Terminal](https://docs.docker.com/). 

#### Check Out the FIWARE Repository

```bash
[~]$ git clone https://github.com/IAIS4EP/fiware.git
```

and check the folder that contains the SpagoBI Docker files.

```bash
[~]$ cd fiware/ge_spaogbi
[ge_spagobi]$ ls -la
    Dockerfile
    Readme.md
    docker-compose.yml 
    entrypoint.sh
    smoketest.sh
    MySQL_custom_setup.sql
```

#### Build the SpagoBI Image from the Dockerfile

Open the *Docker Quickstart Terminal*, go to the folder containing the Dockerfile and it's dependencies, and run the command
```bash
[ge_spagobi]$ export SPAGOBI_CONTAINER_NAME=spagobi_container 
[ge_spagobi]$ docker build -t $SPAGOBI_CONTAINER_NAME .
```

#### Run a MySQL Container for the SpagoBI Data

```bash
[ge_spagobi]$ export MYSQL_IMAGE_NAME=spagobidb_image
[ge_spagobi]$ export MYSQL_USER=spagobi_user
[ge_spagobi]$ export MYSQL_PASSWORD=spagobi_password 
[ge_spagobi]$ export MYSQL_DATABASE=spagobi_db 
[ge_spagobi]$ export MYSQL_ROOT_PASSWORD=spagobi_root_password

[ge_spagobi]$ docker run --name ${MYSQL_IMAGE_NAME} -e MYSQL_USER=${MYSQL_USER} -e MYSQL_PASSWORD=${MYSQL_PASSWORD} -e MYSQL_DATABASE=${MYSQL_DATABASE} -e MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD} -d mysql
```

#### Run the SpagoBI Container

Mind the `-P` flag to open the ports and `--link` to connect to the MySQL container.

```bash
[ge_spagobi]$ docker run --link ${MYSQL_IMAGE_NAME}:db -P ${SPAGOBI_CONTAINER_NAME}
```

Once the Terminal shows 
	INFO: Server startup in 301864 ms
proceed with the next steps.

#### Get the Access Point

- If you are running a Virtual Machine (on Mac OS for example)

Get the IP of the VM:
```bash
[ge_spagobi]$ docker-machine ls

NAME      ACTIVE   DRIVER       STATE     URL                         SWARM
default   *        virtualbox   Running   tcp://THIS_IS_YOUR_IP:2376   
```

The IP address `THIS_IS_YOUR_IP` is used to access the SpagoBI installation.

- If your computer runs already on Linux

Get the IP of the container:
```bash
[ge_spagobi]$ docker inspect --format '{{ .NetworkSettings.IPAddress }}' spagobi
```

Get the Port of the VM:
```bash
[ge_spagobi]$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                                  NAMES
85a6629fbe32        $YOUR_IMAGE_NAME    "./entrypoint.sh ./st"   29 minutes ago      Up 29 minutes       0.0.0.0:THIS_IS_YOUR_PORT->8080/tcp    fantastic_fiware
```

The port number `THIS_IS_YOUR_PORT` is the port to access the SpagoBI installation. 

You can now access the running SpagoBI under the URL `THIS_IS_YOUR_IP:THIS_IS_YOUR_PORT/SpagoBI` in your Web browser.

#### Configure the SpagoBI Interface

Start the Web browser, go to `THIS_IS_YOUR_IP:THIS_IS_YOUR_PORT/SpagoBI` and you should be provided with the SpagoBI welcome screen. Now you can log in using the Administrator account (biadmin/biadmin) and create data sources, data sets and different metrics or visualisations. For a detailed description please refer to the [Wiki](http://wiki.spagobi.org/).

#### Dump the Database

```bash
[ge_spagobi]$ docker exec -i ${MYSQL_IMAGE_NAME} mysqldump ${MYSQL_DATABASE} -u${MYSQL_USER} -p${MYSQL_PASSWORD} > MySQL_custom_setup.sql
```

Keep in mind that there might be sensitive data (user credentials to access MySQL databases) in the file *MySQL_custom_setup.sql*. So don't add it to a public repository or make it publicly available.






The initial files {Dockerfile, entrypoint.sh and docker-compose.yml} were taken from https://github.com/SpagoBILabs/SpagoBI/tree/master/docker/5.1-fiware-all-in-one
