#!/bin/bash

cd $HOME/nezalohovane-foto
for dir in $(ls) ; do  
	rsync -av --progress $dir vps.sukovec.cz:foto/

	RET=$?

	if [[ $RET == 0 ]] ; then
		if [ -d ../uploaded-foto/$(basename $dir) ] ; then
			echo "Collision! $dir exists in source and destination"
			exit 1
		fi

		mv $dir ../uploaded-foto/
	else
		echo $dir not backed up, run again
	fi
done
