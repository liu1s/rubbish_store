/* include fig01 */
#include    "unp.h"
#include "http_parser.h"

#define alloc_cpy(dest, src, len) \
    dest = malloc(len + 1);\
    memcpy(dest, src, len);\
    dest[len] = '\0';


void print_range(const char *buf, size_t len) {
  char *temp_buf = (char *)malloc(len);
  memcpy(temp_buf, buf, len);
  temp_buf[len] = '\0';
  printf("%s\n", temp_buf);
  free(temp_buf);
}


// 保存HTTP报文头部的字段的链表
struct http_header {
    char *name, *value;
    struct http_header *next;
};


// 初始化一个新的HTTP头部字段
static inline struct http_header *new_http_header() {
    struct http_header *header = malloc(sizeof(struct http_header));
    header->name = NULL;
    header->value = NULL;
    header->next = NULL;
    return header;
}


// 删除一个HTTP头部中的字段
static inline void delete_http_header(struct http_header *header) {
    if (header->name != NULL) free(header->name);
    if (header->value != NULL) free(header->value);
    free(header);
}


// HTTP请求的结构提
struct http_request {
    char *method, *url, *body;
    unsigned int flags;
    unsigned short http_major, http_minor;
    struct http_header *headers;
};


#define F_HREQ_KEEPALIVE 0x01


// 初始化一个新的HTTP请求
static inline struct http_request *new_http_request() {
    struct http_request *request = malloc(sizeof(struct http_request));
    request->headers = NULL;
    request->url = NULL;
    request->body = NULL;
    request->flags = 0;
    request->http_major = 0;
    request->http_minor = 0;
    return request;
}


// 删除一个HTTP请求
static inline void delete_http_request(struct http_request *request) {
    if (request->url != NULL) free(request->url);
    if (request->body != NULL) free(request->body);
    struct http_header *header = request->headers;
    while (header != NULL) {
        struct http_header *to_delete = header;
        header = header->next;
        delete_http_header(to_delete);
    }
    free(request);
}


// 将一个空的HTTP头部字段附件到字段链表的尾部
static inline struct http_header *add_http_header(struct http_request *request) {
    struct http_header *header = request->headers;
    // 从头开始循环链表
    while (header != NULL) {
    // 创建一个新的header添加到尾部
    // 并直接返回
        if (header->next == NULL) {
            header->next = new_http_header();
            return header->next;
        }
        header = header->next;
    }
    // 如果header是空，则创建一个空的header
    // 并将它赋值给request-headers
    request->headers = new_http_header();
    return request->headers;
}


int null_cb(http_parser *parser) { return 0; }


// 当解析到URL时的回调
int url_cb(http_parser *parser, const char *buf, size_t len) {
    printf("on url_cb: \n");
    print_range(buf, len);
    // 将保存在parser->data中的用户数据取得
    struct http_request *request = (struct http_request *) parser->data;
    // 设置method, http版本字段
    request->method = (char *)http_method_str(parser->method);
    request->http_major = parser->http_major;
    request->http_minor = parser->http_minor;
    // 解析完成的数据放在buf处，长度为len
    alloc_cpy(request->url, buf, len)
    printf("\n");
    return 0;
}


// 当解析到头部字段名称的回调
int header_field_cb(http_parser *parser, const char *buf, size_t len) {
    printf("on header_field_cb: \n");
    print_range(buf, len);
    printf("\n");
    // 取出parser->data中的request
    struct http_request *request = (struct http_request *) parser->data;
    // 创建新的header
    struct http_header *header = add_http_header(request);
    // 保存name字段
    alloc_cpy(header->name, buf, len)
    return 0;
}


// 当解析到头部字段值的回调
int header_value_cb(http_parser *parser, const char *buf, size_t len) {
    printf("on header_value_cb: \n");
    print_range(buf, len);
    printf("\n");
    struct http_request *request = (struct http_request *) parser->data;
    // 取出保存的header
    struct http_header *header = request->headers;
    // 得到链表中的最后一个header
    while (header->next != NULL) {
        header = header->next;
    }
    // 将value字段保存
    alloc_cpy(header->value, buf, len)
    return 0;
}

// 当解析到HTTP消息主体的回调
int body_cb(http_parser *parser, const char *buf, size_t len) {
    printf("on body_cb: \n");
    print_range(buf, len);
    printf("\n");
    struct http_request *request = (struct http_request *) parser->data;
    alloc_cpy(request->body, buf, len)
    return 0;
}


