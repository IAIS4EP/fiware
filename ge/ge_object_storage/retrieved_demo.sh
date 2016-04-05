HTTP/1.1 200 OK
Content-Length: 550
Accept-Ranges: bytes
Last-Modified: Wed, 30 Mar 2016 09:17:30 GMT
Etag: 6e8b20a20330ec9a66e0c86def6fd0b0
X-Timestamp: 1459329449.54405
Content-Type: application/octet-stream
X-Trans-Id: tx1be872ebe5aa4d74a76ab-0056fb99a9
Date: Wed, 30 Mar 2016 09:17:29 GMT

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
