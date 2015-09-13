#!/bin/bash

STEPS=( "scrap:media:site:list" "scrap:media:site:page" )

for STEP in "${STEPS[@]}"
do
    while true; do
        app/console $STEP
        EXIT_CODE=$?
        if test $EXIT_CODE -eq 1
        then
            echo "Done"
            break
        fi
        echo '...';
    done
done