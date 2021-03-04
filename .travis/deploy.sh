#!/usr/bin/env bash

# Requires the following environment variables:
# $ENVIRONMENT = The environment (production/release/staging).
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

# If not a Travis build set the commit to use to create the archive
if [ -z "${TRAVIS_COMMIT}" ]; then
    export TRAVIS_BUILD_DIR="${0%/*}/../"
    export TRAVIS_COMMIT=`git rev-parse HEAD`
else
    cd ${TRAVIS_BUILD_DIR}
fi

# Build the image.
./docker/build.sh

# Deploy the update to the services.
SERVICE="api" ./docker/deploy.sh
