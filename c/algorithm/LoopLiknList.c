#include <stdio.h>
#include <stdlib.h> 
#ifndef TRUE
    #define TRUE 1
#endif
#ifndef FALSE
    #define FALSE 0
#endif

/*
跟单链表的区别只在于循环链表的尾部指向头
*/

/*单链表中一个节点的数据类型*/
typedef int LinkListDataType;
typedef LinkListDataType LLDataType;

/*单链表数据链结构*/
typedef struct LinkListNode
{
    LLDataType data;
    struct LinkListNode *next;
} LinkListNode;

typedef LinkListNode * LinkList;

/*获取单链表中第num个元素,从1开始*/
int getSingleLinkListData(LinkList, int, LLDataType *);
/*往单链表的第num个位置插入新元素*/
int addSingleLinkListData(LinkList, int, LLDataType);

/*main*/
int main(int argc, char* argv[])
{
    LinkList linkList;
    linkList = (LinkList)malloc(sizeof(LinkListNode));
    linkList->data = 0;
    linkList->next = linkList;
    addSingleLinkListData(linkList,1,1);
    int first;
    getSingleLinkListData(linkList,1,&first);
    printf("%d",first);
    return 0;
}

/*往单链表的第num个位置插入新元素*/
int addSingleLinkListData(LinkList list, int num, LLDataType data)
{
    int position = 0;
    /*找到第num-1个元素*/
    while(list && position< (num-1) ){
        list = list->next;
        position ++;
    }

    if(!list || position > (num-1) ){
        return FALSE;
    }

    LinkList p;
    p = (LinkList)malloc(sizeof(LinkListNode));
    p->data = data;
    p->next = list->next;
    list->next = p;

    return TRUE;
}

/*获取单链表中第num个元素,从1开始*/
int getSingleLinkListData(LinkList list, int num, LLDataType * data)
{
    int position = 0; /*单链表位置,0代表头*/
    while(list && position < num){ /*find the numth node of the  list*/
        list = list->next;
        position++;
    }

    if(!list || position > num ){ 
        return FALSE;
    }

    *data = list->data;
    return TRUE;
}
