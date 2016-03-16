/*
 * threaded.c -- A simple multi-threaded FastCGI application.
 */

#ifndef lint
static const char rcsid[] = "$Id: threaded.c,v 1.9 2001/11/20 03:23:21 robs Exp $";
#endif /* not lint */

#include <fcgi_config.h>

#include <pthread.h>
#include <sys/types.h>

#ifdef HAVE_UNISTD_H
#include <unistd.h>
#endif

#include <fcgiapp.h>
#include <fcgi_stdio.h>
#include <stdlib.h>


#define THREAD_COUNT 20

static int counts[THREAD_COUNT];

static void *doit(void *a)
{
    int rc, i, thread_id = (int)a;
    pid_t pid = getpid();
    FCGX_Request request;
    char *server_name;

    char input[1024];
    int maxline = 1024;

    FCGX_InitRequest(&request, 0, 0);

    for (;;)
    {
        static pthread_mutex_t accept_mutex = PTHREAD_MUTEX_INITIALIZER;
        static pthread_mutex_t counts_mutex = PTHREAD_MUTEX_INITIALIZER;

        /* Some platforms require accept() serialization, some don't.. */
        pthread_mutex_lock(&accept_mutex);
        rc = FCGX_Accept_r(&request);
        pthread_mutex_unlock(&accept_mutex);

        if (rc < 0)
            break;

        server_name = FCGX_GetParam("SERVER_NAME", request.envp);
//
//        FILE*fp;
//        int open_result;
//        if((fp=fopen("/tmp/fastcgi1","w"))==NULL)/*打开文件写模式*/
//        {
//            open_result = 0;
//            printf("cannotopenthefile.\n");/*判断文件是否正常打开*/
//            exit(0);
//        } else {
//            open_result = 1;
//        }

        int len = 0;
        char* content_length;
        content_length = FCGX_GetParam("CONTENT_LENGTH", request.envp);
        len = strtol(content_length, NULL, 10);
        FCGX_GetStr(input, maxline, request.in);
        input[len-1] = '\0';
//        FCGI_FILE *log;
//        log = FCGI_fopen("/tmp/fastcgi","w+");
//        FCGI_fputs("13132313", log);

        FCGX_FPrintF(request.out,
            "Content-type: text/html\r\n"
            "\r\n"
            "<title>FastCGI Hello! (multi-threaded C, fcgiapp library)</title>"
            "<h1>FastCGI Hello! (multi-threaded C, fcgiapp library)</h1>"
            "Thread %d, Process %ld<p>"
            "Request counts for %d threads running on host o<i>%s</i></p>"
            "<p>len : %d</p>"
            "<p>input : %s</p>",
            thread_id, pid, THREAD_COUNT, server_name ? server_name : "?",len, input);





        sleep(2);



        pthread_mutex_lock(&counts_mutex);
        ++counts[thread_id];
        for (i = 0; i < THREAD_COUNT; i++)
            FCGX_FPrintF(request.out, "%5d " , counts[i]);
        pthread_mutex_unlock(&counts_mutex);

        FCGX_Finish_r(&request);
    }

    return NULL;
}

int main(void)
{
    int i;
    pthread_t id[THREAD_COUNT];

    FCGX_Init();

    for (i = 1; i < THREAD_COUNT; i++)
        pthread_create(&id[i], NULL, doit, (void*)i);

    doit(0);

    return 0;
}


//int main(void){
//    while( FCGI_Accept() >= 0){
//        printf( "Content-Type: text/html\r\n" );
//        printf("\r\n");
//        printf( "Hello world in C\n" );
//    }
//}
