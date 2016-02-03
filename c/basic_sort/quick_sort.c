#include "quick_sort.h"

int quick_sort(int *data, int left, int right, int num)
{
    if(data == NULL ) {
        return FALSE;
    }

    if (left >= right) {
        return TRUE;
    }

    int key = data[left];
    int low = left;
    int high = right;
    int tmp_low,tmp_high;

    while(low<high)
    {
        tmp_low = low;
        tmp_high = high;

        while(low<high && data[high] > key) {
            high--;
        }
        data[low]=data[high]; //小于key的数字移动到key左边
        while(low<high && data[low] < key) {
            low++;
        }
        data[high]=data[low]; //大于key的数据移动到key右边

        if (high == tmp_high && low == tmp_low) { //某个区间都是重复数
            high--;//减少high
        }
    }
    data[low] = key;  //left=right的时候，为key值

    show_data_tree(data, num);

    if (quick_sort(data,left,low-1,num) == FALSE){
        return FALSE;
    }

    if (quick_sort(data,low+1,right,num) == FALSE){
        return FALSE;
    }


    return TRUE;
}
