## SpagoBI Docker with MySQL Support
The idea of SpagoBI is to provide users with insights and metrics using data from different databases. Unfortunately, SpagoBI lacks of usability for the setup of the Web service and the integration of different data sets.
The aim of this document is to create and configure an instance of SpagoBI that can be easily deployed to different servers or ran by other users.

We will first set up Docker containers for SpagoBI and a MySQL database to store the necessary data. After adding all the different data sources, visualizations and metrics, we will extract the relevant information from the database and integrate it into the Docker files. These files can then be deployed to different servers or ran by other users to provide the pre-configured interface.

### 0. Requirements

If not yet on your computer, install the [Docker Quickstart Terminal](https://docs.docker.com/).

### 1. Check Out the FIWARE Repository

```bash
[~]$ git clone https://github.com/IAIS4EP/fiware.git
```

and check the folder that contains the SpagoBI Docker files.

```bash
[~]$ cd fiware/ge_spagobi
[ge_spagobi]$ ls -la
    Dockerfile
    Readme.md
    docker-compose.yml
    entrypoint.sh
    smoketest.sh
    MySQL_custom_setup.sql.gz
    external_db
        docker-compose.yml
        docker
            data
                Dockerfile
                MySQL_external_db_dump.sql
```

### 2. Build the SpagoBI Image from the Dockerfile

Open the *Docker Quickstart Terminal*, go to the folder containing the Dockerfile and it's dependencies, and run the command
```bash
[ge_spagobi]$ export SPAGOBI_CONTAINER_NAME=spagobi_container
[ge_spagobi]$ docker build -t $SPAGOBI_CONTAINER_NAME .
```

### 3. Run a MySQL Container for the SpagoBI Data

```bash
[ge_spagobi]$ export MYSQL_IMAGE_NAME=spagobidb_image
[ge_spagobi]$ export MYSQL_USER=spagobi_user
[ge_spagobi]$ export MYSQL_PASSWORD=spagobi_password
[ge_spagobi]$ export MYSQL_DATABASE=spagobi_db
[ge_spagobi]$ export MYSQL_ROOT_PASSWORD=spagobi_root_password

[ge_spagobi]$ docker run --name ${MYSQL_IMAGE_NAME} -e MYSQL_USER=${MYSQL_USER} -e MYSQL_PASSWORD=${MYSQL_PASSWORD} -e MYSQL_DATABASE=${MYSQL_DATABASE} -e MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD} -d mysql
```

### 4. Run a MySQL Container

This container will contain a MySQL populated database and tables you can then use in SpagoBI as a datasource.
To run the MySQL container, travel until the external_db directory and launch the following command :

```bash
[external_db]$ docker-compose up
```

Informations you may need to access this external database:
- The port is 3306
- The IP address is the virtual machine one. To obtain it please refer to the step 6.
- The user and password you can use are both "root" and "root".

### 5. Run the SpagoBI Container linked to the two databases

Mind the `-P` flag to open the ports and `--link` to connect to the MySQL containers.

Know first the name of your external database give by docker-compose :

```bash
[external_db]$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED              STATUS              PORTS                    NAMES
a799de7a1474        mysql               "/entrypoint.sh mysql"   About a minute ago   Up 10 seconds       0.0.0.0:3307->3306/tcp   externaldb_mysql_1
```

Your first external MySQL database is named "externaldb_mysql_1". You can now use this name to link her to your SpagoBI container.
You can now go back to your ge_spagobi folder to run the SpagoBI container.

```bash
[ge_spagobi]$ docker run --link ${MYSQL_IMAGE_NAME}:db --link externaldb_mysql_1:external_db -P ${SPAGOBI_CONTAINER_NAME}
```

Once the Terminal shows something like this
```
INFO: Server startup in 301864 ms
```
you can proceed with the next steps.

### 6. Get the Access Point

#### Get the IP of the Container

- If you are running a Virtual Machine (on Mac OS for example)

```bash
[ge_spagobi]$ docker-machine ls

NAME      ACTIVE   DRIVER       STATE     URL                         SWARM
default   *        virtualbox   Running   tcp://THIS_IS_YOUR_IP:2376   
```

- If your computer runs already on Linux

```bash
[ge_spagobi]$ docker inspect --format '{{ .NetworkSettings.IPAddress }}' spagobi
THIS_IS_YOUR_IP
[ge_spagobi]$
```

The IP address `THIS_IS_YOUR_IP` is used to access the SpagoBI installation.

#### Get the Port of the Container

```bash
[ge_spagobi]$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                                  NAMES
85a6629fbe32        $YOUR_IMAGE_NAME    "./entrypoint.sh ./st"   29 minutes ago      Up 29 minutes       0.0.0.0:THIS_IS_YOUR_PORT->8080/tcp    fantastic_fiware
```

The port number `THIS_IS_YOUR_PORT` is the port to access the SpagoBI installation.

You can now access the running SpagoBI under the URL `THIS_IS_YOUR_IP:THIS_IS_YOUR_PORT/SpagoBI` in your Web browser.

### 7. Configure the SpagoBI Interface

Start the Web browser, go to `THIS_IS_YOUR_IP:THIS_IS_YOUR_PORT/SpagoBI` and you should be provided with the SpagoBI welcome screen. Now you can log in using the Administrator account (biadmin/biadmin) and create data sources, data sets and different metrics or visualizations. For a detailed description please refer to the [Wiki](http://wiki.spagobi.org/).

### 8. Dump the Database

Once the configuration is done, we have to dump the MySQL database to extract the relevant information of the modifications.

```bash
[ge_spagobi]$ docker exec -i ${MYSQL_IMAGE_NAME} mysqldump ${MYSQL_DATABASE} -u${MYSQL_USER} -p${MYSQL_PASSWORD} | gzip  > MySQL_custom_setup.sql.gz
```

Keep in mind that there might be sensitive data (user credentials to access MySQL databases) in the file *MySQL_custom_setup.sql.gz*. So don't add it to a public repository or make it publicly available.

### 9. Share your interface

No one can share the interface that contains all the data sources, metrics and visualizations with others by just by giving away the file *MySQL_custom_setup.sql.gz*. They can now follow the steps 0-5 in this document (ensure that  MySQL_custom_setup.sql.gz is replaced correctly) to have an out-of-the-box customized instance of SpagoBI.


The initial files {Dockerfile, entrypoint.sh and docker-compose.yml} were taken from https://github.com/SpagoBILabs/SpagoBI/tree/master/docker/5.1-fiware-all-in-one
