Object storage
==============

This demo was tested on a clean Ubuntu 15.04.

To start with make sure you have the required dependencies installed:

```
sudo apt-get update
sudo apt-get install docker.io
sudo apt-get install git

```


Clone the repository:

```
git clone https://github.com/IAIS4EP/fiware.git
cd fiware/ge_object_storage/
```


Run the docker file:

```
sudo service docker start
docker build -t pbinkley/docker-swift .
```


Run a demo that stores the data in a directory at "/path/to/data".

```
sudo docker run -P -v /path/to/data:/swift/nodes -t pbinkley/docker-swift
```


Now we need to find out in which port it is running. So open another terminal window:

```
sudo docker ps
```


And get the port mapped to 8080. This port will be used on our requests.
Get the authorization token:

```
curl -v -H 'X-Storage-User: test:tester' -H 'X-Storage-Pass: testing' http://127.0.0.1:<port>/auth/v1.0
```


And take note of the `X-Storage-Url` and `X-Auth-Token` which will be used for consecutive requests.
To run the demo simply add 2 new lines to `demo.sh` containing the url and token:

```
URL=<url>
TOKEN=<token>
```

And simply run the demo:

```
./demo.sh
```
