# this script is a small helper for the test app environment to build Docker image and run container


HTTP_PORT=${1:-80}
export HTTP_PORT

docker build -f Dockerfile.plain -t fiware/ge_object_storage_demo:latest .
docker rm -f swift-demo
docker run --name=swift-demo -d -p $HTTP_PORT:80  fiware/ge_object_storage_demo:latest

# for troubleshooting run container shell:
#docker exec -it swift-demo
