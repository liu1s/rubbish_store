#include "unp.h"
#include "http_parser.h"
#include "httpprotocol.h"

http_parser_settings parser_settings =
{
     .on_message_begin    = on_message_begin
    ,.on_message_complete = on_message_complete
    ,.on_headers_complete = on_headers_complete
    ,.on_header_field     = header_field_cb
    ,.on_header_value     = header_value_cb
    ,.on_url              = url_cb
    ,.on_body             = body_cb
};

/* 测试的HTTP报文 */
const char * http_get_raw = "GET /favicon.ico HTTP/1.1\r\n"
         "Host: 0.0.0.0=5000\r\n"
         "User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9) Gecko/2008061015 Firefox/3.0\r\n"
         "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
         "Accept-Language: en-us,en;q=0.5\r\n"
         "Accept-Encoding: gzip,deflate\r\n"
         "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n"
         "Keep-Alive: 300\r\n"
         "Connection: keep-alive\r\n"
         "\r\n";


const char * http_post_raw =  "POST /post_identity_body_world?q=search#hey HTTP/1.1\r\n"
         "Accept: */*\r\n"
         "Transfer-Encoding: identity\r\n"
         "Content-Length: 5\r\n"
         "\r\n"
         "World";


// trunk编码的报文后，含有托挂的字段
// 详细请参考《HTTP权威指南》编码的部分
const char * http_trunk_head =  "POST /chunked_w_trailing_headers HTTP/1.1\r\n"
         "Transfer-Encoding: chunked\r\n"
         "\r\n"
         "5\r\nhello\r\n"
         "6\r\n world\r\n"
         "0\r\n"
         "Vary: *\r\n"
         "Content-Type: text/plain\r\n"
         "\r\n";


const char *http_trunk_head1 = "POST /two_chunks_mult_zero_end HTTP/1.1\r\n"
         "Transfer-Encoding: chunked\r\n"
         "\r\n"
         "5\r\nhello\r\n"
         "6\r\n world\r\n"
         "000\r\n"
         "\r\n";

const char * http_trunk_part_1 =  "POST /chunked_w_trailing_headers HTTP/1.1\r\n"
         "Transfer-Encoding: chunked\r\n"
         "\r\n"
         "5\r\nhello\r\n";
const char * http_trunk_part_2 = "6\r\n world\r\n"
         "0\r\n"
         "Vary: *\r\n"
         "Content-Type: text/plain\r\n"
         "\r\n";


// 测试HTTP GET报文
void test_http_get() {
  printf("Test HTTP GET\n");
  http_parser *parser = (http_parser *)malloc(sizeof(http_parser));
  http_parser_init(parser, HTTP_REQUEST);
  struct http_request *requst = new_http_request();
  parser->data = requst;

  int res = http_parser_execute(parser, &parser_settings, http_get_raw, strlen(http_get_raw));
  printf("method: %s, version: %u.%u\n",
     requst->method, requst->http_major, requst->http_minor);

  delete_http_request(requst);
  free(parser);
  printf("END Test HTTP GET\n\n\n");
}


// 测试HTTP POST报文
void test_http_post() {
  printf("Test HTTP POST\n");
  http_parser *parser = (http_parser *)malloc(sizeof(http_parser));
  http_parser_init(parser, HTTP_REQUEST);
  struct http_request *requst = new_http_request();
  parser->data = requst;

  int res = http_parser_execute(parser, &parser_settings, http_post_raw, strlen(http_post_raw));
  printf("method: %s, version: %u.%u\n",
     requst->method, requst->http_major, requst->http_minor);

  delete_http_request(requst);
  free(parser);
  printf("END Test HTTP POST\n\n\n");
}

// 测试含有托挂字段的HTTP trunk编码的报文
void test_http_chunk() {
  printf("Test HTTP CHUNK\n");
  http_parser *parser = (http_parser *)malloc(sizeof(http_parser));
  http_parser_init(parser, HTTP_REQUEST);
  struct http_request *requst = new_http_request();
  parser->data = requst;

  int res = http_parser_execute(parser, &parser_settings, http_trunk_head, strlen(http_trunk_head));
  printf("method: %s, version: %u.%u\n",
     requst->method, requst->http_major, requst->http_minor);

  delete_http_request(requst);
  free(parser);
  printf("END Test HTTP TRUNK\n\n\n");
}

// 测试HTTP trunk编码的报文: trunk编码以多个0表明结束
void test_http_chunk1() {
  printf("Test HTTP CHUNK 1\n");
  http_parser *parser = (http_parser *)malloc(sizeof(http_parser));
  http_parser_init(parser, HTTP_REQUEST);
  struct http_request *requst = new_http_request();
  parser->data = requst;

  int res = http_parser_execute(parser, &parser_settings, http_trunk_head1, strlen(http_trunk_head1));
  printf("method: %s, version: %u.%u\n",
     requst->method, requst->http_major, requst->http_minor);

  delete_http_request(requst);
  free(parser);
  printf("END Test HTTP TRUNK 1\n\n\n");
}


// 测试HTTP trunk编码的报文: 将一个完整的报文分两次解析
void test_http_chunk_part() {
  printf("Test HTTP CHUNK PART\n");
  http_parser *parser = (http_parser *)malloc(sizeof(http_parser));
  http_parser_init(parser, HTTP_REQUEST);
  struct http_request *requst = new_http_request();
  parser->data = requst;

  int res = http_parser_execute(parser, &parser_settings, http_trunk_part_1, strlen(http_trunk_part_1));
  //printf("method: %s, version: %u.%u\n",
     //requst->method, requst->http_major, requst->http_minor);
  printf("\n\n\n\n\n");

  res = http_parser_execute(parser, &parser_settings, http_trunk_part_2, strlen(http_trunk_part_2));

  delete_http_request(requst);
  free(parser);
  printf("END Test HTTP TRUNK PART\n\n\n");
}


void main(int argc, char **argv) {
  test_http_get();
  test_http_post();
  test_http_chunk();
  test_http_chunk1();
  test_http_chunk_part();
}
