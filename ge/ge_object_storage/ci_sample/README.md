
# CodeIgniter Sample for Object Storage
This demo was tested on a clean ubuntu 15.10

To start with make sure you have the required configuration

```
Ubuntu 15.10
Apache 2.4.12
PHP 5.6.11
PHP application framework : CodeIgniter 2.2.6

```

# API Sample Code
This sample directory contains sample code for using Object Storage API in CI.
```
Controller : image_storage.php
View : fiwareImageStorage.php

```

<b>application/config/config.php</b>

Set base url in config.php file: $config['base_url'] =  '<HOST URL>';

<b>application/controller/image_storage.php</b>

In this controller, you need to fill the following Object Storage Server Details :

<b>FIWARE OBJECT STORAGE SERVER</b>&nbsp;//object storage server IP or domain.
<b>FIWARE OBJECT STORAGE PORT</b>&nbsp;//object storage server port, e.g. "9099", "8080"<br>
<b>CONTAINER NAME</b>&nbsp;//container name for storing images. e.g. "testcontainer"<br>
<b>USER NAME</b>&nbsp;//user name of existing object storage account. e.g. "test:tester"<br>
<b>USER KEY</b>&nbsp;//user secret key of existing object storage account. e.g. "testing"<br>











