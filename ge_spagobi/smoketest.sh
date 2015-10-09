#!/usr/bin/env bash
set -e

container=spagobi

if [[ `uname` == 'Darwin'* ]]; then
	IP=$(docker-machine ip dev)
else
	IP=$(docker inspect --format '{{.NetworkSettings.IPAddress}}' ${container})
fi

port_container=$(docker port $container)

IFS=':' read -a array <<< "$port_container"
port=$(echo ${array[1]})
address=$(echo $IP:$port)

# make sure the virtual machine is on
if [[ -z $IP ]]; then
	echo "Docker container not running"
	exit 1
else
	echo "Docker container running at IP : $IP"
fi

if [[ -z $port ]]; then
	echo "Container's ports not open to the local machine"
	exit 1
else
	echo "Container port : $port"
fi

echo "\nHomepage test"
status=$(curl -s --head -w %{http_code} $address/SpagoBI -o /dev/null)

if [[ "$status" > '390' ]]; then
	echo "Homepage not reached. Status : $status"
	exit 1
else
	echo "Homepage found with status : $status"
fi

echo "\nSmoketests were successful. \nOK."