SLEEPTIME=30

mkdir OrionCBTesting
cd OrionCBTesting
echo "Getting the docker compose file from https://raw.githubusercontent.com/telefonicaid/fiware-orion/develop/docker-compose.yml"
wget https://raw.githubusercontent.com/telefonicaid/fiware-orion/develop/docker/docker-compose.yml
docker-compose up -d
sleep $SLEEPTIME

wget https://bitbucket.org/eglobalmark/egm_testexecorioncb/raw/b69758c3aa5f386e8e30d783d92c93a8e6783a49/testExecOrionCB.jar
java -jar testExecOrionCB.jar -p 1026 -ip http://127.0.0.1 -o http://207.249.127.73:7070/report -t 2 -header Fiware-Service#egmtestexectests@Fiware-ServicePath#/egmtestexectests

docker rm -f OrionCBTesting_mongo_1
docker rm -f OrionCBTesting_orion_1

cd ..
rm -r OrionCBTesting

