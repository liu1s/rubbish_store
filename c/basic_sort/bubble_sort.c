#include "bubble_sort.h"

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
