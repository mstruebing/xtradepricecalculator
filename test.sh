#!/usr/bin/env bash

# Color definitions for output
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

printFail() {
    printf "\t${RED}FAIL${NC}\n" >&2
}

printPassed() {
    printf "\t${GREEN}PASSED${NC}\n"
}

# start php server
php -S localhost:3000 &>/dev/null &

# sleep to let the server start in the background
sleep 1

exitValue=0

echo "Should return an error if not all expected values are passed"
retValue=$(curl -s 'localhost:3000?iron=30&water=5&food=2&steel=50000' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return an error if no value is passed"
retValue=$(curl -s 'localhost:3000' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return an error if additional values are passed"
retValue=$(curl -s 'localhost:3000?iron=30&water=5&food=2&steel=50000&electronics=2000&stuff=abc' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return no error if the request is valid"
retValue=$(curl -s 'localhost:3000?iron=30&water=5&food=2&steel=50000&electronics=2000' | grep 'ERROR:') > /dev/null
if [[ ! -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

killall php
exit $exitValue
