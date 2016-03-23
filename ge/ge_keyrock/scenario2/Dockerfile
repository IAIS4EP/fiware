# along the lines of tapsboy/docker-codeigniter for Code Igniter 2.3.0
FROM centurylink/apache-php:latest

RUN apt-get update && \
 DEBIAN_FRONTEND=noninteractive apt-get -y upgrade && \
 DEBIAN_FRONTEND=noninteractive apt-get -y install supervisor pwgen && \
 apt-get -y install mysql-client unzip wget 
 
 
WORKDIR /tmp 
RUN wget https://github.com/bcit-ci/CodeIgniter/archive/2.2.6.zip 

RUN mkdir /app
RUN unzip 2.2.6.zip && mv 2.2.6 /var/www/app

WORKDIR /app

ADD . /app

# Add configuration with info for Codeingiter to connect to DB
ADD database.php /app/application/config/database.php
RUN chmod 644 /app/application/config/database.php

# Fix permissions for apache
RUN chown -R www-data:www-data /app

EXPOSE 80
CMD ["/run.sh"]
