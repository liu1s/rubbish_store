#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <time.h>

#include "tools.h"
#include "bubble_sort.h"
#include "select_sort.h"


int main(int argc, char* argv[])
{
    int num,i;
    int init_data[10000];

    if (argc < 2) {
        printf("请输入排序数目");
        return 1;
    }
    sscanf(argv[1],"%d", &num);

    srand( (unsigned)time( NULL ) );
    for(i=0;i<num;i++) {
        init_data[i] = rand()%100+1;
    }

    //int init_data[10] = {10,9,8,7,6,5,4,3,2,1};

    show_data_tree(init_data, num);

//    if (bubble_sort(init_data, num) == FALSE) {
//        printf("sort fail.\r\n");
//    }

    if (select_sort(init_data, num) == FALSE) {
        printf("sort fail.\r\n");
    }

    return 0;
}


