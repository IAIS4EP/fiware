#!/bin/bash
set -e

# if command starts with an option, prepend mysqld
if [ "${1:0:1}" = '-' ]; then
	set -- mysqld "$@"
fi
if [ "$1" = 'mysqld' ]; then
	# Get config
	DATADIR="$("$@" --verbose --help 2>/dev/null | awk '$1 == "datadir" { print $2; exit }')"

	if [ ! -d "$DATADIR/mysql" ]; then

		mkdir -p "$DATADIR"
		chown -R mysql:mysql "$DATADIR"

		echo 'Initializing database'
		mysqld --initialize-insecure=on --datadir="$DATADIR"
		echo 'Database initialized'

		"$@" --skip-networking &
		pid="$!"

		mysql=( mysql --protocol=socket -uroot )

		for i in {30..0}; do
			if echo 'SELECT 1' | "${mysql[@]}" &> /dev/null; then
				break
			fi
			echo 'MySQL init process in progress...'
			sleep 1
		done

		"${mysql[@]}" <<-EOSQL
				-- What's done in this file shouldn't be replicated
				--  or products like mysql-fabric won't work
				SET @@SESSION.SQL_LOG_BIN=0;
				DELETE FROM mysql.user ;
				CREATE USER 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}' ;
				GRANT ALL ON *.* TO 'root'@'%' WITH GRANT OPTION ;
				DROP DATABASE IF EXISTS test ;
				FLUSH PRIVILEGES ;
		EOSQL

		# Insert external db and tables in mysql if it doesn't exist
		if [ -s "./MySQL_external_db_dump.sql.gz" ]
		then
			echo "Importing dump..."
			gunzip -c ./MySQL_external_db_dump.sql.gz | mysql -uroot -proot
			echo "Dump imported"
		fi
	fi
fi

exec "$@"
