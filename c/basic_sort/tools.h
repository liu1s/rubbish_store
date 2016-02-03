#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

#define TRUE 0
#define FALSE 1


#define CLEAR() printf("\033[2J")

void show_data_tree(int*, int);
