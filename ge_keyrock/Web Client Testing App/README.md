# KeyRock GE - Web Client Testing App
This is a web client for testing your KeyRock GE Instance Installation.

One of KeyRock's feature is an OAuth 2.0 authorization flow which allows 3rd party apps to access to your API.

The client implements all grants type (code, implicit, password and credentials). 

Some additional settings may be required - please read further.

# Setting an Application

  You need to set a new Application under your KeyRock instance and get its client and secret ids.
  
  The default of the script is to use the KeyRoc Client Testing App installed under our account on
  ```https://account.lab.fiware.org```    
  You can use this application for testing.
  
  If you need to make changes in the application you can login using the following credentials:
  
  ```username: testing@pico-app.com```
  
  ```password: 123456789```

# Docker Build 

  From the client's project folder
  ```docker build -t keyrock-client .```
        
# Run the client on Docker VM
     
  ```docker run -p 8080:80 -d keyrock-client```
      
  Browse to ```http://your.ip.address:8080``` to start using the client 
     
# Settings 
  
  Some additional settings are required depending on your grant type:
         
  **Code**
  Change Redirect URL to : ```http://your.ip:8080/callback/callback.php``` in both of these places:
 
  * The application in your KeyRock instance 
  * The ```$redirectUri``` variable in path ```php/settings.php```
       
  **Password** 
  
  Make sure your username and password are correct in ```php/authentication.php```
  
  **Implicit***
  in ```callback/index.html``` you can see an implementation of JS implicit flow using the ``scripts/KeyrockManager.js``` file
  You don't have to set anything special to use implicit login flow, but if you want to use the JS implementation, change the following settings: 
  
  Change Redirect URL to : ```http://your.ip:8080/callback``` in both of these places:
   
  * The application in your KeyRock instance 
  * The ```$redirectUri``` variable in path ```php/settings.php```
  
  

Hope you find this useful !


## Please don't hesitate to contact us:
[Asaf Nevo](mailto:asaf.nevo@pico.buzz)

[Aviv Paz](mailto:aviv.paz@pico.buzz)

# Enjoy!
