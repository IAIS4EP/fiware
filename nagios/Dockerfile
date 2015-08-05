FROM itoshkov/docker-pnp4nagios


RUN apt-get -y install nmap
RUN apache2ctl start
RUN /etc/init.d/nagios3 start
RUN nmap localhost -p 80
RUN pstree -p

# added from webratio/java:7

WORKDIR /tmp


RUN apt-get update -y && \
    apt-get install -y python-software-properties && \
    apt-get install -y software-properties-common && \
    add-apt-repository ppa:webupd8team/java -y && \
    apt-get update -y && \
    echo oracle-java7-installer shared/accepted-oracle-license-v1-1 select true | /usr/bin/debconf-set-selections && \
    apt-get install -y oracle-java7-installer && apt-get install -y psmisc nano && \
    apt-get remove software-properties-common -y && \
    apt-get autoremove -y && \
    apt-get clean
ENV JAVA_HOME /usr/lib/jvm/java-7-oracle


# Installs Ant
ENV ANT_VERSION 1.9.6

RUN wget -q http://www.apache.org/dist/ant/binaries/apache-ant-${ANT_VERSION}-bin.tar.gz



RUN pwd
RUN ls -alh

RUN tar -xzf apache-ant-${ANT_VERSION}-bin.tar.gz && \
    mv apache-ant-${ANT_VERSION} /opt/ant && \
    rm apache-ant-${ANT_VERSION}-bin.tar.gz

ENV ANT_HOME /opt/ant
ENV PATH ${PATH}:/opt/ant/bin

RUN uname -a && java -version && javac -version && ant -version && \
    pwd && ls -alh


# build

COPY build.xml /tmp/build.xml
COPY nagios_conf /tmp/nagios_conf
RUN pwd && ls -alh 
RUN ant nagios

# run

RUN pstree -p 


#RUN cd build 
RUN ls -alh

RUN cp build/nagios.cfg  /etc/nagios3/conf.d

#RUN sed -n '1315,1330p; 1331q' /etc/nagios3/nagios.cfg


RUN /etc/init.d/nagios3 restart




EXPOSE 80
