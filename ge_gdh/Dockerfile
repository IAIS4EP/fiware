FROM tomcat:6.0.44-jre7
MAINTAINER andrei@appscend.com

ADD EspR4FastData-3.3.3.war /usr/local/tomcat/webapps/
ADD MockEventSink.war /usr/local/tomcat/webapps/
ADD MockNotifyContextRecipient.war /usr/local/tomcat/webapps/
ADD EspR4FastDataSanityCheck.jar /root/

EXPOSE 8080
RUN ["catalina.sh", "run"]

# execute internal vendor sanity check
CMD java -jar EspR4FastDataSanityCheck.jar
