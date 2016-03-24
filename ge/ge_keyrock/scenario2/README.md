# KEYROCK-Testing

KEYROCK OAuth 2.0 authorization - a LAMP example using PHP framework Code Igniter 2.1.3

##Authenticate user using FIWARE

This demo was tested on Ubuntu 12.10

To start with make sure you have the required configuration

```
Ubuntu 12.10
Apache 2.2.2
MySql 5.5.29
PHP 5.4.6
PHP application framework : CodeIgniter 2.1.3

```

## Testing the sample with Docker (this example is yet in work)

docker build -t rezguru/ge_keyrock_sample:latest .
docker run -d -p CUSTOM_PORT:80  --name=rezguru_demo rezguru/ge_keyrock_sample:latest

You should be able to access the frontend via http://host:CUSTOM_PORT on the machine where you run Docker.

For troubleshooting and experiments, you can connect to the Docker container using this command to enter its shell environment:
 docker exec -it rezguru_demo bash



