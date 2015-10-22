from threading import Thread
from threading import RLock
from Queue import Queue
import os
import time
import sys

def write_file(fileQueue):
    num = fileQueue.get()
    g_lock.acquire()
    handle = g_lock.filehandle
    handle.write(str(num));
    g_lock.release()
    time.sleep(1)
    print num

threadNum = 10
fileQueue = Queue()
for i in range(threadNum):
    fileQueue.put(i);

g_lock = RLock()
g_lock.filehandle = open('Thread.file','a+')
#create the csv file
threads = []
for i in range(threadNum):
    t = Thread(target=write_file,args=(fileQueue,))
    t.setDaemon(True)
    t.start()
    threads.append(t)

#check if the sub thread is exit
for item in threads:
    if item.isAlive():
        item.join()

g_lock.filehandle.close()

print 3

