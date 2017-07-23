#!/bin/sh

allprocess="
retrypush
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
		echo $i "is already running with PID $apa"
	else
		echo "Engine Starting" $i
		php /var/www/html/engine/app/cli.php $i &
	fi
	
counter=`expr $counter + 1`
done