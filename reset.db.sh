#!/bin/bash

mysql -uroot -pfiltration -e 'DROP DATABASE IF EXISTS `test`; CREATE DATABASE `test`';

mysql -uroot -pfiltration test < dump.sql