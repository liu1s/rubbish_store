/*
#include	"unp.h"
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
//#define INADDR_ANY "127.0.0.1"

int 
main(int argc, char **argv)
{
	int					listenfd, connfd;
	socklen_t			len;
	struct sockaddr_in	servaddr, cliaddr;
	char				buff[MAXLINE];
	time_t				ticks;

	listenfd = socket(AF_INET, SOCK_STREAM, 0);

	bzero(&servaddr, sizeof(servaddr));
	servaddr.sin_family      = AF_INET;
	/*servaddr.sin_addr.s_addr = htonl(INADDR_ANY);*/
	servaddr.sin_addr.s_addr = inet_addr("127.0.0.1");
	servaddr.sin_port        = htons(7744);	/* daytime server */

	bind(listenfd, (SA *) &servaddr, sizeof(servaddr));

	listen(listenfd, LISTENQ);

	for ( ; ; ) {
		len = sizeof(cliaddr);
		connfd = accept(listenfd, (SA *) &cliaddr, &len);
		printf("connection from %d, port %d\n",
			   inet_ntop(AF_INET, &cliaddr.sin_addr, buff, sizeof(buff)),
			   ntohs(cliaddr.sin_port));

        ticks = time(NULL);
        //snprintf(buff, sizeof(buff), "%.24s\r\n", ctime(&ticks));
        write(connfd, buff, strlen(buff));

		close(connfd);
	}
}
