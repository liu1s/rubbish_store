#!/bin/bash

#gcc main.c tools.c bubble_sort.c -o main -g
gcc main.c tools.c select_sort.c -o main -g


gcc main.c tools.c bubble_sort.c select_sort.c quick_sort.c insertion_sort.c -o main -g
