# ./smoketest.sh 195.220.224.14 4444

#!/bin/sh
HOST="http://localhost";
PORT="80";

if [ -n "$1" ]; then
	HOST="$1";
fi

if [ -n "$2" ]; then
	PORT="$2";
fi

HOST_URL=$HOST:$PORT

echo "Running tests on http://$HOST_URL"

echo "Run smoke tests for german ContentOptimisationSE."
ANNOTATE_RESULT=`curl -X PUT -H "Content-Type:text/plain" -d 'Angela Merkel regiert Deutschlan von Berlin aus.' -s -o /dev/null -w "%{http_code}" $HOST_URL/de/ingest/optimize/iais/smoketest/justAText/2`
if [ "$ANNOTATE_RESULT" -ne "200" ]; then
        echo "Curl command for annotating text failed." 
        exit 1;
else
        echo "Curl command for annotating text successful."
fi

COMMIT_RESULT=`curl -s -o /dev/null -w "%{http_code}" $HOST_URL/de/solr/search/update?commit=true`
if [ "$COMMIT_RESULT" -ne "200" ]; then
        echo "Curl command for updating index failed."
        exit 1;
else
        echo "Curl command for updating index successful."
fi

SEARCH_RESULT=`curl -s -o /dev/null -w "%{http_code}" $HOST_URL/de/search?query=Angela&rows=10`
if [ "$SEARCH_RESULT" -ne "200" ]; then
        echo "Curl command for search items failed."
        exit 1;
else
        echo "Curl command for search items successful."
fi

ITEM_RESULT=`curl -H "Content-Type:application/json" -s -o /dev/null -w "%{http_code}" $HOST_URL/de/items/2ACA2R3JVEMY6QLEO3X2EIVX5XRBTEFE`
if [ "$ITEM_RESULT" -ne "200" ]; then
        echo "Curl command for retrieval of item failed."
        exit 1;
else
        echo "Curl command for retrieval of item successful."
fi

DELETE_RESULT=`curl -X DELETE -H "Content-Type:application/json" -s -o /dev/null -w "%{http_code}" $HOST_URL/de/deleteItem/2ACA2R3JVEMY6QLEO3X2EIVX5XRBTEFE`
if [ "$DELETE_RESULT" -ne "200" ]; then
        echo "Curl command for deletion of item failed."
        exit 1;
else
        echo "Curl command for deletion of item successful."
fi

echo "Run smoke tests for english ContentOptimisationSE."
ANNOTATE_RESULT=`curl -X PUT -H "Content-Type:text/plain" -d 'Barak Obama works at the White House in Washngton,D.C..' -s -o /dev/null -w "%{http_code}" $HOST_URL/en/ingest/optimize/iais/smoketest/justAText/2`
if [ "$ANNOTATE_RESULT" -ne "200" ]; then
        echo "Curl command for annotating text failed."
        exit 1;
else
        echo "Curl command for annotating text successful."
fi

COMMIT_RESULT=`curl -s -o /dev/null -w "%{http_code}" $HOST_URL/en/solr/search/update?commit=true`
if [ "$COMMIT_RESULT" -ne "200" ]; then
        echo "Curl command for updating index failed."
        exit 1;
else
        echo "Curl command for updating index successful."
fi

SEARCH_RESULT=`curl -s -o /dev/null -w "%{http_code}" $HOST_URL/en/search?query=Angela&rows=10`
if [ "$SEARCH_RESULT" -ne "200" ]; then
        echo "Curl command for search items failed."
        exit 1;
else
        echo "Curl command for search items successful."
fi

ITEM_RESULT=`curl -H "Content-Type:application/json" -s -o /dev/null -w "%{http_code}" $HOST_URL/en/items/2ACA2R3JVEMY6QLEO3X2EIVX5XRBTEFE`
if [ "$ITEM_RESULT" -ne "200" ]; then
        echo "Curl command for retrieval of item failed."
        exit 1;
else
        echo "Curl command for retrieval of item successful."
fi

DELETE_RESULT=`curl -X DELETE -H "Content-Type:application/json" -s -o /dev/null -w "%{http_code}" $HOST_URL/en/deleteItem/2ACA2R3JVEMY6QLEO3X2EIVX5XRBTEFE`
if [ "$DELETE_RESULT" -ne "200" ]; then
        echo "Curl command for deletion of item failed."
        exit 1;
else
        echo "Curl command for deletion of item successful."
fi
