#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <time.h>

#define TRUE 0
#define FALSE 1

/**
 * 升序排序
 * 第一个参数 需要排序数组
 * 第二个参数 排序数组个数
 */
int bubble_sort(int*,int);

void show_data_tree(int*, int);

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

    if (bubble_sort(init_data, num) == FALSE) {
        printf("sort fail.\r\n");
    }

    return 0;
}

int bubble_sort(int *data, int num)
{
    if(data == NULL ) {
        return FALSE;
    }

    int i,j,tmp,swap;
    swap = 1;

    for(i=num;i>0;i--) {
        if ( swap == 0 ){
            break;
        }

        swap = 0;
        for(j=0;j<i-1;j++) {
            if (data[j] > data[j+1]) {
                tmp = data[j];
                data[j] = data[j+1];
                data[j+1] = tmp;

                swap = 1;
            }
        }

        if(swap == 1) {
            show_data_tree(data, num);
        }
    }

    return TRUE;
}

void show_data_tree(int *data, int num)
{
    int i,j;
    for(i=0;i<num;i++) {
        for(j=0;j<data[i];j++) {
            printf("*");
        }
        printf("\r\n");
    }
    //printf("------\r\n");

    for(i=0;i<num;i++) {
        printf("\033[1A"); //先回到上一行
        printf("\033[K");  //清除该行
    }

//    sleep(1);
    usleep(100000);
}
