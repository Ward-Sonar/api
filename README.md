# api

## Deployment

Deploy is via Travis and AWS

### Domain

If the domain has already been created for the frontend, skip this step.

1. Create a hosted zone in Route53 for wardsonar.co.uk
2. Create a hosted zone in Route53 for staging.wardsonar.co.uk
3. Create a ns record in wardsonar.co.uk with the staging.wardsonar.co.uk nameservers

### Certificates

1. Create wildcard certificate in AWS Certificate Manager (_.wardsonar.co.uk, _.staging.wardsonar.co.uk) in the required region (e.g. eu-west-2)
2. Verify via DNS and allow Certificate Manager to create the records in Route53
3. make a note of the certificate ARNs as they will be used in Cloudformation

### Cloudformation

1. Run aws/create-ecs.sh to generate the cloudformation template as aws/cloudformation.json
2. Create a stack for staging and production using the aws/cloudformation.json template
3. Set the ApiTaskCount at 0 until the stack is created, then change this to 2 with an update.
4. In the parameters, choose either production or staging for the Environment
5. Enter the relevant certificate ARN for the staging or production certificate
6. Enter the database credentials. Record these as they will not be accessible later but are required for the Secret
7. Select all the subnets that are required. If this is the default VPC then select all.
8. Choose the VPC, this should be the default.

### Secrets Manager

Each environment requires a secret in AWS. Each secret should be created in the local region (e.g. eu-west-2) and named in the format:

.env.frontend.[ENVIRONMENT]

Where [ENVIRONMENT] is one of:

-   production
-   staging

Each Secret value should take the form:

```
APP_NAME=WardSonar
APP_ENV=
APP_KEY=
APP_DEBUG=
APP_URL=

LOG_CHANNEL=stack
LOG_LEVEL=

DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_LIFETIME=120

MAIL_MAILER=log
```

Where:

-   APP_ENV is production or staging
-   APP_KEY is left blank as this will be generated during deploy
-   APP_DEBUG is true or false
-   APP_URL is the root url (including scheme) of the api
-   LOG_LEVEL is a logging level e.g. debug, error
-   DB_HOST is the AWS RDB endpoint
-   DB_DATABASE is the name of the database assigned in the Cloudformation build
-   DB_USERNAME is the database master user username assigned in the Cloudformation build
-   DB_PASSWORD is the database master user password assigned in the Cloudformation build
