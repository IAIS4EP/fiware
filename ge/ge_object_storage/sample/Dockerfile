FROM tutum/lamp:latest
ADD ./fiwareImageStorage.php /app 
RUN mv /app/fiwareImageStorage.php /app/index.php
EXPOSE 80 
CMD ["/run.sh"]
