#include <stdio.h>
#include <stdlib.h> 
#ifndef TRUE
    #define TRUE 1
#endif
#ifndef FALSE
    #define FALSE 0
#endif

/*单链表中一个节点的数据类型*/
typedef int LinkListDataType;
typedef LinkListDataType LLDataType;

/*单链表数据链结构*/
typedef struct LinkListNode
{
    LLDataType data;
    struct LinkListNode *next;
    struct LinkListNode *before;
} LinkListNode;

typedef LinkListNode * LinkList;

/*获取单链表中第num个元素,从1开始*/
int getSingleLinkListData(LinkList, int, LLDataType *);
/*往单链表的第num个位置插入新元素*/
int addSingleLinkListData(LinkList, int, LLDataType);

/*main*/
int main(int argc, char* argv[])
{
    /*定义新链表*/
    LinkList linkList;
    linkList = (LinkList)malloc(sizeof(LinkListNode));
    linkList->data = 0;
    linkList->next = linkList;
    linkList->before = linkList;
    
    /*为链表添加新元素*/
    int i;
    for(i = 1;i<10;i++){
        addSingleLinkListData(linkList,i,i);
    }
    
    /*获取某个元素*/
    int a[10],j;
    for(j=1;j<10;j++){
        getSingleLinkListData(linkList,j,&a[j]);
        printf("first:%d\n",a[j]);
    }
    return 0;
}

/*往单链表的第num个位置插入新元素*/
int addSingleLinkListData(LinkList list, int num, LLDataType data)
{
    int position = 0;
    /*找到第num-1个元素*/
    /*todo 根据num和链表的总数判断利用next还是before*/
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
    p->before = list->before;
    list->next = p;
    list->before = p;

    return TRUE;
}

/*获取单链表中第num个元素,从1开始*/
int getSingleLinkListData(LinkList list, int num, LLDataType * data)
{
    int position = 0; /*单链表位置,0代表头*/
    /*todo 根据num和链表的总数判断利用next还是before*/
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
