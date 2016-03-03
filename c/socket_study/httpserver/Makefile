##simple makefile

#tag = httpserver fastcgi_server.fcgi
tag = fastcgi_server.fcgi

all: ${tag}

CC = gcc
CFLAGS = -I../unpv13e/lib -g -O2 -D_REENTRANT -Wall

#COMMON_FILE = ../unpv13e/lib/error.c ../unpv13e/lib/wrapsock.c ../unpv13e/lib/wraplib.c

LIBS = ../unpv13e/libunp.a -lpthread

#httpserver: httpserver.c 
#	${CC} ${CFLAGS} httpserver.c http-parser/http_parser.o  -o $@ ${LIBS}
	
fastcgi_server.fcgi:fastcgi_server.c
#	${CC} fastcgi_server.c -o $@ /usr/local/fastcgi/lib/libfcgi.a
	${CC}  fastcgi_server.c  -o $@ -lfcgi -L /usr/local/fastcgi/lib -lpthread -I/usr/local/fastcgi/include
    	
	
clean: 
	rm ${tag}