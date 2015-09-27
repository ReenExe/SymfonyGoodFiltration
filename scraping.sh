#!/bin/bash

STEPS=(
    "scrap:media:site:list" "scrap:media:site:page"
    "create:media:site:structure" "media:site:analyze:structure"
)

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

app/console doctrine:migrations:migrate
app/console media:site:fill:book