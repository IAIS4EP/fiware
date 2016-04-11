
# CodeIgniter Sample for Keyrock OAuth API
This demo was tested on a clean ubuntu 15.10

To start with make sure you have the required configuration

```
Ubuntu 15.10
Apache 2.4.12
PHP 5.6.11
PHP application framework : CodeIgniter 2.2.6

```

# Setting An Application
First you need to set a new Application under your keyrock instance and get its client id and secret key.

Then set the following fields in <b>application/config/config.php</b> :

<b>$config['base_url'] =  'HOST URL'</b><br>
<b>$config['fiwareClientId'] = 'FIWARE CLIENT ID'</b><br>
<b>$config['fiwareSecret'] = 'FIWARE SECRET KEY'</b><br>

```
Controller : application/controller/fiware_login.php
```
 Change Redirect URL to : ```http://your.ip/index.php/fiware_login/fwlogin``` in both of these places:

  * The application in your KeyRock instance 
  * The ```redirect_uri``` variable in contoller ```application/controller/fiware_login.php```








