#!/bin/bash

# 当前目录
cwd=$(cd "$(dirname "$0")"; pwd);
cd $cwd

if [ -n "$1" ]; then
	today=$1
else
	today=$(date +'%Y%m%d')
fi

ctime=`date +'%H:%M'`
useragent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:25.0) Gecko/20100101 Firefox/25.0"
neituis=(\
	# 发布时间,neitui_uid,PHPSESSID,简历接收邮箱
	'09:10,25098,wajfljasldfjaskdhfkjah,lukin@anjuke.com'
)


for line in ${neituis[@]}; do
	ptime=$(echo $line | awk -F ',' '{print $1}')
	if [ "$ptime" = "*" ] || [ "$ptime" = "$ctime" ]; then
		uid=$(echo $line | awk -F ',' '{print $2}')
		token=$(echo $line | awk -F ',' '{print $3}')
		email=$(echo $line | awk -F ',' '{print $4}')
		curl -s -H "Cookie: PHPSESSID=$token; neitui_uid=$uid;" -A "$useragent" "http://t.neitui.me/u/$uid" \
			| grep '/job/save/type=republish&jid=' | awk -F '&' '{print $2}' | awk -F '"' '{print $1}' | awk -F '=' '{print $2}' \
			| sed 's/\.html//g' | head -n 2 | while read -r jdid; do
				echo jdid: "$jdid"
				body=$(curl -s -H "Cookie: PHPSESSID=$token; neitui_uid=$uid;" -A "$useragent" "http://t.neitui.me/job/save/type=republish&jid=$jdid.html")
				#echo body: "$body"
				position=$(echo "$body" | grep 'id="position"' | awk -F '"' '{print $10}')
				if [ -n "$position" ]; then
					echo position: "$position"
					city=$(echo "$body" | grep 'id="city"' | awk -F '"' '{print $12}')
					echo city: "$city"
					department=$(echo "$body" | grep 'id="department"' | awk -F '"' '{print $12}')
					echo department: "$department"
					if [ "$email" = "" ]; then
						email=$(echo "$body" | grep 'id="recievemail"' | awk -F '"' '{print $12}')
					fi
					echo email: "$email"
			
					detail=$(echo "$body" | php "$cwd"/neitui.php mid '/<textarea name="detail"[^>]+?>/' '</textarea>')
					echo detail: "$detail"
			
			
					en_city=$(echo "$city" | php "$cwd"/neitui.php rawurlencode)
					en_department=$(echo "$department" | php "$cwd"/neitui.php rawurlencode)
					en_email=$(echo "$email" | php "$cwd"/neitui.php rawurlencode)
					en_position=$(echo "$position" | php "$cwd"/neitui.php rawurlencode)
					en_detail=$(echo "$detail" | php "$cwd"/neitui.php rawurlencode)
			
					curl -s -i -H "Cookie: PHPSESSID=$token; neitui_uid=$uid;" -A "$useragent" \
						-d "city=$en_city&click=1&department=$en_department&recievemail=$en_email&position=$en_position&detail=$en_detail" \
						"http://t.neitui.me/job/save/type=republish&jid=$jdid.html" > /dev/null &
				
					echo "Push Success!"
			
					sleep 600
				fi
		done
	fi
done
