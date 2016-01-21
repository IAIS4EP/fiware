#!/bin/sh

HOST=$1
PORT=$2
ROOT_URL_NO_SLASH="http://$HOST:$PORT"

echo "Running smoke tests for the Audio Mining SE (German) at $ROOT_URL_NO_SLASH"

LIST_INDEXES_RESULT=`curl -H "Content-Type:application/json" -H "Accept:application/json" -s -o /dev/null -w "%{http_code}" ${ROOT_URL_NO_SLASH}/`
if [ "$LIST_INDEXES_RESULT" -ne "200" ]; then
        echo "Listing the indexes failed." 
        exit 1;
else
        echo "Listing the indexes successful."
fi

CREATE_INDEX_RESULT=`curl -X POST -H "Content-Type:application/json" -H "Accept:application/json" -s -o /dev/null -w "%{http_code}" ${ROOT_URL_NO_SLASH}/?indexid=smoketest&language=German`
if [ "$CREATE_INDEX_RESULT" -ne "201" ]; then
        echo "Creating an index failed." 
        exit 1;
else
        echo "Creating an index successful."
fi

DELETE_INDEX_RESULT=`curl -X DELETE -H "Content-Type:application/json" -H "Accept:application/json" -s -o /dev/null -w "%{http_code}" ${ROOT_URL_NO_SLASH}/smoketest/`
if [ "$DELETE_INDEX_RESULT" -ne "200" ]; then
        echo "Deleting an index failed." 
        exit 1;
else
        echo "Deleting an index successful."
fi
