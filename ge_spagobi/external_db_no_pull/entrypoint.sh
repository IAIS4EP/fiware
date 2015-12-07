#!/bin/bash
set -e

#wait for MySql if it's a compose image
if [ -n "$WAIT_MYSQL" ]; then
	while ! curl http://$DB_PORT_3306_TCP_ADDR:$DB_PORT_3306_TCP_PORT/
	do
	  echo "$(date) - still trying to connect to mysql"
	  sleep 1
	done
fi

if [ -n "$DB_ENV_MYSQL_DATABASE" ]; then

	# Insert external db and tables in mysql if it doesn't exist
	Result=`mysql -uroot -proot -e "SHOW TABLES LIKE '%users%';"`
	echo "Result : "
	echo $Result
	if [ -z "$Result" ]; then
		if [ -s "MySQL_external_db_dump.sql.gz" ]
		then
			gunzip -c ${MYSQL_SCRIPT_DIRECTORY}/MySQL_external_db_dump.sql.gz | mysql -uroot -proot
		fi
	fi
fi

exec "$@"