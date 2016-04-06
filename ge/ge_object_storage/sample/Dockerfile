FROM tutum/lamp:latest
ADD ./fiwareImageStorage.php /app/fiwareImageStorage.php
RUN rm /app/index.php
RUN export TERM=xterm
RUN apt-get -y install php5-curl nano
RUN apache2ctl restart
EXPOSE 80
