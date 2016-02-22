##simple makefile

tag = server client sigchldwaitpid.o tcpserver tcpclient

all: ${tag}

CC = gcc
CFLAGS = -I../unpv13e/lib -g -O2 -D_REENTRANT -Wall

#COMMON_FILE = ../unpv13e/lib/error.c ../unpv13e/lib/wrapsock.c ../unpv13e/lib/wraplib.c

LIBS = ../unpv13e/libunp.a -lpthread


server: server.c 
#	${CC} ${CFLAGS} server.c ${COMMON_FILE} -o $@ -g ${LIBS}
	${CC} ${CFLAGS} server.c  -o $@ ${LIBS}
	
client: client.c 
#	${CC} ${CFLAGS} client.c ${COMMON_FILE} -o $@ -g ${LIBS}
	${CC} ${CFLAGS} client.c -o $@ ${LIBS}

sigchldwaitpid.o: sigchldwaitpid.c
	${CC} ${CFLAGS} sigchldwaitpid.c -c  ${LIBS}
	
tcpserver: tcpserver.c 
	${CC} ${CFLAGS} tcpserver.c sigchldwaitpid.o -o $@ -g ${LIBS}
	
tcpclient: tcpclient.c 
	${CC} ${CFLAGS} tcpclient.c -o $@ ${LIBS}
	
clean: 
	rm ${tag}