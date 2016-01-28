#coding=utf-8
import urllib
import urllib2
import re
 
class SpiderList:
 
    def __init__(self):
        self.siteURL = 'http://tieba.baidu.com/f?kw=runningman&fr=ala0&loc=rec'
 
    def getPage(self,pageIndex):
        url = self.siteURL + "?page=" + str(pageIndex)
        request = urllib2.Request(url)
        response = urllib2.urlopen(request)
        return response.read()
 
    def getContents(self,pageIndex):
        page = self.getPage(pageIndex)
        pattern = re.compile('<a href="/p/(\d+)" title="┏RunningMan┓(.*?)" target="_blank" class="j_th_tit">(.*?)</a>.*?<span></span></div>',re.S)
        items = re.findall(pattern,page)
        return items
        #for item in items:
        #    print item[0],item[1]
        #    print "___________"
 
