#!/bin/bash

# revert test

docker stop ckan
docker stop solr
docker stop postgresql

docker rm -f ckan
docker rm -f solr
docker rm -f postgresql

#docker rmi -f ckan/solr
#docker rmi -f ckan/postgresql



