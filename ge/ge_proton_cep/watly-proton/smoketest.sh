
#!/bin/sh

HOST=$1
PORT=$2

response=`curl -X POST -H "Content-Type: application/json" -H "Cache-Control: no-cache" -d '{"Name":"RfidDetected","Type":"Text","Uri":null,"Title":"31bc572a"}' "http://$HOST:$PORT/ProtonOnWebServer/rest/events"`
echo $response
