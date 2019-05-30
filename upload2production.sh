#!/bin/bash

rsync -av --exclude=.git ./ vps.sukovec.cz:/var/www/vps.sukovec.cz/foto/
