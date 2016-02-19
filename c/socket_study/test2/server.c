#include    "unp.h"

#define MAXRECVLEN 1024

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
    servaddr.sin_addr.s_addr = htonl(INADDR_ANY);
    servaddr.sin_port = htons(7744); /* daytime server */

    Bind(listenfd, (SA *) &servaddr, sizeof(servaddr));

    Listen(listenfd, LISTENQ);

    for ( ; ; ) {
        len = sizeof(cliaddr);
        connfd = Accept(listenfd, (SA *) &cliaddr, &len);

        //获取信息来源地址
        Inet_ntop(AF_INET, &cliaddr.sin_addr, buff, sizeof(buff));
        //inet_ntop(AF_INET, &cliaddr.sin_addr, buff, sizeof(buff));
        printf("connection from %s, port %d\n",buff,ntohs(cliaddr.sin_port));

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

        //回复服务器当前时间
        ticks = time(NULL);
        snprintf(buff, sizeof(buff), "%.24s\r\n", ctime(&ticks));
        write(connfd, buff, strlen(buff));

        close(connfd);
    }

}
