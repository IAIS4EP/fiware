docker build -t fiware/swift-demo .
docker run -d --name=swift_demo -p 9098:80 fiware/swift-demo

