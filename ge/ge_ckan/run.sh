#!/bin/sh
# This script builds a CKAN image and container, and runs an instance of this last one.

# useful variables
echo Declare variables ... 
echo


CUSTOM_CKAN_PORT=9091

export POSTGRESQL_CONTAINER=postgresql
export SOLR_CONTAINER=solr
export CKAN_IMAGE=ckan_image
export CKAN_CONTAINER=ckan

echo Run CKAN dependencies containers ...
echo

docker run -d --name ${POSTGRESQL_CONTAINER} ckan/postgresql
docker run -d --name ${SOLR_CONTAINER} ckan/solr

echo Build CKAN image ...
echo

docker build -t ${CKAN_IMAGE} .

echo Create CKAN instance ... 
echo

docker run -d -p $CUSTOM_CKAN_PORT:80 --link ${POSTGRESQL_CONTAINER}:db --link ${SOLR_CONTAINER}:solr --name ${CKAN_CONTAINER} ${CKAN_IMAGE}

# case in which we build CKAN before postgresql is fully ready : before result != 0
result=$(docker inspect --format '{{ .State.ExitCode }}' ${POSTGRESQL_CONTAINER})
running=0

while [ $result -ne 0 -a $running -ne 1 ]; do
	sleep 5
	docker rm -f ${CKAN_CONTAINER}
	docker run -d -p 80:80 --link ${POSTGRESQL_CONTAINER}:db --link ${SOLR_CONTAINER}:solr --name ${CKAN_CONTAINER} ${CKAN_IMAGE}
done

# avoid falling back to the loop when we destroy the container
running=1

echo CKAN instance running

# wait that the ckan container is ready before launching the smoketests
sleep 10

chmod u+x smoketest.sh
sh smoketest.sh
