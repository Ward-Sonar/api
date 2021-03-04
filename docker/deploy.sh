#!/usr/bin/env bash

# Requires the following environment variables:
# $DEPLOY_ENV = The environment (production/staging).
# $REPO_URI_${DEPLOY_ENV} = The URI of the ECR repo to push to, e.g. REPO_URI_STAGING.
# $CLUSTER_${DEPLOY_ENV} = The name of the ECS cluster to deploy to, e.g. CLUSTER_STAGING.
# $AWS_ACCESS_KEY_ID = The AWS access key.
# $AWS_SECRET_ACCESS_KEY = The AWS secret access key.
# $AWS_DEFAULT_REGION = The AWS region.
# $AWS_IAM_ROLE_ARN = The ARN of an access role to access aws as [optional]

# Bail out on first error.
set -e

source ${TRAVIS_BUILD_DIR}/docker/deploy/.env

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
if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    echo "Logging in to ECR docker registry: $AWS_DOCKER_REGISTRY in region $AWS_DEFAULT_REGION as IAM_Role $AWS_IAM_ROLE_ARN"
    aws ecr get-login-password --profile accessrole --region ${AWS_DEFAULT_REGION} | docker login --username AWS --password-stdin ${AWS_DOCKER_REGISTRY}
else
    echo "Logging in to ECR docker registry: $AWS_DOCKER_REGISTRY in region $AWS_DEFAULT_REGION"
    aws ecr get-login-password --region ${AWS_DEFAULT_REGION} | docker login --username AWS --password-stdin ${AWS_DOCKER_REGISTRY}
fi

docker context use default

# echo "Build the app image..."
docker build -t ${AWS_DOCKER_REPO}:${TRAVIS_COMMIT} ${TRAVIS_BUILD_DIR}/docker

# echo "Tag the app image"
docker tag $AWS_DOCKER_REPO:${TRAVIS_COMMIT} "$REPO_URI:latest"

# echo "Push the tagged image to the repo..."
docker push "$REPO_URI:latest"

# Update the service.
if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    echo "Updating the ECS Cluster: $CLUSTER service: $SERVICE as IAM Role: $AWS_IAM_ROLE_ARN"
    aws ecs update-service \
        --profile accessrole \
        --cluster ${CLUSTER} \
        --service ${SERVICE} \
        --force-new-deployment
else
    echo "Updating the ECS Cluster: $CLUSTER service: $SERVICE"
    aws ecs update-service \
        --cluster ${CLUSTER} \
        --service ${SERVICE} \
        --force-new-deployment
fi