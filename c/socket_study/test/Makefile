##simple makefile

all: changed_error.o server client

common_file = changed_error.c changed_unp.h changed_ourhdr.h changed_config.h


changed_error.o: ${common_file}

server: server.c ${common_file}
	gcc server.c changed_error.o  -o $@ -g
	
client: client.c ${common_file}
	gcc client.c changed_error.o  -o $@ -g

#server client: client.c server.c ${common_file}
	#gcc $@.c changed_error.o  -o $@ -g
	
clean: 
	rm server client changed_error.o 	