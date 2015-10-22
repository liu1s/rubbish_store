#include <stdio.h>
#include "extern_test.c"

extern e;

void main()
{
    //int e = 2;
    /*extern int e;*/
    printf("%s %d \n","main e=",e);
    changeE();
}
