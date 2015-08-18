FROM ubuntu:latest
# Update Ubuntu
RUN apt-get update && apt-get -y upgrade
# Add oracle java 7 repository
RUN apt-get -y install software-properties-common
RUN add-apt-repository ppa:webupd8team/java
RUN apt-get -y update
# Accept the Oracle Java license
RUN echo "oracle-java7-installer shared/accepted-oracle-license-v1-1 boolean true" | debconf-set-selections
# Install Oracle Java
RUN apt-get -y install oracle-java7-installer
# Install tomcat
RUN apt-get -y install tomcat7 tomcat7-admin
RUN echo "JAVA_HOME=/usr/lib/jvm/java-7-oracle" >> /etc/default/tomcat7
EXPOSE 8080
# Install git
RUN apt-get -y install git maven
WORKDIR /root

# Clone Proton repo
RUN git clone https://github.com/ishkin/Proton.git
# Ubuntu  Tomcat package sets CATALINA_HOME to this folder, and CATALINA_BASE to /var/lib/tomcat7. Proton expects them to be in the same folder.
# This is a hack in order to get Proton running on Ubuntu official Tomcat packages
# This is suboptimal, in case Proton.properties or JSON application files change syntax, the builds would fail and these files should also be updated.

WORKDIR /root/Proton
RUN mvn clean install
WORKDIR /root/Proton/IBM Proactive Technology Online 
RUN mkdir -p /var/lib/tomcat7/webapps/ProtonOnWebServer/
RUN mv  ./AuthoringTool/target/AuthoringTool-0.0.1.war ./AuthoringTool/target/AuthoringTool.war
RUN mv ./AuthoringToolWebServer/target/AuthoringToolWebServer-0.0.1.war ./AuthoringToolWebServer/target/AuthoringToolWebServer.war
RUN mv ./ProtonOnWebServer/target/ProtonOnWebServer-0.0.1.war ./ProtonOnWebServer/target/ProtonOnWebServer.war
RUN mv ./ProtonOnWebServerAdmin/target/ProtonOnWebServerAdmin-0.0.1.war ./ProtonOnWebServerAdmin/target/ProtonOnWebServerAdmin.war
RUN cp ./AuthoringTool/target/AuthoringTool.war /var/lib/tomcat7/webapps/
RUN cp ./AuthoringToolWebServer/target/AuthoringToolWebServer.war /var/lib/tomcat7/webapps/
RUN cp ./ProtonOnWebServer/target/ProtonOnWebServer.war /var/lib/tomcat7/webapps/
RUN cp ./ProtonOnWebServerAdmin/target/ProtonOnWebServerAdmin.war /var/lib/tomcat7/webapps/
COPY ./Proton.properties /var/lib/tomcat7/webapps/ProtonOnWebServer/
COPY ./DoSAttack2.json /var/lib/tomcat7/webapps/ProtonOnWebServer/
#startup.sh and tomcat-users settings according to the Installation Guide
COPY ./startup.sh /usr/share/tomcat7/bin/
COPY ./tomcat-users.xml /etc/tomcat7/
RUN mkdir /root/docker
WORKDIR /root
# install curl for smoketest
RUN apt-get -y install curl
# Remove warnings 
RUN mkdir -p /usr/share/tomcat7/common/classes
RUN mkdir -p /usr/share/tomcat7/server/classes
RUN mkdir -p /usr/share/tomcat7/shared/classes
RUN mkdir -p /var/lib/tomcat7/temp
# smoketest bash script
COPY ./docker_smoketest.sh /root/docker/
ENV CATALINA_BASE /var/lib/tomcat7/
ENV CATALINA_HOME /usr/share/tomcat7/
CMD /usr/share/tomcat7/bin/catalina.sh run
#CMD /root/docker/docker_smoketest.sh run
