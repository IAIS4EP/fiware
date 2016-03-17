# contributor: watly

HOST=$1
PORT=$2

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
curl -v -H "Content-type: application/json" -X GET http://$HOST/ngsi9/contextEntities/Room1
