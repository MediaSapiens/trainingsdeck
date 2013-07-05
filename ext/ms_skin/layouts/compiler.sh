#!/bin/bash

echo 'remove static'
rm -rf static
echo 'change folder'
cd src/

echo 'command execute'

npm install
brunch b --optimize

echo 'done'
