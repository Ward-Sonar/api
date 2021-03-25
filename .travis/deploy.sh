#!/usr/bin/env bash

# Requires the following environment variables:
# $DEPLOY_ENV = The environment (production/release/staging).
# $REPO_URI_${DEPLOY_ENV} = The URI of the ECR repo to push to, e.g. REPO_URI_STAGING.
# $CLUSTER_${DEPLOY_ENV} = The name of the ECS cluster to deploy to, e.g. CLUSTER_STAGING.
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

# Set the deploy variables based on the environment

REPO_URI_VAR=`echo "REPO_URI_${DEPLOY_ENV}" | tr [a-z] [A-Z]`
export REPO_URI="${!REPO_URI_VAR}"
CLUSTER_VAR=`echo "CLUSTER_${DEPLOY_ENV}" | tr [a-z] [A-Z]`
export CLUSTER="${!CLUSTER_VAR}"

OLD_IFS=$IFS
IFS='/'
read AWS_DOCKER_REGISTRY AWS_DOCKER_REPO <<< "${REPO_URI}"
IFS=$OLD_IFS
export AWS_DOCKER_REGISTRY=${AWS_DOCKER_REGISTRY}
export AWS_DOCKER_REPO=${AWS_DOCKER_REPO}

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
