#!/bin/bash

cd /data/backup/gistore_source;
gistore commit --repo nginx_backup.git;

cd /data/backup/nginx;
git pull;