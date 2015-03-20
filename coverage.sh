#!/bin/sh

coveragefile=$(date +%Y-%m-%d)
filenamed=./build/coverage/coverage-report.$coveragefile
./vendor/bin/phpunit --coverage-html=$filenamed