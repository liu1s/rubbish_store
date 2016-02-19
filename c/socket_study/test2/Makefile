##simple makefile

#all: error.o server client
all: server client

CC = gcc
CFLAGS = -I../unpv13e/lib -g -O2 -D_REENTRANT -Wall

#COMMON_FILE = ../unpv13e/lib/error.c ../unpv13e/lib/wrapsock.c ../unpv13e/lib/wraplib.c

LIBS = ../unpv13e/libunp.a -lpthread

#error.o: ../unpv13e/sock/error.c


server: server.c 
#	${CC} ${CFLAGS} server.c ${COMMON_FILE} -o $@ -g ${LIBS}
	${CC} ${CFLAGS} server.c  -o $@ -g ${LIBS}
	
client: client.c 
#	${CC} ${CFLAGS} client.c ${COMMON_FILE} -o $@ -g ${LIBS}
	${CC} ${CFLAGS} client.c -o $@ -g ${LIBS}

#server client: client.c server.c ${common_file}
	#gcc $@.c changed_error.o  -o $@ -g
	
clean: 
	rm server client 	