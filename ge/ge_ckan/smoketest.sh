#!/bin/bash

set -e

# Get container id
container=($(docker ps | awk '{ print $1 }'))
container_id=${container[1]}

# Get IP depending on the OS : IP of the VM on Mac OS, IP of the container on others
if [[ `uname` == 'Darwin'* ]]; then
	IP=$(docker-machine ip $(docker-machine active))
else
	IP=$(docker inspect --format '{{.NetworkSettings.IPAddress}}' ${container_id})
fi

# get port
port_container=$(docker port $container_id)

IFS=':' read -a array <<< "$port_container"
port=$(echo ${array[1]})
address=$(echo $IP)

echo "Docker-machine test"
# make sure the virtual machine is on
if [[ -z $IP ]]; then
	echo "Docker container not running\n"
	exit 1
else
	echo "Docker container found at IP $IP\n"
fi

echo "Port test"
if [[ -z $port ]]; then
	echo "Container's ports not open to the local machine"
	exit 1
else
	echo "Open port found at $port_container\n"
fi

echo "Homepage test"
status=$(curl -s --head -w %{http_code} $address -o /dev/null)

if [[ "$status" > '390' ]]; then
	echo "Homepage not reached. Status : $status"
	exit 1
else
	echo "Homepage found with status : $status\n"
fi

# get ckanapi if not
pip install ckanapi

# move to the python files folder
cd api_interact
echo 'Move to python files folder'
pwd
echo

# insert dataset and resource in the ckan container
ckan_instance=$(python test_instance.py $IP)

if [[ -z $ckan_instance ]]; then
	echo "No CKAN instance found."
	exit 1
else
	echo "CKAN instance created."
	echo
fi

ckan_user=$(python create_user.py $IP)
echo "$ckan_user"


echo "Smoketests OK."
