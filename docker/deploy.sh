#!/usr/bin/env bash

# Requires the following environment variables:
# $DEPLOY_ENV = The environment (production/staging).
# $REPO_URI = The URI of the ECR repo to push to.
# $CLUSTER = The name of the ECS cluster to deploy to.
# $AWS_DEFAULT_REGION = The AWS region.
# $AWS_IAM_ROLE_ARN = The ARN of an access role to access aws as [optional]

# Bail out on first error.
set -e

source ${TRAVIS_BUILD_DIR}/docker/deploy/.env

# Retrieve an authentication token and authenticate Docker client to project registry.
if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    echo "Logging in to ECR docker registry: $AWS_DOCKER_REGISTRY in region $AWS_DEFAULT_REGION as IAM_Role $AWS_IAM_ROLE_ARN"
    aws ecr get-login-password --profile accessrole --region ${AWS_DEFAULT_REGION} | docker login --username AWS --password-stdin ${AWS_DOCKER_REGISTRY}
else
    echo "Logging in to ECR docker registry: $AWS_DOCKER_REGISTRY in region $AWS_DEFAULT_REGION"
    aws ecr get-login-password --region ${AWS_DEFAULT_REGION} | docker login --username AWS --password-stdin ${AWS_DOCKER_REGISTRY}
fi

docker context use default

# echo "Push the tagged images to the repo..."
docker push ${REPO_URI}:${TRAVIS_COMMIT}
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
