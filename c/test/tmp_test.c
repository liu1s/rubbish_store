#include <string.h>
#include <stdlib.h>
#include <stdio.h>
char *replacestr();
typedef struct _mlwl_autoid_conf {
        char host[16]; //xxx.xxx.xxx.xxx
            char port[5];
} mlwl_autoid_conf;
void _phase_test(mlwl_autoid_conf *);

void _phase_test(mlwl_autoid_conf *dest_conf){
        strcpy(dest_conf->host,"1231313");
}

void main(){
mlwl_autoid_conf *master_conf;
master_conf = malloc(sizeof(mlwl_autoid_conf));
_phase_test(master_conf);
printf("%s",master_conf->host);
}


// 替换字符串中特征字符串为指定字符串
/*
descript:replace str,返回一个替换以后的字符串,用完之后要free()
success:return 1
error:return 0
BUG:"select * from tab where id=':a' and name =':aa'",this is not support,this function is just simple str_replace ,not support all SQL language
 */
char *replacestr(char *strbuf, char *sstr, char *dstr)
{
    char *p,*p1;
    int len;

    if ((strbuf == NULL)||(sstr == NULL)||(dstr == NULL))
        return NULL;

    p = strstr(strbuf, sstr);       //返回字符串第一次出现的地址,否则返回NULL
    if (p == NULL)  /*not found*/
        return NULL;

    len = strlen(strbuf) + strlen(dstr) - strlen(sstr);
    p1 = malloc(len);
    bzero(p1, len);
    strncpy(p1, strbuf, p-strbuf);
    strcat(p1, dstr);
    p += strlen(sstr);
    strcat(p1, p);
    return p1;
}
