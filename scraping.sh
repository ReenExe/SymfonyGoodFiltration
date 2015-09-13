#!/usr/bin/env bash

while true; do
    app/console scrap:media:site:list
    EXIT_CODE=$?
    if test $EXIT_CODE -eq 1
    then
        echo "Done scrapinf list"
        break
    fi
done