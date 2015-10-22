#include <stdio.h>
#include <string.h>
#include <unistd.h> /*for getopt function*/
#include <io.h>

char filename[50];

int  main(int argc,char* argv[])
{
    char character;
    FILE * fp;

    /*解析参数获取文件名*/
    setOpn(argc,argv);
    if(filename[0] == 0){
        printf("cat_ -f [filename]\n");
    }

    /*check file if exist*/
    if(access(filename,0) == -1){
        printf("file not exsit!");
        return 0;
    }
    
    /*char fileName[50] = *argv;*/
    fp = fopen(filename,"rb");
    if(fp == NULL){
        printf("open file fail!");
        return 0;
    }

    while((character=fgetc(fp)) != EOF )
    {
        printf("%c",character);
        //if(character == 10){
        //   putchar('$');
        //}
    }
        /*putchar(character);*/

    fclose(fp);
    return 0;
}

/*解析命令行参数*/
int setOpn(int argc,char* argv[])
{
    extern char *optarg;  //选项的参数指针
    extern int optind;   //下一次调用getopt的时，从optind存储的位置处重新开始检查选项。 
    extern int opterr;  //当opterr=0时，getopt不向stderr输出错误信息。
    extern int optopt;  //当命令行选项字符不包括在optstring中或者选项缺少必要的参数时，该选项存储在optopt 中，getopt返回'？’
    int ch;
    while ((ch = getopt(argc,argv,"f:")) != -1)
    {
        switch(ch)
        {
            case 'f':
                strcpy(filename,optarg);
                break;
            default:
                break;
        }
    }
    return 1;
}
