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

echo "Should return an error if a parameter is a string instead of an integer"
retValue=$(curl -s 'localhost:3000?iron=30&water=5&food=2&steel=50000&electronics="2000"' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return an error if the wrong request method is used (POST)"
retValue=$(curl -s --data "iron=30&water=5&food=2&steel=50000&electronics=2000" 'localhost:3000' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return an error if the wrong request method is used (PUT)"
retValue=$(curl -X PUT -s --data "iron=30&water=5&food=2&steel=50000&electronics=2000" 'localhost:3000' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return an error if the wrong request method is used (DELETE)"
retValue=$(curl -X DELETE -s --data "iron=30&water=5&food=2&steel=50000&electronics=2000" 'localhost:3000' | grep 'ERROR:') > /dev/null
if [[ -z $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

echo "Should return no error if the request is valid"
retValue=$(curl -s 'localhost:3000?iron=30&water=5&food=2&steel=50000&electronics=2000' | grep 'ERROR:') > /dev/null
if [[ -n $retValue ]]; then
    printFail
    exitValue=1
else
    printPassed
fi

# kills the started PHP-server
# CAUTION this kills ALL running PHP-server
killall php

exit $exitValue
