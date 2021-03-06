#!/bin/bash

# demo script to run a SpagoBI container

# useful variables
export SPAGOBI_IMAGE_NAME=spagobi_image
export MYSQL_CONTAINER_NAME=spagobidb_container
export MYSQL_USER=spagobi_user
export MYSQL_PASSWORD=spagobi_password
export MYSQL_DATABASE=spagobi_db
export MYSQL_ROOT_PASSWORD=spagobi_root_password
export SPAGOBI_CONTAINER_NAME=spagobi_container

# Build the SpagoBI Image from the Dockerfile
docker build -t $SPAGOBI_IMAGE_NAME .

# Run a MySQL Container for the SpagoBI Data
docker run --name ${MYSQL_CONTAINER_NAME} -e MYSQL_USER=${MYSQL_USER} -e MYSQL_PASSWORD=${MYSQL_PASSWORD} -e MYSQL_DATABASE=${MYSQL_DATABASE} -e MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD} -d mysql

# Run the SpagoBI Container
docker run --name ${SPAGOBI_CONTAINER_NAME} --link ${MYSQL_CONTAINER_NAME}:db -P ${SPAGOBI_IMAGE_NAME}

# handle the case in which we build the SpagoBI container before finishing to build the mysql db container - ERROR 2003 (HY000): Can't connect to MySQL server
result=$(docker inspect --format '{{ .State.ExitCode }}' ${SPAGOBI_CONTAINER_NAME})

while [ $result -ne 0 ]; do
	sleep 5
	docker rm -f ${SPAGOBI_CONTAINER_NAME}
	docker run --name ${SPAGOBI_CONTAINER_NAME} --link ${MYSQL_CONTAINER_NAME}:db -P ${SPAGOBI_IMAGE_NAME}
done

exit 0