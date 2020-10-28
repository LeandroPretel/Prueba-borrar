#! /bin/bash
DEPLOY_PASSWORD=`cat keys/deploy_password`
echo "Usando '${DEPLOY_PASSWORD}' como deploy password"
. ./keys/aws_keys
echo "Usando '${AWS_ACCESS_KEY_ID}' access key id"
docker build --tag api_redentradas \
    --build-arg SSH_PRIVATE_KEY=keys/deploy.key \
    --build-arg SSH_PASS_KEY=${DEPLOY_PASSWORD} \
    --build-arg REPO=git@github.com:beebitsolutions/redentradas-backend.git \
    --build-arg ARG_AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID} \
    --build-arg ARG_AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY} \
    --build-arg ARG_AWS_BUCKET=${AWS_BUCKET} \
    .