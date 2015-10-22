#include <stdio.h>
#define MAXSORTLENGTH 10

typedef int sortListData;

/*顺序数组结构体*/
typedef struct _sortList
{
    sortListData data[10];
    int lenth;
}sortList;

/*往顺序队列中添加新元素*/
int addSortList(sortList *,sortListData);
/*获取顺序队列中的某个元素*/
int getOneSortListData(sortList *,int,sortListData *);

void main()
{
    sortList sortListArray; 
    sortListArray.lenth = 0;

    sortListData i = 1;
    for(i;i<=10;i++){
        addSortList(&sortListArray, i);
    }

    sortListData thirdNum = 0;
    getOneSortListData(&sortListArray,2,&thirdNum);
    printf("the third num is %d",thirdNum);
    
    //printf("sizeof sortListArray.data: %ld",sizeof(sortListArray.data));
    //printf("sizeof sortListArray.lenth: %ld",sizeof(sortListArray.lenth));
    //printf("%d",sortListArray.lenth);
}

/*往数组中添加新元素*/
int addSortList(sortList *list,sortListData data)
{
    //printf("sortListArray.lengh: %d\n",list->lenth);

    /*达到数组最大长度，返回false*/
    if (list->lenth >= MAXSORTLENGTH){
        return 0;
    }

    list->data[list->lenth] = data;
    //printf("sortListArray.data: %d\n",list->data[list->lenth]);
    list->lenth++;

    return 1;
}

/*获取顺序队列中指定位置的元素，从0开始*/
int getOneSortListData(sortList *list, int num, sortListData *returnData)
{
    if(num >= MAXSORTLENGTH){
        return 0;       
    }

    *returnData = list->data[num];
    return 1;
}
