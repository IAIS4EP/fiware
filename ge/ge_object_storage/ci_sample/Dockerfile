FROM ubuntu:latest

RUN apt-get update && apt-get upgrade -y

# Install apache, PHP, and supplimentary programs. curl and lynx-cur are for debugging the container.
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install apache2 libapache2-mod-php5 php5-mysql php5-gd php-pear php5-curl curl lynx-cur

# Enable apache mods.
RUN a2enmod php5
RUN a2enmod rewrite

EXPOSE 80

# Copy site into place.
ADD . /var/www/site

# Update the default apache site with the config we created.
ADD config/apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# By default, simply start apache.
CMD /usr/sbin/apache2ctl -D FOREGROUND
