import os
import sys

#check file if exist and init pointer
if os.path.isfile("pointer"):
    file_object=open('pointer')
    try:
        pointer = file_object.read()
    finally:
        file_object.close()
    if pointer == '':
        print pointer = 0
    print pointer 
else:
    os.mknod("pointer")    
    pointer=0

