#!/usr/bin/env python

import sys
import ckanapi

# ckan address
ckan_address = 'http://'+sys.argv[1]

demo = ckanapi.RemoteCKAN(ckan_address)

if demo:
    print demo.user_agent
else:
    pass
