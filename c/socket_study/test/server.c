/*
#include    "unp.h"
*/
#include <stdlib.h>
#include <time.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <stdio.h>
#include <string.h>
//#include <linux/in.h>
#include <netinet/in.h>



#define MAXLINE 1000
#define LISTENQ 10
#define SA struct sockaddr

#define MAXRECVLEN 1024
//#define INADDR_ANY "127.0.0.1"

int
main(int argc, char **argv)
{
    int                 listenfd, connfd;
    socklen_t           len;
    struct sockaddr_in  servaddr, cliaddr;
    char                buff[MAXLINE], revieve_buff[MAXLINE];
    time_t              ticks;

    listenfd = socket(AF_INET, SOCK_STREAM, 0);

    bzero(&servaddr, sizeof(servaddr));
    servaddr.sin_family      = AF_INET;
    /*servaddr.sin_addr.s_addr = htonl(INADDR_ANY);*/
    //servaddr.sin_addr.s_addr = inet_addr("127.0.0.1"); //inet_addr 已被弃用
    //inet_aton("127.0.0.1", servaddr.sin_addr.s_addr); //inet_pton的兼容性比inet_aton好
    servaddr.sin_port = htons(7744); /* daytime server */

    if (inet_pton(AF_INET, "127.0.0.1", &servaddr.sin_addr) <= 0)
            err_quit("inet_pton error for %s", argv[1]);

    if (bind(listenfd, (SA *) &servaddr, sizeof(servaddr)) < 0)
            err_sys("bind error");

    if (listen(listenfd, LISTENQ) < 0)
            err_sys("listen error");

    for ( ; ; ) {
        len = sizeof(cliaddr);
        connfd = accept(listenfd, (SA *) &cliaddr, &len);

        //获取信息来源地址
        inet_ntop(AF_INET, &cliaddr.sin_addr, buff, sizeof(buff));
        printf("connection from %d, port %d\n",buff,ntohs(cliaddr.sin_port));

        //获取传输内容
        int iret = -1;
        iret = recv(connfd, revieve_buff, MAXRECVLEN, 0);
        printf("iret:%d\n", iret);
        if(iret>0)
        {
            printf("from client : %s\n", revieve_buff);
        }else
        {
            break;
        }

        /*
         * 如果循环则需等到接受client端数据完全结束为止
         */
//        while(1)
//        {
//            iret = recv(connfd, revieve_buff, MAXRECVLEN, 0);
//            printf("iret:%d\n", iret);
//            if(iret>0)
//            {
//                printf("from client : %s\n", revieve_buff);
//            }else
//            {
//                break;
//            }
//
//           // send(connfd, buff, iret, 0); /* send to the client welcome message */
//        }

        //回复服务器当前时间
        ticks = time(NULL);
        snprintf(buff, sizeof(buff), "%.24s\r\n", ctime(&ticks));
        write(connfd, buff, strlen(buff));

        close(connfd);
    }

}
