##simple makefile

tag = httpserver

all: ${tag}

CC = gcc
CFLAGS = -I../unpv13e/lib -g -O2 -D_REENTRANT -Wall

#COMMON_FILE = ../unpv13e/lib/error.c ../unpv13e/lib/wrapsock.c ../unpv13e/lib/wraplib.c

LIBS = ../unpv13e/libunp.a -lpthread

httpserver: httpserver.c 
	${CC} ${CFLAGS} httpserver.c http-parser/http_parser.o  -o $@ ${LIBS}
	
	
clean: 
	rm ${tag}