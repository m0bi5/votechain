import requests
import json


candidates=[]
votes=[]
results={"Genesis":999}
r=requests.get('http://127.0.1.1:5000/display_chain')
a=0
for i in (json.loads(r.content)['chain']):
	try:
		for j in i['vote_info']:
			votes.append(j['vote'])
	except:
		votes.append(i['vote_info']['vote'])


import pymysql.cursors

# Connect to the database
connection = pymysql.connect(host='localhost',
                             user='root',
                             password="'root'",
                             db='login_system',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)

try:
    with connection.cursor() as cursor:
        # Read a single record
        sql = "SELECT `first_name`,`last_name` FROM `candidates`"
        cursor.execute(sql)
        result = cursor.fetchall()
except Exception as exp:
	print(exp)


for i in result:
	candidates.append(i['first_name']+" "+i['last_name'])

for i in candidates:
	results[i]=votes.count(i)

print(results)

for i in results:
	print(i)

	try:
	    with connection.cursor() as cursor:
	        # Read a single record
	        sql = "UPDATE `candidates` SET `vote_count` = \""+str(results[i])+"\" WHERE `first_name` =\""+(i.split(' ')[0])+"\" AND `last_name`=\""+(i.split(' ')[1])+"\""       
	        print(cursor.execute(sql))
	        connection.commit()
	except Exception as exp:
		print(exp)


connection.close()