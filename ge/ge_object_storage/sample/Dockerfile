FROM tutum/lamp:latest
ADD ./fiwareImageStorage.php /app/fiwareImageStorage.php 
RUN rm /app/index.php

# Install Apache and PHP5
RUN apt-get install apache2 -y
RUN apt-get install php5 -y libapache2-mod-php5 -y
RUN apt-get install php5-curl -y php5-gd -y php5-intl -y php-pear -y php5-imagick -y php5-imap -y php5-mcrypt -y php5-memcache -y php5-ming -y php5-ps -y php5-pspell -y php5-recode -y php5-tidy -y php5-xmlrpc -y php5-xsl -y php5-mysql -y php5-xcache -y

# enable mod_rewrite
RUN a2enmod php5
RUN a2enmod rewrite

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Update the default apache site with the config we created.
ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# Update php.ini
# we might want to upload large files
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 1000M/' /etc/php5/apache2/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 1000M/' /etc/php5/apache2/php.ini
# we don't want our requests to time out too soon (specially if uploading large files)
RUN sed -i 's/max_execution_time = 30/max_execution_time = 900/' /etc/php5/apache2/php.ini



RUN apache2ctl restart
EXPOSE 80 
CMD ["/run.sh"]
