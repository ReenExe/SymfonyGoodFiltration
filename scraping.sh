#!/bin/bash

startTime=`date +%s`

STEPS=(
    "scrap:media:site:list"
    "scrap:media:site:page"
    "create:media:site:structure"
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

app/console media:site:analyze:structure
app/console doctrine:migrations:migrate --no-interaction
app/console media:site:fill:book

endTime=`date +%s`
echo execution time was `expr $endTime - $startTime` s.