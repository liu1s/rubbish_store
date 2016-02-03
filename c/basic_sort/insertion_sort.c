#include "bubble_sort.h"

int insertion_sort(int *data, int num)
{
    if(data == NULL ) {
        return FALSE;
    }

    int i,j,tmp,tmp_i;

    for(i=0;i<num;i++) {
        tmp_i = i;

        //找到需要插入的位置j
        j = i;
        while(j > 0 && data[j] >= data[i])
        {
            if (data[j-1] < data[i]) {
                break;
            }

            j--;
        }

        if (j < i) {
            //把j设置为原data[i]，j-i全部往后移动一步
            tmp = data[i];
            while(tmp_i>j) {
                data[tmp_i] = data[tmp_i-1];
                tmp_i--;
            }
            data[j] = tmp;
        }

        show_data_tree(data, num);
    }

    return TRUE;
}
