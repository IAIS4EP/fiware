FROM linuxkonsult/chef-solo

# official FIWARE Chef cookbook
#COOKBOOK_URL="https://forge.fiware.org/scmrepos/svn/testbed/trunk/cookbooks/GESoftware/IoTBroker/"
#RUN apt-get install svn

#RUN svn export $COOKBOOK_URL

# and then?..

## INSTALLATION STEPS , ( more info at https://fiware-orion.readthedocs.org/en/develop/quick_start_guide/index.html )

## Fist step is to create a Fiware Account here: https://account.lab.fiware.org/
## Once you have your account created:
## Download the token generation script by executing this command: 

wget --no-check-certiﬁcate https://raw.githubusercontent.com/fgalan/ oauth2-example-orion-client/master/token_script.sh

## Once is downloaded, execute it and it will ask for your fiware account details, introduce it and you will get the token.
## If this script doesn't work for you, you can generate it manually executing this command

curl -s -d "{\"username\": \”username@yourdomain.com \”, \"password\":\"yourpassword\"}" -H "Content-type: application/json" https://orion.lab.fiware.org/token

## As soon as you have the token you can start using the iOT Broker Services, for example, let's check to Santander's City sensor's information.

curl orion.lab.fi-ware.org:1026/ngsi10/contextEntities/urn:smartsantander:testbed:357 \ -X GET -s -S --header 'Content-Type: application/json'  --header 'Accept: application/json' \  --header  "X-Auth-Token: YOUR_TOKEN" | python -mjson.tool

## NOTE: Remember to replace YOUR_TOKEN with the token generated in the previous step

## With iOT Broker you can create and manage any kinds of entities.

## If you want to create a new entity, just execute this example

(curl orion.lab.fi-ware.org:1026/ngsi10/contextEntities/$ID -X POST -s -S \
   --header 'Content-Type: application/json' --header 'Accept: application/json' \
   --header "X-Auth-Token: YOUR_TOKEN" -d @- | python -mjson.tool) <<EOF
{
    "attributes": [
        {
            "name": "name_of_the_field",
            "type": "type_of_the_field",
            "value": "value_of_the_field"
        },
    ]
}
EOF

## NOTE: $ID is the id that your entity will have. Remember that you are using a public platform, so better create a random ID to identify your entity

RANDOM_NUMBER=$(cat /dev/urandom | tr -dc '0-9' | fold -w 10 | head -n 1)

## You can add as much attibutes as you want. The common types are all included (integer, float..)

## Of course, you can query the entities

(curl orion.lab.fi-ware.org:1026/ngsi10/contextEntities/$ID -X GET -s -S \ --header 'Content-Type: application/json' --header 'Accept: application/json'\ --header "X-Auth-Token: YOUR_TOKEN" | python -mjson.tool)

## For modifying the values of the stored entities, you can send a request with the new values

(curl orion.lab.fi-ware.org:1026/ngsi10/contextEntities/$ID/attributes/atribute_you_want_to_update \
   -X PUT -s -S --header  'Content-Type: application/json' --header 'Accept: application/json' \
   --header "X-Auth-Token: YOUR_TOKEN" -d @- | python -mjson.tool) <<EOF
{
    "value": "the_new_value"
}
EOF


## Note: Remember to chage atribute_you_want_to_update with the name of the field you want to update

## END INSTALLATION STEPS


#RUN chef-solo ####
