#!/bin/bash

find ./ -path "./.git" -and -prune -or -type f |xargs file --mime-encoding | awk '{if($2 != "utf-8" && $2 != "us-ascii" && $2 != "binary"){print $0}}'
