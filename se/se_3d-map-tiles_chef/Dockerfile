FROM okapies/chef-solo

RUN uname -a

RUN apt-get update && apt-get -y install subversion

ENV RECIPE_PATH /var/chef/cookbooks/3DMapTiles

RUN mkdir /etc/chef
RUN echo "file_cache_path \"/var/chef-solo\"\ncookbook_path \"/var/chef-solo/cookbooks\n\"" > /etc/chef/solo.rb

WORKDIR /opt/

# currently FIWARE hosts an SVN repo for Chef recipes for GE/SE
RUN svn export --non-interactive --trust-server-cert  \
https://forge.fiware.org/scmrepos/svn/testbed/trunk/cookbooks/SESoftware/3DMapTiles/ $RECIPE_PATH  

RUN pwd
RUN ls -alh $RECIPE_PATH


# write Chef solo install script on the fly
RUN echo "{ \"run_list\" : \"recipe[3DMapTiles::4.1.3_install]\" }" > $RECIPE_PATH/install.js

RUN pwd
RUN ls -alh $RECIPE_PATH


# will clone git repo with SE and configure Apache
RUN chef-solo -j $RECIPE_PATH/install.js


EXPOSE 80 