// 通知回调：说明开始解析HTTP消息
int on_message_begin(http_parser *parser) {
  printf("on_message_begin\n");
  return 0;
}


// 通知回调：说明消息解析完毕
int on_message_complete(http_parser *parser) {
  printf("on_message_complete\n");
  return 0;
}


// 通知回调：说明HTTP报文头部解析完毕
int on_headers_complete(http_parser *parser) {
  printf("on_headers_complete\n");
  return 0;
}


// 设置回调
static http_parser_settings parser_settings =
{
     .on_message_begin    = on_message_begin
    ,.on_message_complete = on_message_complete
    ,.on_headers_complete = on_headers_complete
    ,.on_header_field     = header_field_cb
    ,.on_header_value     = header_value_cb
    ,.on_url              = url_cb
    ,.on_body             = body_cb
};

int
main(int argc, char **argv)
{
    int                 i,j, maxi, maxfd, listenfd, connfd, sockfd;
    int                 nready, client[FD_SETSIZE];
    ssize_t             n;
    fd_set              rset, allset;
    char                buf[MAXLINE];
    socklen_t           clilen;
    struct sockaddr_in  cliaddr, servaddr;


    listenfd = Socket(AF_INET, SOCK_STREAM, 0);

    bzero(&servaddr, sizeof(servaddr));
    servaddr.sin_family      = AF_INET;
    servaddr.sin_addr.s_addr = htonl(INADDR_ANY);
    servaddr.sin_port        = htons(7747);

    Bind(listenfd, (SA *) &servaddr, sizeof(servaddr));

    Listen(listenfd, LISTENQ);

    maxfd = listenfd;           /* initialize */
    maxi = -1;                  /* index into client[] array */
    for (i = 0; i < FD_SETSIZE; i++)
        client[i] = -1;         /* -1 indicates available entry */
    FD_ZERO(&allset);
    FD_SET(listenfd, &allset);
/* end fig01 */

/* include fig02 */
    for ( ; ; ) {
        rset = allset;      /* structure assignment */
        nready = Select(maxfd+1, &rset, NULL, NULL, NULL);

        if (FD_ISSET(listenfd, &rset)) {    /* new client connection */
            clilen = sizeof(cliaddr);
            connfd = Accept(listenfd, (SA *) &cliaddr, &clilen);
#ifdef  NOTDEF
            printf("new client: %s, port %d\n",
                    Inet_ntop(AF_INET, &cliaddr.sin_addr, 4, NULL),
                    ntohs(cliaddr.sin_port));
#endif

            for (i = 0; i < FD_SETSIZE; i++)
                if (client[i] < 0) {
                    client[i] = connfd; /* save descriptor */
                    break;
                }
            if (i == FD_SETSIZE)
                err_quit("too many clients");

            FD_SET(connfd, &allset);    /* add new descriptor to set */
            if (connfd > maxfd)
                maxfd = connfd;         /* for select */
            if (i > maxi)
                maxi = i;               /* max index in client[] array */

            if (--nready <= 0)
                continue;               /* no more readable descriptors */
        }

        for (i = 0; i <= maxi; i++) {   /* check all clients for data */
            if ( (sockfd = client[i]) < 0)
                continue;
            if (FD_ISSET(sockfd, &rset)) {
                if ( (n = Read(sockfd, buf, MAXLINE)) == 0) {
                        /*4connection closed by client */
                    Close(sockfd);
                    FD_CLR(sockfd, &allset);
                    client[i] = -1;
                } else
                    Writen(fileno(stdout), buf, n);
                    //Writen(sockfd, buf, n);

                    http_parser *parser = (http_parser *)malloc(sizeof(http_parser));
                    http_parser_init(parser, HTTP_REQUEST);
                    struct http_request *requst = new_http_request();
                    parser->data = requst;

                    int res = http_parser_execute(parser, &parser_settings, buf, strlen(buf));
                      printf("method: %s, version: %u.%u\n",
                         requst->method, requst->http_major, requst->http_minor);


                    char *response = "HTTP/1.1 404 Not Found\r\n\r\n";
                    Writen(sockfd, response, strlen(response));
                    //Writen(fileno(stdout), requst->url, strlen(requst->url));
                    delete_http_request(requst);
                    free(parser);


                    Close(sockfd);
                    FD_CLR(sockfd, &allset);
                    client[i] = -1;

                if (--nready <= 0)
                    break;              /* no more readable descriptors */
            }
        }
    }
}
/* end fig02 */
