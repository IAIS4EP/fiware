FROM linuxkonsult/chef-solo

# official FIWARE Chef cookbook
COOKBOOK_URL="https://forge.fiware.org/scmrepos/svn/testbed/trunk/cookbooks/GESoftware/IoTBroker/"
RUN apt-get install svn

RUN svn export $COOKBOOK_URL

# and then?..
RUN chef-solo ####
