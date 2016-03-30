#!/bin/sh
# This script builds a CKAN image and container, and runs an instance of this last one.

# useful variables
echo Declare variables ... 
echo

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

docker run -d -p 80:80 --link ${POSTGRESQL_CONTAINER}:db --link ${SOLR_CONTAINER}:solr --name ${CKAN_CONTAINER} ${CKAN_IMAGE}

echo CKAN instance running
