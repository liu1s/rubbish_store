/*以_连接2个字符串*/
char *_mlwl_str_join_with_symbol(char *a, char *b) {
    char *c = (char *) emalloc(strlen(a) + strlen(b)  + 2); //局部变量，用malloc申请内存
    if (c == NULL) exit (1);
    char *tempc = c; //把首地址存下来
    while (*a != '\0') {
        *c++ = *a++;
    }
    *c++ = '_';
    while ((*c++ = *b++) != '\0') {
        ;
    }
    //注意，此时指针c已经指向拼接之后的字符串的结尾'\0' !
    return tempc;//返回值是局部malloc申请的指针变量，需在函数调用结束后free之
}
