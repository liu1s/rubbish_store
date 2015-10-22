#include <string.h>
#include <stdlib.h>
#include <stdio.h>
int main(void){
    static char s[1000];
    strncpy(s, "get lock fail",strlen("get lock fail"));
    printf("%s\n",s);
}


