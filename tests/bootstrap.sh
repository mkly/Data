#!/bin/bash
# $1 database user
# $2 database password
mysql -u $1 -p$2 -e 'drop database if exists concrete5_tests; create database concrete5_tests';
mkdir -p core/concrete5
if [ ! -d core/concrete5/web ]
	then
		git clone https://github.com/concrete5/concrete5.git ./core/concrete5
fi
rm -rf ./core/concrete5/web/config/install
rm ./core/concrete5/web/config/site_install.php
rm ./core/concrete5/web/config/site_install_user.php
cp -a ./install ./core/concrete5/web/config/
rm -rf ./core/concrete5/web/packages/*
ln -s ../../../../../src/data ./core/concrete5/web/packages/data
./core/concrete5/cli/install-concrete5.php --config="./test_config.php"
rm -rf ./fixtures
mkdir ./fixtures
mysqldump --xml -t -u $1 -p$2 concrete5_tests > ./fixtures/database.xml
