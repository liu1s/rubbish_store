#coding=utf-8
import urllib
import urllib2
import re
from list import SpiderList
from unit import BDTB
 
spiderList = SpiderList()
lists = spiderList.getContents(1)
for list in lists:
    content_id = list[0]
    baseURL = 'http://tieba.baidu.com/p/' + content_id
    #print baseURL
    bdtb = BDTB(baseURL,1)
    page = bdtb.getPage(1)
    titile = bdtb.getTitle(page)
    content = bdtb.getContent(page)
    print "*****\r\n","这个是sb标题：" + titile + "\r\n",'__________\r\n',"这个是sb内容：" + content[0]+"\r\n",'*****\r\n'