Object storage
==============

This demo was tested on a clean Ubuntu 15.04.

### EASY INSTALLATION

Simply download this repo and run `./easy_install.sh`


### MANUAL INSTALLATION

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

Sample Code
-----------

The sample directory contains sample code for using Object Storage API.

<b>fiwareImageStorage.php</b>:

A PHP client and demo web client for Object Storage which allows testing of basic Object Storage APIs: PUT, GET, LIST, DELETE.
The client uses these operations for storing, retrieving and removing images from an image container on Object Storage Server.

<b>Note</b>:

The PHP code contains place holders (which you need to fill) for the actual Object Storage Server details:

<b>FIWARE OBJECT STORAGE SERVER</b>&nbsp;//object storage server IP or domain. e.g. "http://fiware.objectstorage.com"<br>
<b>FIWARE OBJECT STORAGE PORT</b>&nbsp;//object storage server port. e.g. "80"<br>
<b>CONTAINER NAME</b>&nbsp;//container name for storing images. e.g. "images"<br>
<b>USER NAME</b>&nbsp;//user name of existing object storage account. e.g. "test:tester"<br>
<b>USER KEY</b>&nbsp;//user secret key of existing object storage account. e.g. "testing"<br>


smoketest.sh
------------
The smoketest.sh gives a simple way to test basic API functionality.
```
> docker run -v /srv --name SWIFT_DATA busybox
> docker run -d -p 8080:8080 --name=swiftfun -e SWIFT_DEFAULT_CONTAINER=container_name --volumes-from SWIFT_DATA -t morrisjobke/docker-swift-onlyone
> ./smoketest.sh localhost 8080
```
The script must exit with exit code 0 and produce a result test file retrieved_demo.sh
