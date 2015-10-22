#!/bin/sh

array1=( [1]=1 [2]=2 [3]=3 )
array2[1]=4
array2[2]=5

echo $array1
echo ${array1}
echo ${#array1[@]}

echo ${#array2[@]}

php test.php ${array2[@]}
