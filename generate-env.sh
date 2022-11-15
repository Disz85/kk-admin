#!/bin/bash

(
echo APP_NAME=\"Kremmania Admin\"
echo APP_ENV=\"$APP_ENV\"
echo APP_KEY=\"$APP_KEY\"
echo APP_DEBUG=false
echo APP_URL=\"$APP_URL\"

echo LOG_CHANNEL=stack
echo LOG_DEPRECATIONS_CHANNEL=null
echo LOG_LEVEL=debug

echo DB_CONNECTION=mysql
echo DB_HOST=\"$DB_HOST\"
echo DB_PORT=3306
echo DB_DATABASE=\"$DB_DATABASE\"
echo DB_USERNAME=\"$DB_USERNAME\"
echo DB_PASSWORD=\"$DB_PASSWORD\"

echo BROADCAST_DRIVER=log
echo CACHE_DRIVER=redis
echo QUEUE_CONNECTION=beanstalkd
echo SESSION_DRIVER=file
echo SESSION_LIFETIME=20160

echo REDIS_HOST=\"$REDIS_HOST\"
echo REDIS_PASSWORD=\"$REDIS_PASSWORD\"
echo REDIS_PORT=6379

echo MAIL_DRIVER=smtp
echo MAIL_HOST=\"$MAIL_HOST\"
echo MAIL_PORT=25
echo MAIL_USERNAME=null
echo MAIL_PASSWORD=null
echo MAIL_ENCRYPTION=null
echo MAIL_FROM_ADDRESS=info@kremmania.hu
echo MAIL_FROM_NAME=Kremmania

echo AWS_ACCESS_KEY_ID=
echo AWS_SECRET_ACCESS_KEY=
echo AWS_DEFAULT_REGION=
echo AWS_BUCKET=
echo AWS_URL=

# File Storage
echo MINIO_ENDPOINT=\"$MINIO_ENDPOINT\"
echo MINIO_KEY=\"$MINIO_KEY\"
echo MINIO_SECRET=\"$MINIO_SECRET\"
echo MINIO_REGION=eu-central-1
echo MINIO_BUCKET=kremmania

echo FILE_STORAGE_DISK=minio

# Service Account Credentials (used by backend, CRON jobs)
echo SSO_REALM_URL=\"$SSO_REALM_URL\"
echo SSO_TOKEN_URL=\"$SSO_TOKEN_URL\"
echo SSO_CLIENT_ID=\"$SSO_CLIENT_ID\"
echo SSO_PUBLIC_KEY=\"$SSO_PUBLIC_KEY\"
echo SSO_CLIENT_SECRET=\"$SSO_CLIENT_SECRET\"

#Image API
echo IMAGE_API_URL=\"$IMAGE_API_URL\"

# Frontend
echo FRONTEND_URL=\"$FRONTEND_URL/\"

#ELASTICSEARCH
echo ELASTIC_SEARCH_HOST=\"$ELASTIC_SEARCH_HOST\"
echo ELASTIC_SEARCH_PORT=9200

# BEANSTALKD
echo BEANSTALKD_HOST=\"$BEANSTALKD_HOST\"
echo BEANSTALKD_PORT=\"$BEANSTALKD_PORT\"
) > .env
