#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <time.h>

#include "tools.h"
#include "bubble_sort.h"
#include "select_sort.h"
#include "quick_sort.h"
#include "insertion_sort.h"

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

//    init_data[0] = 13;
//    init_data[1] = 11;
//    init_data[2] = 10;
//    num = 3;

    show_data_tree(init_data, num);


//    pid_t fpid;
//    fpid=fork();
//    if (fpid < 0)
//        printf("error in fork!");
//    else if (fpid == 0) {
//        while(1){
//            show_data_tree(init_data, num);
//        }
//    }
//    else {
//        if (select_sort(init_data, num) == FALSE) {
//            printf("sort fail.\r\n");
//            return 1;
//        }
//    }

//    if (bubble_sort(init_data, num) == FALSE) {
//        printf("sort fail.\r\n");
//    }

//    if (quick_sort(init_data, 0, num-1, num) == FALSE) {
//        printf("sort fail.\r\n");
//    }

    insertion_sort(init_data, num);

    return 0;
}


