# Install AWS-CLI
if ! command -v aws &> /dev/null; then
    echo "Installing AWS CLI..."
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    rm -rf ./aws-tmp
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

# Install Docker Compose CLI
if ! command -v docker compose &> /dev/null; then
    curl -L https://raw.githubusercontent.com/docker/compose-cli/main/scripts/install/install_linux.sh | sh
fi

source ~/.bashrc

docker context rm -f ecscontext

if [ ! -z "${AWS_IAM_ROLE_ARN}" ]; then
    docker context create ecs ecscontext --profile accessrole
else
    docker context create ecs ecscontext
fi

docker context use ecscontext

docker compose --file ./docker-compose.yml up
