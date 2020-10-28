#!/bin/bash

# Variable to store first argument to setup-repo, the repo name. Will be used as GH repo name, too.
NEW_REPO_NAME='Prueba-borrar'

# GitHub API Token
. server/keys/aws_keys
GH_API_TOKEN=$GH_API_TOKEN
# GitHub User Name
GH_USER='LeandroPretel'
# Store current working directory.
CURRENT_DIR=$PWD
# Project directory can be passed as second argument to setup-repo, or will default to current working directory.
PROJECT_DIR=${2:-$CURRENT_DIR}
# GitHub repos Create API call
curl -H "Authorization: token $GH_API_TOKEN" https://api.github.com/user/repos -d '{"name": "'"${NEW_REPO_NAME}"'"}'
git init $PROJECT_DIR
# Initialize Git in project directory, and add the GH repo remote.
git -C $PROJECT_DIR remote add origin git@github.com:$GH_USER/$NEW_REPO_NAME.git

ghs --ci secret-apply -p automatic-repo -r $NEW_REPO_NAME -s AWS_ACCESS_KEY_ID -v ${AWS_ACCESS_KEY_ID}
ghs --ci secret-apply -p automatic-repo -r $NEW_REPO_NAME -s AWS_SECRET_ACCESS_KEY -v ${AWS_SECRET_ACCESS_KEY}