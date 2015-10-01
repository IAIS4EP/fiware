# add your smoketest here - e.g. curl based API test of a GE

HOST=$1
PORT=$2

URL="$HOST:$PORT"

echo Get token:
curl -v -H 'X-Storage-User: test:tester' -H 'X-Storage-Pass: testing' $URL

echo Create container:

curl -X PUT -i -H "X-Auth-Token: $TOKEN" $URL/testcontainer

echo List containers:

curl -X GET -i -H "X-Auth-Token: $TOKEN" $URL

echo Contents of container:

curl -X GET -i -H "X-Auth-Token: $TOKEN" $URL/testcontainer

echo Metadata of container:

curl -X HEAD -i -H "X-Auth-Token: $TOKEN" $URL/testcontainer

echo Put object:

curl -X PUT -i -H "X-Auth-Token: $TOKEN" -T demo.sh $URL/testcontainer/testobject

echo Retrieve object:

curl -X GET -i -H "X-Auth-Token: $TOKEN" -o retrieved_demo.sh $URL/testcontainer/testobject
