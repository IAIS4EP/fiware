#!/bin/bash
WAITFOR=30
echo "WAITFOR time defined = $WAITFOR"

echo "Creating test Directory"
mkdir ./orioncbtesting
cd orioncbtesting

echo "########## Actualy going to run in $(pwd) ##########"
echo "Getting the docker compose file from https://raw.githubusercontent.com/telefonicaid/fiware-orion/develop/docker-compose.yml"
wget https://raw.githubusercontent.com/telefonicaid/fiware-orion/develop/docker/docker-compose.yml
docker-compose up -d
echo "Going to sleep : Give time for docker instances to spawn"
sleep $WAITFOR

echo "Downloading the EGM functional test"
wget https://bitbucket.org/eglobalmark/egm_testexecorioncb/raw/b69758c3aa5f386e8e30d783d92c93a8e6783a49/testExecOrionCB.jar

echo "Executing the EGM functional test on 127.0.0.1:1026 (Orion instance) and reporting tests result to : http://207.249.127.73:7070/report"
java -jar testExecOrionCB.jar -p 1026 -ip http://127.0.0.1 -o http://207.249.127.73:7070/report -t 2 -header Fiware-Service#egmtestexectests@Fiware-ServicePath#/egmtestexectests

STATUS="${?}" ;
echo "Global Exit Code of Tests : ${STATUS}"

echo "removing Docker instances created..."
docker rm -f orioncbtesting_mongo_1
docker rm -f orioncbtesting_orion_1

echo "Deleting created directory"
cd ..
rm -r ./orioncbtesting

echo "Bye bye! Exit with status code ${STATUS}"
exit $STATUS
