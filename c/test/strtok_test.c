#include <string.h>
main(){
    char s[] = "ab-cd : ef;gh :i-jkl;mnop;qrs-tu: vwx-y;z";
    char *delim = "-: ";
    char *p;
    printf("%s ", strtok(s, delim));
    printf("%s ", "-------");
    printf("%s ", s);
    /*
    while((p = strtok(NULL, delim)))
        printf("%s ", p);
    printf("\n");
    */
}
