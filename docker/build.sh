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

# Create the working directory archive to import into the final build
echo "Pull the archived repo for commit: $TRAVIS_COMMIT"
mkdir ${TRAVIS_BUILD_DIR}/docker/deploy
git archive --format=tar --worktree-attributes ${TRAVIS_COMMIT} | tar -xf - -C ${TRAVIS_BUILD_DIR}/docker/deploy

echo "Install dependencies.."
cd ${TRAVIS_BUILD_DIR}/docker/deploy
composer install --no-dev --no-interaction --optimize-autoloader
cd ${TRAVIS_BUILD_DIR}

# Install AWS-CLI
if ! command -v aws &> /dev/null; then
    echo "Installing AWS CLI..."
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "$HOME/awscliv2.zip"
    rm -rf ${HOME}/aws-tmp
    unzip -q awscliv2.zip -d ${HOME}/aws-tmp
    sudo ${HOME}/aws-tmp/aws/install
    echo `aws --version`
    rm -r ${HOME}/aws-tmp
    rm awscliv2.zip
fi

if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    echo "Creating accessrole profile for IAM Role: $AWS_IAM_ROLE_ARN"
    mkdir -p ${HOME}/.aws
    cat <<EOF > $HOME/.aws/config
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

echo $SECRET | python -c "import json,sys;obj=json.load(sys.stdin);print obj['SecretString'];" > ${TRAVIS_BUILD_DIR}/docker/deploy/.env

echo "Build the Docker Image: $AWS_DOCKER_REPO:$TRAVIS_COMMIT"
docker context use default

# echo "Build the app image..."
docker build -t ${AWS_DOCKER_REPO}:${TRAVIS_COMMIT} ${TRAVIS_BUILD_DIR}/docker

echo "Tag the Docker Image: $REPO_URI:latest"
# echo "Tag the app image"
docker tag $AWS_DOCKER_REPO:${TRAVIS_COMMIT} "$REPO_URI:latest"
