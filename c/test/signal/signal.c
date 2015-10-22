#include <stdio.h>
#include <signal.h>
char **args;

void catch_signal(int sig)
{
    printf("%s : signal %d received ",args[1], sig);
}

int main(int argc,char **argv)
{
        args = argv;
        signal(SIGHUP,catch_signal);
        signal(SIGINT,catch_signal);
        signal(SIGQUIT,catch_signal);
        pause();
        return 0;
}