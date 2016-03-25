apt-get update
apt-get install docker.io -y
apt-get install git -y
cd ~
git clone https://github.com/IAIS4EP/fiware.git
cd fiware/ge_object_storage/
echo "---- INSTALLATION COMPLETED"
sudo service docker start
echo "---- BUILDING CONTAINER..."
docker build -t pbinkley/docker-swift .
docker run -P -v /path/to/data:/swift/nodes -t pbinkley/docker-swift
echo "---------------------------"
echo "---------------------------"
echo "---- CONTAINER RUNNING! :) "
echo "---------------------------"
echo "---------------------------"
docker ps
echo "---------------------------"
echo "---------------------------"
echo "1. Please take note of the port mapped to port 8080 from the results above"
echo "2. Now you can get the authorisation code by running this command -->"
echo "curl -v -H 'X-Storage-User: test:tester' -H 'X-Storage-Pass: testing' http://127.0.0.1:<port>/auth/v1.0"
echo "3. Take note of URL=<url> and TOKEN=<token>"
echo "4. Use those values for any conscutive requests or to run the demo.sh script"
echo "---------------------------"
echo "--------------------------- ALL DONE :)"
