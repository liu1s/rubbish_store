#!/bin/bash

spawn-fcgi -a 127.0.0.1 -p 9002 -C 25 -f ./fastcgi_server.fcgi -n
