#!/bin/sh

if [ -d .git ]; then
    cp ./bin/pre-commit ./.git/hooks/pre-commit
    chmod +x ./.git/hooks/pre-commit
fi