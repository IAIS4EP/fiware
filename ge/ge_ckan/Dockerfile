FROM phusion/baseimage:0.9.15
MAINTAINER Open Knowledge

# Disable SSH
RUN rm -rf /etc/service/sshd /etc/my_init.d/00_regen_ssh_host_keys.sh

ENV HOME /root
ENV CKAN_HOME /usr/lib/ckan/default
ENV CKAN_CONFIG /etc/ckan/default
ENV CKAN_DATA /var/lib/ckan
ENV CKAN_VERSION=docker-for-ckan-v2.2.1
ENV CKAN_URL=https://github.com/cygri/ckan/archive/${CKAN_VERSION}.zip


# Install required packages
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get -q -y install \
        python-minimal \
        python-dev \
        python-virtualenv \
        libevent-dev \
        libpq-dev \
        nginx-light \
        apache2 \
        libapache2-mod-wsgi \
        postfix \
        wget \
        unzip \
        tree \
        build-essential

# Install CKAN
RUN virtualenv $CKAN_HOME
RUN mkdir -p $CKAN_HOME $CKAN_CONFIG $CKAN_DATA
RUN mkdir -p $CKAN_HOME/src/ckan
RUN chown www-data:www-data $CKAN_DATA

# Download CKAN & extract it
RUN echo 'Downloading CKAN'
RUN wget "${CKAN_URL}" -O CKAN.zip && \
        unzip CKAN.zip && \
        mv ./ckan-$CKAN_VERSION/requirements.txt $CKAN_HOME/src/ckan/requirements.txt && \
        $CKAN_HOME/bin/pip install -r $CKAN_HOME/src/ckan/requirements.txt && \
        mv ./ckan-$CKAN_VERSION/* $CKAN_HOME/src/ckan/ && \
        rm CKAN.zip


#ADD ckan.ini /etc/ckan/default/

#RUN $CKAN_HOME/bin/pip install -r $CKAN_HOME/src/ckan/requirements.txt 

RUN $CKAN_HOME/bin/pip install -e $CKAN_HOME/src/ckan/
RUN ln -s $CKAN_HOME/src/ckan/ckan/config/who.ini $CKAN_CONFIG/who.ini
RUN mv $CKAN_HOME/src/ckan/contrib/docker/apache.wsgi $CKAN_CONFIG/apache.wsgi

# Configure apache
RUN mv $CKAN_HOME/src/ckan/contrib/docker/apache.conf /etc/apache2/sites-available/ckan_default.conf
RUN echo "Listen 8080" > /etc/apache2/ports.conf
RUN a2ensite ckan_default
RUN a2dissite 000-default

# Configure nginx - server proxy
RUN mv $CKAN_HOME/src/ckan/contrib/docker/nginx.conf /etc/nginx/nginx.conf
RUN mkdir /var/cache/nginx

# Configure postfix - mail transfert agent
RUN mv $CKAN_HOME/src/ckan/contrib/docker/main.cf /etc/postfix/main.cf

# Configure runit - init scheme for Unix os
# runit initialise & supervise every daemons like postgresql, solr
RUN mv $CKAN_HOME/src/ckan/contrib/docker/my_init.d/* /etc/my_init.d/
RUN mv $CKAN_HOME/src/ckan/contrib/docker/svc/* /etc/service
CMD ["/sbin/my_init"]

VOLUME ["/var/lib/ckan"]
EXPOSE 80

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
