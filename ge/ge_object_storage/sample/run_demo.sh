# this script is a small helper for the test app environment to build Docker image and run container

docker build  -t fiware/ge_object_storage_demo:latest .
docker rm -f swift-demo
docker run --name=swift-demo -d fiware/ge_object_storage_demo:latest

# for troubleshooting run container shell:
#docker exec -it ge_os_test bash
