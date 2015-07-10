#!/usr/bin/env bash
export DEBIAN_FRONTEND=noninteractive
export LC_ALL=en_US.UTF-8
export LANG=en_US.UTF-8

# sudo aptitude --help
# -y             Assume that the answer to simple yes/no questions is 'yes'.
# -f             Aggressively try to fix broken packages.
# -q             In command-line mode, suppress the incremental progress indicators.

sudo aptitude update -q

sudo apt-get install -y php5-cli

# blackfire.io
# http://habrahabr.ru/post/242167/
wget -O - https://packagecloud.io/gpg.key | sudo apt-key add -
echo "deb http://packages.blackfire.io/debian any main" | sudo tee /etc/apt/sources.list.d/blackfire.list
sudo apt-get update
sudo apt-get install -y blackfire-php blackfire-agent

# blackfire-agent -register

sudo apt-get install mysql-server mysql-client
sudo apt-get install php5-mysql

# How To Install Elasticsearch
# https://www.digitalocean.com/community/tutorials/how-to-install-elasticsearch-logstash-and-kibana-4-on-ubuntu-14-04

# Java before ElasticSearch
sudo add-apt-repository -y ppa:webupd8team/java
sudo apt-get update
sudo apt-get -y install oracle-java8-installer

# ElasticSearch
wget -O - http://packages.elasticsearch.org/GPG-KEY-elasticsearch | sudo apt-key add -
echo 'deb http://packages.elasticsearch.org/elasticsearch/1.4/debian stable main' | sudo tee /etc/apt/sources.list.d/elasticsearch.list
sudo apt-get update
sudo apt-get -y install elasticsearch

# sudo vi /etc/elasticsearch/elasticsearch.yml
# network.host: localhost
# sudo service elasticsearch restart

# for connect to ElasticSearch
sudo apt-get install php5-curl