FROM ubuntu:14.04.4

RUN apt-get -y update && apt-get -y upgrade 
RUN apt-get -y install wget apache2 nginx libapache2-mod-wsgi libpq5
RUN wget http://packaging.ckan.org/python-ckan_2.5-trusty_amd64.deb
RUN dpkg -i python-ckan_2.5-trusty_amd64.deb



