# along the lines of tapsboy/docker-codeigniter for Code Igniter 2.1.3
FROM centurylink/apache-php:latest

RUN export igniter_version=2.1.3
RUN echo "setting framework version to: $igniter_version"

#RUN apt-get update && \
# DEBIAN_FRONTEND=noninteractive apt-get -y upgrade && \
# DEBIAN_FRONTEND=noninteractive apt-get -y install supervisor pwgen && \

RUN apt-get -y update 
RUN apt-get -y install mysql-client unzip wget 
 
 
WORKDIR /tmp 
RUN wget https://github.com/bcit-ci/CodeIgniter/archive/2.1.3.zip

RUN unzip 2.1.3.zip && mv CodeIgniter-2.1.3 /app

WORKDIR /app

ADD . /app

# Add configuration with info for Codeingiter to connect to DB
ADD database.php /app/application/config/database.php
RUN chmod 644 /app/application/config/database.php

# Fix permissions for apache
RUN chown -R www-data:www-data /app

EXPOSE 80
CMD ["/run.sh"]
