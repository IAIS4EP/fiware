# lets try to create 2 new entities with some attributes
# remember to change $HOST variable 
# (if you have use docker you can set localhost:8080)

HOST=$1
PORT=$2

# REGISTRATION
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{
    "contextRegistrations": [
        {
            "entities": [
                {
                    "type": "Room",
                    "isPattern": "false",
                    "id": "Room1"
                },
                {
                    "type": "Room",
                    "isPattern": "false",
                    "id": "Room2"
                }
            ],
            "attributes": [
                {
                    "name": "temperature",
                    "type": "float",
                    "isDomain": "false"
                },
                {
                    "name": "pressure",
                    "type": "integer",
                    "isDomain": "false"
                }
            ],
            "providingApplication": "http://mysensors.com/Rooms"
        }
    ],
    "duration": "P1M"
}'  http://$HOST/ngsi9/registerContext

# Retrieve info from the first entity
curl -v -H "Content-type: application/json" -X GET http://$HOST:$PORT/ngsi9/contextEntities/Room1
