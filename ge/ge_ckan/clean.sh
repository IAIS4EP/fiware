#!bin/sh

# Cleaning script : remove all containers
# Remove also all the images if parameter -images passed

# remove all containers

export POSTGRESQL_CONTAINER=postgresql
export SOLR_CONTAINER=solr
export CKAN_IMAGE=ckan_image
export CKAN_CONTAINER=ckan
export LEN_EMPTY_CONTAINER=262


containers=$(docker ps -a --filter="name=$POSTGRESQL_CONTAINER")+$(docker ps -a --filter="name=$SOLR_CONTAINER")+$(docker ps -a --filter="name=$CKAN_CONTAINER")
len_three_empty_containers=3*$LEN_EMPTY_CONTAINER
len_containers=${#containers}

if [ "$len_containers" -lt "$len_three_empty_containers" ]
then
	echo No container to remove.
else
	echo Removing containers ...
	echo

	docker rm -f $POSTGRESQL_CONTAINER $SOLR_CONTAINER $CKAN_CONTAINER
	echo Containers removed
	echo
fi

# remove image if asked
if [ ! -z "$1" ]
then
	if [ -z $(docker images | grep $CKAN_IMAGE) ]
	then
		echo No image to remove.
		exit 1
	else
		echo Removing images ...
		echo
		docker rmi -f $(docker images -q)
		echo Images removed
		exit 1
	fi
fi

exit 1
