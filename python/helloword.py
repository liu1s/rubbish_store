import MySQLdb as db
import sys

DB_HOST = '127.0.0.1'
DB_USER = 'root'
DB_PASS = 'root'
DB_NAME = 'soufun'
DB_PORT = 3306

conn = db.connect(host=DB_HOST, user=DB_USER, passwd=DB_PASS, db=DB_NAME, port=DB_PORT)

broker=['liushuan','15821645']
arg=[broker[0],broker[1]]
arg.append(10)
print arg
sys.exit()

c=conn.cursor()
c.execute('''
select * from test where name =%s and phone=%s;
''',arg)
result=c.fetchone()
print result
