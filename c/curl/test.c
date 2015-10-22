#include <stdio.h>
#include <curl/curl.h>
#include <stdlib.h>
#include <string.h>
#include <regex.h>

/**
 *
 * @param contents 抓取数据
 * @param userp 回写数据的指针
 */
        static size_t
myWrite(char *contents, size_t size, size_t nmemb, char *userp)
{
        int z, i, status, cflags=REG_EXTENDED;
        size_t realsize = size * nmemb;

        char pattern[20] = "新.*闻";
        char ebuf[128];
        regex_t reg; //保存编译后的正则
        regmatch_t pm[1]; //数组0单元存放主正则表达式位置，后边的单元依次存放子正则表达式位置。 数组长度和nmatch一样
        const size_t nmatch = 1;

        /* 编译正则表达式*/
        z = regcomp(&reg, pattern, cflags);
        if (z != 0){
                regerror(z, &reg, ebuf, sizeof(ebuf));
                fprintf(stderr, "%s: pattern '%s' \n",ebuf, pattern);
                return 1;
        }

        /* 查找开始 */
        status = regexec(&reg, userp, nmatch, pm, 0);
        if (status == REG_NOMATCH) {
            printf("No match\n");
        } else {
            printf("Match:\n");
            for (i=pm[0].rm_so;i<pm[0].rm_eo;++i) 
                putchar(userp[i]);

            printf("\n");
        }

        //释放reg
        regfree(&reg);

        //采用strcpy实现
        strcpy(userp, contents);

        return realsize;
}

int main(int argc,char* argv[])
{
        CURL *curl;
        CURLcode res;
        char curlContent[50000];
        curl = curl_easy_init();
        if(curl)
        {
                curl_easy_setopt(curl, CURLOPT_URL,
                                "http://www.baidu.com");

                /* 
                 * 添加WRITEFUNCTION来抑制信息自动输出
                 */
                curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, myWrite);
                curl_easy_setopt(curl, CURLOPT_WRITEDATA, curlContent);

                res = curl_easy_perform(curl);
                if (CURLE_OK == res) {
                        char *ct;
                        time_t fileTime;
                        res = curl_easy_getinfo(curl, CURLINFO_CONTENT_TYPE,&ct);

                        /*
                           if((CURLE_OK == res) && ct)
                           printf("We received Content-Type: %s\n", ct);

                           res = curl_easy_getinfo(curl, CURLINFO_FILETIME, &fileTime);
                           if((CURLE_OK == res) && fileTime)
                           printf("We received http code: %s\n", ctime(&fileTime));
                         */
                }


                curl_easy_cleanup(curl);
        }
        return 0;
}

