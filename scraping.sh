#!/bin/bash

STEPS=( "app/console scrap:media:site:list" "app/console scrap:media:site:page" )

for STEP in "${STEPS[@]}"
do
    while true; do
        $STEP
        EXIT_CODE=$?
        if test $EXIT_CODE -eq 1
        then
            echo "Done"
            break
        fi
        echo '...';
    done
done