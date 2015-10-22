#include <stdio.h>
#define MAXSORTLENGTH 10

int binarySearch(int *, int ,int *, int, int);

int binarySearch(int *list, int search,int *position, int start, int end)
{
    float tmp_position;

    tmp_position = (start + end)/2;
    *position = (int)tmp_position;

    if (end - start == 1) {
        if ( list[start] == search){
            *position == start;
        } else if ( list[start] == end){
            *position == end;
        } else {
            *position == -1;
        }
        
        return 1;
    } else if ( list[*position] == search){
        return 1;
    } else {
            if (list[*position] > search) {
                    binarySearch(list, search, position, start, *position);
            } else {
                    binarySearch(list, search, position, *position, end);
            }
    
    }
}
void main()
{
    int a[10] = {1,2,3,4,5,6,7,8,9,10};
    int *position;
    binarySearch(a, 1, position, 0, 10);
    
    printf("%d",*position);
}

