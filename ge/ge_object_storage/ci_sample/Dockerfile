FROM centurylink/apache-php:latest

RUN export igniter_version=2.2.6
RUN echo "setting framework version to: $igniter_version"


RUN apt-get -y update 
RUN apt-get -y install mysql-client unzip wget 
 
 
WORKDIR /tmp 
RUN wget https://github.com/bcit-ci/CodeIgniter/archive/2.2.6.zip

RUN unzip 2.2.6.zip && mv CodeIgniter-2.2.6 /app

WORKDIR /app

ADD . /app


# Fix permissions for apache
RUN chown -R www-data:www-data /app

EXPOSE 80
CMD ["/run.sh"]
