#/bin/bash

grep -r -l [pattern1] ./* | xargs sed -e 's/[pattern1]/[pattern2]/g'
