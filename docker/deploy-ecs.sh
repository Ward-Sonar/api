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

# Set environment variables.
echo "Setting deployment configuration for ${DEPLOY_ENV}..."
export ENV_SECRET_ID=".env.api.${DEPLOY_ENV}"
export SERVICE="api"

# Install AWS-CLI
if ! command -v aws &> /dev/null; then
    echo "Installing AWS CLI..."
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    rm -rf ./aws-tmp
    unzip -q awscliv2.zip -d aws-tmp
    sudo ./aws-tmp/aws/install
    echo `aws --version`
    rm -r aws-tmp
    rm awscliv2.zip
fi

if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    echo "Creating accessrole profile for IAM Role: $AWS_IAM_ROLE_ARN"
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

# Get the .env file.
if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    echo "Creating .env file from secret: $ENV_SECRET_ID as IAM Role: $AWS_IAM_ROLE_ARN"
    SECRET=`aws secretsmanager get-secret-value \
        --profile accessrole \
        --secret-id ${ENV_SECRET_ID}`
else
    echo "Creating .env file from secret: $ENV_SECRET_ID"
    SECRET=`aws secretsmanager get-secret-value \
        --secret-id ${ENV_SECRET_ID}`
fi

echo $SECRET | python -c "import json,sys;obj=json.load(sys.stdin);print obj['SecretString'];" > .env

source .env

# If not a Travis build set the commit to use to create the archive
if [ -z "${TRAVIS_COMMIT}" ]; then
    TRAVIS_COMMIT=`git rev-parse HEAD`
else
    cd ${TRAVIS_BUILD_DIR}
fi
# Create the working directory archive to import into the final build
echo "Pull the archived repo for commit: $TRAVIS_COMMIT"
git archive -o docker/app.tar --worktree-attributes ${TRAVIS_COMMIT}
tar -rf docker/app.tar .env


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
docker build -t ${AWS_DOCKER_REPO} ./docker

# echo "Tag the app image"
docker tag "$AWS_DOCKER_REPO:latest" "$REPO_URI:latest"

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
