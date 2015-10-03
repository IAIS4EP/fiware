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

## As soon as you have the token you can start using the iOT Broker Services. See more examples in the smoketest.sh file

## END INSTALLATION STEPS


#RUN chef-solo ####
