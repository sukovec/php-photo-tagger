#!/bin/bash

DATEFILE=$HOME/.newest-sukofon-date
NEW=$(cat $DATEFILE)
NEWEST=$NEW

PORT=2222
DEV=$1

if [ "$DEV" == "" ] ; then
	echo "Need IP address (port is by default 2222)"
	exit 5
fi

echo "Preparing SSH connection"
SSHSOCK=$(mktemp -u)
ssh -p$PORT -M -S $SSHSOCK -fN $DEV
if [ $? != 0 ] ; then
	echo "Cannot create master socket"
	exit 2
fi

sshdev() {
	ssh -S $SSHSOCK "$DEV" -p"$PORT" "$@" < /dev/null
}
TODAY=$(date +"%Y-%m-%d")
ODIR=$HOME/nezalohovane-foto/sukofon-$TODAY
mkdir $ODIR
TOTAL=0

NWISO=$(date -d @"$NEW" "+%Y%m%d%H%M.%S")
sshdev touch '~/.thedatefile' -t "$NWISO"
echo "Created datefile, processing file list"
for file in $(sshdev find '~/SDCard/DCIM/100_CFV5' -newer '~/.thedatefile' -type f) ; do
	DT=$(sshdev stat $file -c "%Y")

	if [ $DT -gt $NEWEST ] ; then
		NEWEST=$DT
	fi
	scp -o ControlPath=$SSHSOCK $DEV:$file $ODIR/$(basename $file)


	if [ $? != 0 ] ; then
		echo "Error: Cannot copy $file"
	else
		TOTAL=$(( $TOTAL + 1 ))
	fi
done

echo $NEWEST > $DATEFILE

echo "Unmounting, copied $TOTAL"
echo "Newest was $NEW, now it's $NEWEST"

ssh -S $SSHSOCK "$DEV" -O exit

