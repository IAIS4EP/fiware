HTTP/1.1 200 OK
Content-Length: 550
Accept-Ranges: bytes
Last-Modified: Wed, 06 Apr 2016 11:52:45 GMT
Etag: 6e8b20a20330ec9a66e0c86def6fd0b0
X-Timestamp: 1459943564.88640
Content-Type: application/octet-stream
X-Trans-Id: tx37f81aaea1b2453c91138-005704f88c
Date: Wed, 06 Apr 2016 11:52:44 GMT

echo Create container

curl -X PUT -i -H "X-Auth-Token: $TOKEN" $URL/testcontainer

echo List containers:

curl -X GET -i -H "X-Auth-Token: $TOKEN" $URL

echo Contents of container:

curl -X GET -i -H "X-Auth-Token: $TOKEN" $URL/testcontainer

echo Metadata of container:

curl -X HEAD -i -H "X-Auth-Token: $TOKEN" $URL/testcontainer

echo Put object:

curl -X PUT -i -H "X-Auth-Token: $TOKEN" -T demo.sh $URL/testcontainer/testobject

echo Retrieve object

curl -X GET -i -H "X-Auth-Token: $TOKEN" -o retrieved_demo.sh $URL/testcontainer/testobject
