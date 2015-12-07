FROM mysql:latest
FROM mysql/mysql-server

# Copy the mysql dump on linux machine
COPY ./MySQL_external_db_dump.sql.gz ./

COPY entrypoint-docker.sh /entrypoint-docker.sh

RUN chmod +x *.sh

ENTRYPOINT ["/entrypoint-docker.sh"]

EXPOSE 3306
CMD ["mysqld"]