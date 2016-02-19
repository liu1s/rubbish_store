#include    "unp.h"

//#include <stdlib.h>
//#include <time.h>
//#include <sys/types.h>
//#include <sys/socket.h>
//#include <stdio.h>
//#include <string.h>
////#include <linux/in.h>
//#include <netinet/in.h>
//#include <stdarg.h>
//
//
//#define MAXLINE 1000
//#define LISTENQ 10
//#define SA struct sockaddr


int
main(int argc, char **argv)
{
    int                 sockfd, n;
    char                recvline[MAXLINE + 1];
    struct sockaddr_in  servaddr;

    if (argc != 2)
        err_quit("usage: a.out <IPaddress>");

    if ( (sockfd = socket(AF_INET, SOCK_STREAM, 0)) < 0)
        err_sys("socket error");

    bzero(&servaddr, sizeof(servaddr));
    servaddr.sin_family = AF_INET;
    servaddr.sin_port   = htons(7744);    /* daytime server */
    if (inet_pton(AF_INET, argv[1], &servaddr.sin_addr) <= 0)
    //if (inet_pton(AF_INET, "127.0.0.1", &servaddr.sin_addr) <= 0)
        err_quit("inet_pton error for %s", argv[1]);

    if (connect(sockfd, (SA *) &servaddr, sizeof(servaddr)) < 0)
        err_sys("connect error");

    char msg[] = "this is from client/n";
    send(sockfd, msg, strlen(msg),0);

    while ( (n = read(sockfd, recvline, MAXLINE)) > 0) {
        recvline[n] = 0;    /* null terminate */
        if (fputs(recvline, stdout) == EOF)
            err_sys("fputs error");
    }
    if (n < 0)
        err_sys("read error");

    close(sockfd);
    exit(0);
}
