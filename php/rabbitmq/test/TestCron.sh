#!/bin/bash

for((i=1;i<10000;i++));do
    php ../public/script.php TestRabbitRouting > /dev/null &
done