#include "select_sort.h"

int select_sort(int *data, int num)
{
    if(data == NULL ) {
        return FALSE;
    }

    int i,j,tmp,index;
    //swap = 1;

    for(i=0;i<num;i++) {
        tmp=data[i];
        index=i;

        for(j=i+1;j<num;j++) {
            if (data[j]<tmp) {
                tmp=data[j];
                index=j;
            }
        }

        if (index != i) {
            data[index] = data[i];
            data[i] = tmp;
        }

        show_data_tree(data, num);
    }

    return TRUE;
}
