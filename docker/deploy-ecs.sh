#!/usr/bin/env bash

# Requires the following environment variables:
# $DEPLOY_ENV = The environment (production/staging).
# $REPO_URI = The URI of the ECR repo to push to.
# $CLUSTER = The name of the ECS cluster to deploy to.
# $AWS_ACCESS_KEY_ID = The AWS access key.
# $AWS_SECRET_ACCESS_KEY = The AWS secret access key.
# $AWS_DEFAULT_REGION = The AWS region.
# $AWS_IAM_ROLE_ARN = The ARN of an access role to access aws as [optional]
# $TRAVIS_BUILD_DIR = The directory of the project.
# $TRAVIS_COMMIT = The commit hash of the build.

# Bail out on first error.
set -e

# Set environment variables.
echo "Setting deployment configuration for ${DEPLOY_ENV}..."
export ENV_SECRET_ID="${DEPLOY_ENV}.api.env"

# Install AWS-CLI
if ! command -v aws &> /dev/null; then
    echo "Installing AWS CLI..."
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    unzip awscliv2.zip -d aws-tmp
    sudo ./aws-tmp/aws/install
    echo `aws --version`
    if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
        mkdir -p ~/.aws
        cat <<EOF > ~/.aws/config
[default]
    aws_access_key_id=$AWS_ACCESS_KEY_ID
    aws_secret_access_key=$AWS_SECRET_ACCESS_KEY
    region=$AWS_DEFAULT_REGION
[profile accessrole]
    role_arn=$AWS_IAM_ROLE_ARN
    source_profile=default
EOF
    fi
    rm -r aws-tmp
    rm awscliv2.zip
fi

# Get the .env file.
echo "Downloading .env file..."
if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    SECRET=`aws secretsmanager get-secret-value \
        --profile accessrole \
        --secret-id ${ENV_SECRET_ID}`
else
    SECRET=`aws secretsmanager get-secret-value \
        --secret-id ${ENV_SECRET_ID}`
fi

echo $SECRET | python -c "import json,sys;obj=json.load(sys.stdin);print obj['SecretString'];" > .env

# Set the deploy variables based on the environment

REPO_URI_VAR=`echo "REPO_URI_${DEPLOY_ENV}" | tr [a-z] [A-Z]`
export REPO_URI="${!REPO_URI_VAR}"
CLUSTER_VAR=`echo "CLUSTER_${DEPLOY_ENV}" | tr [a-z] [A-Z]`
export CLUSTER="${!CLUSTER_VAR}"

OLD_IFS=$IFS
IFS='/'
read AWS_DOCKER_REGISTRY AWS_DOCKER_REPO <<< "${REPO_URI}"
IFS=$OLD_IFS

# Retrieve an authentication token and authenticate Docker client to project registry.
echo "Logging in to ECR docker registry..."
if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    aws ecr get-login-password --profile accessrole --region ${AWS_DEFAULT_REGION} | docker login --username AWS --password-stdin ${AWS_DOCKER_REGISTRY}
else
    aws ecr get-login-password --region ${AWS_DEFAULT_REGION} | docker login --username AWS --password-stdin ${AWS_DOCKER_REGISTRY}
fi

echo "Build the app image..."
docker build -t  ${AWS_DOCKER_REPO} ${PWD}/docker/php

echo "Tag the app image"
docker tag "$AWS_DOCKER_REPO:latest" "$REPO_URI:latest"

echo "Push the tagged image to the repo..."
docker push "$REPO_URI:latest"
