#!/bin/bash

set -e

# Remove old containers
echo $(docker rm -f $(docker ps -a -q))


# Build images and run containers
echo
echo Build images

echo $(docker build -t spago_image .) &
wait
echo
echo Building SpagoBI image


echo $(docker run --name mysqldb_container -e MYSQL_USER=root -e MYSQL_PASSWORD=root -e MYSQL_DATABASE=mysql_db -e MYSQL_ROOT_PASSWORD=root -d mysql) &
echo MySQL container run

echo

echo $(mysql.server start)

echo
echo SpagoBI image built

sleep 2 && echo $(docker run --name spago_container -e DB_PORT_3306_TCP_ADDR=localhost --link mysqldb_container -P spago_image) &
wait
echo
echo Running SpagoBI container

echo
echo SpagoBI container run

cd external_db 
echo
echo $(docker-compose up) &

echo
echo Running docker-compose
wait

echo
echo MySQL datasource run

# Get container id
spago_container_id=$(docker inspect --format="{{.Id}}" spago_container)
echo $spago_container_id

# Get IP depending on the OS : IP of the VM on Mac OS, IP of the container on others
if [[ `uname` == 'Darwin'* ]]; then
	IP=$(docker-machine ip $(docker-machine active))
else
	IP=$(docker inspect --format '{{.NetworkSettings.IPAddress}}' ${container_id})
fi

#Get port
port_container=$(docker port $container_id)

IFS=':' read -a array <<< "$port_container"
port=$(echo ${array[1]})
address=$(echo $IP:$port)

echo "Docker-machine test"
# make sure the virtual machine is on
if [[ -z $IP ]]; then
	echo "Docker machine not running\n"
	exit 1
else
	echo "Docker machine found at IP $IP\n"
fi

echo "Port test"
if [[ -z $port ]]; then
	echo "Container's ports not open to the local machine"
	exit 1
else
	echo "Open port found at $port_container\n"
fi

echo "Homepage test"
status=$(curl -s --head -w %{http_code} $address/SpagoBI -o /dev/null)

if [[ "$status" > '390' ]]; then
	echo "Homepage not reached. Status : $status"
	exit 1
else
	echo "Homepage found with status : $status\n"
fi

echo "Smoketests OK."