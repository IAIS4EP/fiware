#!/bin/bash
set -e

echo
echo 'MySQL init process done. Ready for start up.'
echo

# Insert external db and tables in mysql if it doesn't exist
if [ -s "./MySQL_external_db_dump.sql.gz" ]
then
	gunzip -c ./MySQL_external_db_dump.sql.gz | mysql -uroot -proot
fi

exec "$@"	