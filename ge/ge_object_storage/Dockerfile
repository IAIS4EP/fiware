# DOCKER-VERSION 0.6.4
FROM ubuntu:15.10

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


RUN	echo "deb http://archive.ubuntu.com/ubuntu precise universe" >> /etc/apt/sources.list

# workaround for Ubuntu dependency on upstart https://github.com/dotcloud/docker/issues/1024
RUN	dpkg-divert --local --rename --add /sbin/initctl; ln -sf /bin/true /sbin/initctl

RUN	DEBIAN_FRONTEND=noninteractive apt-get update; DEBIAN_FRONTEND=noninteractive apt-get upgrade -y

RUN	DEBIAN_FRONTEND=noninteractive apt-get install -y rsyslog; DEBIAN_FRONTEND=noninteractive apt-get install -y apt-utils net-tools curl gcc memcached rsync sqlite3 xfsprogs git-core libffi-dev python-setuptools sudo python-coverage python-dev python-nose python-simplejson python-xattr python-eventlet python-greenlet python-pastedeploy python-netifaces python-pip python-dnspython python-mock sysklogd attr openssh-server openssh-client \
    python-lxml

RUN	cd /usr/local/src; git clone --depth 1 https://github.com/openstack/python-swiftclient.git
RUN	cd /usr/local/src; git clone --depth 1 https://github.com/openstack/swift.git
RUN	cd /usr/local/src; git clone --depth 1 https://github.com/stackforge/swift3.git

RUN pip install --upgrade pip
RUN	cd /usr/local/src/python-swiftclient; git checkout tags/2.3.1 && python setup.py develop; cd -
RUN	cd /usr/local/src/swift; git checkout tags/2.2.2 && python setup.py develop; cd -
RUN	cd /usr/local/src/swift3; python setup.py develop; cd -
RUN	pip install -r /usr/local/src/swift/test-requirements.txt

RUN	easy_install supervisor; mkdir /var/log/supervisor/

# create swift user and group
RUN	/usr/sbin/useradd -m -d /swift -U swift


ADD	./swift /etc/swift

# Setting up rsync

ADD ./misc/rsyncd.conf /etc/
RUN	sed -i 's/RSYNC_ENABLE=false/RSYNC_ENABLE=true/' /etc/default/rsync

RUN     sed -i 's/SLEEP_BETWEEN_AUDITS = 30/SLEEP_BETWEEN_AUDITS = 86400/' /usr/local/src/swift/swift/obj/auditor.py

ADD ./bin /swift/bin
RUN	chmod +x /swift/bin/*

ADD	./misc/bashrc /swift/.bashrc

RUN	cp /usr/local/src/swift/test/sample.conf /etc/swift/test.conf

ADD	./rsyslog.d/10-swift.conf /etc/rsyslog.d/10-swift.conf
RUN	sed -i 's/\$PrivDropToGroup syslog/\$PrivDropToGroup adm/' /etc/rsyslog.conf
RUN	mkdir -p /var/log/swift/hourly; chown -R syslog.adm /var/log/swift; chmod -R g+w /var/log/swift

ADD     ./misc/supervisord.conf /etc/supervisord.conf

RUN	mkdir /var/run/sshd
RUN	echo swift:fingertips | chpasswd; usermod -a -G sudo swift

RUN echo %sudo	ALL=NOPASSWD: ALL >> /etc/sudoers

VOLUME	/swift/nodes

EXPOSE 8080
EXPOSE 22
CMD ["/bin/bash", "/swift/bin/launch.sh"]

