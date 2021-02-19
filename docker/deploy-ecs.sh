#!/usr/bin/env bash

# Requires the following environment variables:
# $DEPLOY_ENV = The environment (production/staging).
# $REPO_URI = The URI of the ECR repo to push to.
# $CLUSTER = The name of the ECS cluster to deploy to.
# $AWS_ACCESS_KEY_ID = The AWS access key.
# $AWS_SECRET_ACCESS_KEY = The AWS secret access key.
# $AWS_DEFAULT_REGION = The AWS region.
# $TRAVIS_BUILD_DIR = The directory of the project.
# $TRAVIS_COMMIT = The commit hash of the build.

# Bail out on first error.
set -e

# Set environment variables.
echo "Setting deployment configuration for ${DEPLOY_ENV}..."
export ENV_SECRET_ID=".env.api.${DEPLOY_ENV}"

# Install AWS-CLI
if ! command -v aws &> /dev/null; then
    echo "Installing AWS CLI..."
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    unzip awscliv2.zip -d aws-tmp
    sudo ./aws-tmp/aws/install
    echo `aws --version`
    rm -r aws-tmp
    rm awscliv2.zip
fi
# Get the .env file.
echo "Downloading .env file..."
aws secretsmanager get-secret-value \
    --secret-id ${ENV_SECRET_ID} | \
    python -c "import json,sys;obj=json.load(sys.stdin);print obj['SecretString'];" > .env.staging
