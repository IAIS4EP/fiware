echo "DO ENVIRONMENT SANITY CHECK"
docker --version
curl --version
echo "---- BUILDING CONTAINER..."

docker build -t pbinkley/docker-swift .
# this one does not support further user interaction, need to use -d key
docker run -d --name=swift_api -p 9099:8080 -P -v /path/to/data:/swift/nodes -t pbinkley/docker-swift 
docker ps

echo "make sure you have got spare port 9099 or change for a custom"
#./smoketest.sh localhost 9099
