
Setting up object storage on Amazon EC2
===

This guide has been authored by our team to help users get Openstack Swift (hereby abbreviated to `OSS`) set up quickly.
What follows is a modified version of the way the our team deploys it's own instances.


EC2 Setup and configuration
---

First, you will need to launch a new instance on Amazon's EC2 scalable cloud service.
We recommend using the `Ubuntu Server 14.04 LTS (HVM), SSD Volume Type` image as the base.
The instructions below have been tested using this base image only.

For the instance type, we recommend using a `Memory optimised` instance, as we have found that to give the best performance with OSS.

The security group should be set to open ports `22, 43, 6000-6100`
The default storage option will work fine.


Preparing a base image
---

Once the instance is launched, you will need to connect via ssh and run the following commands to install OSS.

```
ssh ubuntu@<public-ip> -i <path/to/keyfile>

sudo su
add-apt-repository ppa:swift-core/release
apt-get update
apt-get install swift
mkdir -p /etc/swift
echo -e "[swift-hash]\nswift_hash_path_suffix = `openssl rand -base64 32`" > /etc/swift/swift.conf
chown -R swift:swift /etc/swift
```

Now return to the AWS EC2 console. From here, right click on the instance you are using, and select `image > create image`.
Set the name to something like `Openstack swift base image` and start the image creation. You may need to wait a few minutes for the image to be created.

Now you can go to the AMI section of the AWS console and launch new instances with the OSS image you have created.
You will need at least two, one to run object storage and one to act as the proxy for incoming requests.
The two types of servers will hereby be referred to as the 'proxy' server or the 'storage' server(s).


Configuring the proxy server
---

During the image creation, the proxy server will have rebooted and ended your ssh session. Reconnect and run the following commands:

```
ssh ubuntu@<proxy-public-ip> -i <path/to/keyfile>
sudo su
apt-get install swift-proxy memcached swauth
cd /etc/swift
openssl req -new -x509 -nodes -out cert.crt -keyout cert.key

export PROXY_LOCAL_NET_IP=`curl http://169.254.169.254/latest/meta-data/local-ipv4`
perl -pi -e "s/-l 127.0.0.1/-l $PROXY_LOCAL_NET_IP/" /etc/memcached.conf
service memcached restart
```

Edit the file `/etc/swift/proxy-server.conf` and add the following:

```
[DEFAULT]
bind_port = 443
cert_file = /etc/swift/cert.crt
key_file = /etc/swift/cert.key
workers = 8
user = swift

[pipeline:main]
pipeline = healthcheck cache swauth proxy-server

[app:proxy-server]
use = egg:swift#proxy
allow_account_management = true

[filter:swauth]
use = egg:swauth#swauth
set log_name = swauth
super_admin_key = swauthkey
default_swift_cluster = https://<public-proxy-ip>:443/v1

[filter:healthcheck]
use = egg:swift#healthcheck

[filter:cache]
use = egg:swift#memcache
memcache_servers = <local-proxy-ip>:11211
```

Remember to replace `<public-proxy-ip>` and `<local-proxy-ip>`.

Now run the following:

```
cd /etc/swift
swift-ring-builder account.builder create 18 3 1
swift-ring-builder container.builder create 18 3 1
swift-ring-builder object.builder create 18 3 1
chown -R swift:swift /etc/swift
```

Configuring the storage server
---

Now connect to your storage instance and run the following commands

```
ssh ubuntu@<storage-public-ip> -i <path/to/keyfile>

sudo su
apt-get install swift-account swift-container swift-object xfsprogs
mkdir /var/data
```

Edit the file `/etc/rsyncd.conf` and add the following lines:

```
uid = swift
gid = swift
log file = /var/log/rsyncd.log
pid file = /var/run/rsyncd.pid
address = <storage-local-ip>

[account]
max connections = 2
path = /var/data/
read only = false
lock file = /var/lock/account.lock

[container]
max connections = 2
path = /var/data/
read only = false
lock file = /var/lock/container.lock

[object]
max connections = 2
path = /var/data/
read only = false
lock file = /var/lock/object.lock
```

You can use `curl http://169.254.169.254/latest/meta-data/local-ipv4` to retrieve the local machine IP address.

Edit the file `/etc/default/rsync` and change `RSYNC_ENABLE` to true

Start rsync `service rsync start`

Edit the files `/etc/swift/account-server.conf`, `/etc/swift/container-server.conf` and `/etc/swift/object-server.conf` and set the `bind_ip` to the local address.

Linking the two servers
---

Now switch back to the proxy server.

Create the file `/etc/swift/build_rings.sh` and add the following lines:

```
#!/bin/sh
export ZONE=1
export STORAGE_LOCAL_NET_IP=<storage-public-ip>
export WEIGHT=100
export DEVICE=sdf1
sudo swift-ring-builder account.builder add z$ZONE-$STORAGE_LOCAL_NET_IP:6002/$DEVICE $WEIGHT
sudo swift-ring-builder container.builder add z$ZONE-$STORAGE_LOCAL_NET_IP:6001/$DEVICE $WEIGHT
sudo swift-ring-builder object.builder add z$ZONE-$STORAGE_LOCAL_NET_IP:6000/$DEVICE $WEIGHT
```

Then run the following commands:

```
chmod +x /etc/swift/build_rings.sh
/etc/swift/build_rings.sh
swift-ring-builder account.builder
swift-ring-builder container.builder
swift-ring-builder object.builder
swift-ring-builder account.builder rebalance
swift-ring-builder container.builder rebalance
swift-ring-builder object.builder rebalance
```

From your host machine run the following command:

```
mkdir -p /tmp/swift/ && \
    rsync -avz -e ssh fiware-tut-proxy:/etc/swift/*.gz /tmp/swift/ && \
    rsync -avz -e ssh /tmp/swift/*.gz fiware-tut-storage:/tmp/swift/ && \
    rm -r /tmp/swift
```

This will copy the ring files to your local machine, then to the storage server.
On the storage server, now run:

```
mv /tmp/swift/* /etc/swift/
chown -R swift:swift /etc/swift
swift-init all start
```

On the proxy server, now run:

```
sudo chown -R swift:swift /etc/swift
sudo swift-init proxy start
```

If the last command fails to start, try removing the `default_swift_cluster` line from `proxy-server.conf`

Set up authentication
---

```
swauth-prep -K swauthkey -A https://127.0.0.1/auth/
swauth-add-user -K swauthkey -A https://127.0.0.1/auth/ -a <user> <pass> <key>
```

Now lanch a third EC2 instance to act as your client (or use an existing machine). We tested with the same Ubuntu 14.04 base image.

```
sudo add-apt-repository ppa:swift-core/release
sudo apt-get update
sudo apt-get install swift
curl -k -v -H 'X-Storage-User: <user>:<pass>' -H 'X-Storage-Pass: <key>' https://<proxy-public-ip>/auth/v1.0
```

The response should contain an `X-Auth-Token` header. Take the value of this header to use for future authentication.
The response should be a json formatted packet containing a key-val pair "cluster_name". Take the value of this to use below.

```
curl -k -v -H 'X-Auth-Token: <auth-token>' <cluster_name>
swift -A https://<proxy-public-ip>/auth/v1.0 -U <user>:<pass> -K <key> upload test <file>
```

You are now authenticated and able to use the swift cluster. Launching new storage nodes can be automated for better scalability.
