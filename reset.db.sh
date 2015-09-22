#!/bin/bash

mysql -uroot -pfiltration -e 'DROP DATABASE IF EXISTS `test`;';
mysql -uroot -pfiltration -e 'CREATE DATABASE `test` CHARACTER SET `utf8` COLLATE `utf8_unicode_ci`;'

mysql -uroot -pfiltration test < dump.sql