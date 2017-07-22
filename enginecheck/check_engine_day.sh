#!/bin/sh

allprocess="
/cli.php schedulerpush
"

# Redefine IFS with >
IFS=">
"

counter=1

run_flag="false"
apa=""

for i in $allprocess
do
	
	apa=`ps -eo "%p %a" | grep -P "$i$" | grep -v grep | cut -c1-5`
	
	if [[ $apa != ""  ]]; then
		echo $i " ALREADY RUNNING : PID $apa"
	else
		echo "STARTING : " $i
		cd /var/www/html/engine/
		php /var/www/html/engine/app/cli.php $counter &
	fi
	
counter=`expr $counter + 1`
done
