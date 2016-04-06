API Deployment | Object storage
==============

This demo was tested on a clean Ubuntu 15.04.

### Quick End-to-End Test, or Information for the Impatient.

This test setup requires Docker and consists of two test environments; 
one for the Enabler API, one for a demo client accessing the API.

#### Preparation

- pull this repository
- adjust configuration of the demo client in sample/fiwareImageStorage.php with the hostname (not localhost) and optionally the API port (we used 9099) as it is quite rare. Please note that the demo assumes that the Object Storage uses a storage called "testcontainer" (which will be created during the test execution).

deploy the API container:

`./deploy_api.sh`

Wait a couple of seconds to let the service start. You can ensure that it is running and operates correctly like this:
`./smoketest.sh`

Now deploy the API demo:
```
cd sample
./deploy_demo.sh
```

For troubleshooting, please use:

```
docker logs swift_api
docker logs swift_demo
```

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
Before proceeding, please consider that the service might need a couple of minutes to start. You can check the API interface with running (given that you run the test on the localhost on port 8080):

```
sudo ./smoketest.sh localhost 8080
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

API Demo  | Sample Code
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
The script must exit with exit code 0 and produce a result test file retrieved_demo.sh


Optional way to deploy API if the above ./deploy_api.sh is not working as fall back helper.
------------
```
> docker run -v /srv --name SWIFT_DATA busybox
> docker run -d -p 9099:8080 --name=swiftfun -e SWIFT_DEFAULT_CONTAINER=container_name --volumes-from SWIFT_DATA -t morrisjobke/docker-swift-onlyone
> ./smoketest.sh localhost 9099
```

