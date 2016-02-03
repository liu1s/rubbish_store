#include "tools.h"

void show_data_tree(int *data, int num)
{
    CLEAR();

    int i,j;
    for(i=0;i<num;i++) {
        for(j=0;j<data[i];j++) {
            printf("*");
        }
        printf("\r\n");
    }
    //printf("------\r\n");

//    for(i=0;i<num;i++) {
//        printf("\033[1A"); //先回到上一行
//        printf("\033[K");  //清除该行
//    }

//    sleep(1);
    usleep(100000);
}
