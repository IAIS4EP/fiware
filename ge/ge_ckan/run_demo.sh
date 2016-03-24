
CKAN_PORT=${1:-80}
export CKAN_PORT

echo "Running CKAN demo with port $CKAN_PORT."


export POSTGRESQL_CONTAINER=postgresql
export SOLR_CONTAINER=solr
export CKAN_IMAGE=ckan_image
export CKAN_CONTAINER=ckan


docker run -d --name ${POSTGRESQL_CONTAINER} ckan/postgresql
docker run -d --name ${SOLR_CONTAINER} ckan/solr

docker build -t ${CKAN_IMAGE} .

docker run -d -p $CKAN_PORT:80 --link ${POSTGRESQL_CONTAINER}:db --link ${SOLR_CONTAINER}:solr --name ${CKAN_CONTAINER} ${CKAN_IMAGE}


chmod u+x smoketest.sh
./smoketest.sh
