# add your smoketest here - e.g. curl based API test of a GE

HOST=$1
PORT=$2

URL="$HOST:$PORT"

echo -e '\n'
echo == Get Storage url and token: ==

STORAGE_URL=$(curl -i -H 'X-Storage-User: test:tester' -H 'X-Storage-Pass: testing' $URL/auth/v1.0 | grep X-Storage-Url: | awk {'print $2'})
TOKEN=$(curl -i -H 'X-Storage-User: test:tester' -H 'X-Storage-Pass: testing' $URL/auth/v1.0 | grep X-Auth-Token: | awk {'print $2'})

# clean new line chars
STORAGE_URL=$(echo $STORAGE_URL|tr -d '\r')
STORAGE_URL=$(echo $STORAGE_URL|tr -d '\n')
TOKEN=$(echo $TOKEN|tr -d '\r')
TOKEN=$(echo $TOKEN|tr -d '\n')

echo -e '\n'
echo == Create container: ==

curl -X PUT -i -H "X-Auth-Token: $TOKEN" $STORAGE_URL/testcontainer

echo -e '\n'
echo == List containers: ==

curl -X GET -i -H "X-Auth-Token: $TOKEN" $STORAGE_URL

echo -e '\n'
echo == Contents of container: ==

curl -X GET -i -H "X-Auth-Token: $TOKEN" $STORAGE_URL/testcontainer

echo -e '\n'
echo == Metadata of container: ==

curl -X HEAD -i -H "X-Auth-Token: $TOKEN" $STORAGE_URL/testcontainer

echo -e '\n'
echo == Put object: ==

curl -X PUT -i -H "X-Auth-Token: $TOKEN" -T demo.sh $STORAGE_URL/testcontainer/testobject

echo -e '\n'
echo == Retrieve object: ==

curl -X GET -i -H "X-Auth-Token: $TOKEN" -o retrieved_demo.sh $STORAGE_URL/testcontainer/testobject
