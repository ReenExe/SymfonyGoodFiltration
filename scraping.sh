#!/usr/bin/env bash

while true; do
    app/console scrap:media:site:list
    EXIT_CODE=$?
    if test $EXIT_CODE -eq 1
    then
        echo "Done scraping list"
        break
    fi
    echo '...';
done

while true; do
    app/console scrap:media:site:page
    EXIT_CODE=$?
    if test $EXIT_CODE -eq 1
    then
        echo "Done scraping page"
        break
    fi
    echo '...';
done