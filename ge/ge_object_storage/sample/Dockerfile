FROM tutum/lamp:latest
ADD ./fiwareImageStorage.php /app/fiwareImageStorage.php 
RUN apt-get -y install php5-curl
RUN apache2ctl restart
EXPOSE 80 
CMD ["/run.sh"]
