# Watly-proton test
This projects contains the proton-cep test made in order to evaluate the usage of the technology into the Watly platform.

The **explorenfc-invoker** project is meant to run on a raspberry pi which mounts an [EXPLORE NFC Board](https://www.element14.com/community/docs/DOC-71574/l/explore-nfc-board-for-raspberry-pi). This program will exploit the *explorenfc-basic* command in order to detect the NFC tags and send the as events to a Proton instance running at the url specified as an argument of the java application.

The **watly-cep-definition.json** file contains the definition of the proton instance used within the test. The instance forwards every RfidDetected event to the server and verifies whether a tag has been detected multiple times during the unit of time (20 seconds in the test), notifying it with a MultipleRfidDetected event. The consumers will need to be adapted to the specific url of the server.

The project **watly-server** holds a rest web service which tracks the events incoming from the CEP into a log file specifies by *watly.properties*.

For a quick test you may wish to use the [docker](https://hub.docker.com/r/adeprato/watly-cep/), based on a apache-tomcat instance which contains the proton and watly-server applications.
