#include <stdio.h>
#include <stdlib.h>

static inline void
get_cpuid(unsigned int i, unsigned int * buf)
{
   unsigned int eax,ebx,ecx,edx;
   __asm__ (
    "cpuid"
    :"=a"(eax),"=b"(ebx),"=c"(ecx),"=d"(edx):"a"(i));
    buf[0]=eax;
    buf[1]=ebx;
    buf[2]=edx;
    buf[3]=ecx;
}

int main()
{
   unsigned int cpu[4];

   get_cpuid(0,cpu);

   printf("%d %d %d %d", cpu[0], cpu[1], cpu[2], cpu[3]);
}
