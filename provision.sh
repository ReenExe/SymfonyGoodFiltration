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