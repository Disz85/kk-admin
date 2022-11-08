#!/bin/bash

echo APP_NAME=\"Kremmania Admin\" > .env
echo APP_ENV=\"prod\" >> .env
echo APP_KEY=\"$APP_KEY\" >> .env
echo APP_DEBUG=false >> .env
echo APP_URL=\"$APP_URL\" >> .env
echo MIX_APP_URL=\"$MIX_APP_URL\" >> .env

echo LOG_CHANNEL=stack >> .env
echo LOG_DEPRECATIONS_CHANNEL=null >> .env
echo LOG_LEVEL=debug >> .env

echo DB_CONNECTION=mysql >> .env
echo DB_HOST=\"$DB_HOST\" >> .env
echo DB_PORT=3306 >> .env
echo DB_DATABASE=\"$DB_DATABASE\" >> .env
echo DB_USERNAME=\"$DB_USERNAME\" >> .env
echo DB_PASSWORD=\"$DB_PASSWORD\" >> .env

echo BROADCAST_DRIVER=log >> .env
echo CACHE_DRIVER=redis >> .env
echo QUEUE_CONNECTION=beanstalkd >> .env
echo SESSION_DRIVER=file >> .env
echo SESSION_LIFETIME=20160 >> .env

echo REDIS_HOST=\"$REDIS_HOST\" >> .env
echo REDIS_PASSWORD=\"$REDIS_PASSWORD\" >> .env
echo REDIS_PORT=6379 >> .env

echo MAIL_DRIVER=smtp >> .env
echo MAIL_HOST=\"$MAIL_HOST\" >> .env
echo MAIL_PORT=25 >> .env
echo MAIL_USERNAME=null >> .env
echo MAIL_PASSWORD=null >> .env
echo MAIL_ENCRYPTION=null >> .env
echo MAIL_FROM_ADDRESS=info@kremmania.hu >> .env
echo MAIL_FROM_NAME=Kremmania >> .env

echo AWS_ACCESS_KEY_ID= >> .env
echo AWS_SECRET_ACCESS_KEY= >> .env
echo AWS_DEFAULT_REGION= >> .env
echo AWS_BUCKET= >> .env
echo AWS_URL= >> .env

# File Storage
echo MINIO_ENDPOINT=\"$MINIO_ENDPOINT\" >> .env
echo MINIO_KEY=\"$MINIO_KEY\" >> .env
echo MINIO_SECRET=\"$MINIO_SECRET\" >> .env
echo MINIO_REGION=eu-central-1 >> .env
echo MINIO_BUCKET=kremmania >> .env

echo FILE_STORAGE_DISK=minio >> .env

# Service Account Credentials (used by backend, CRON jobs)
echo SSO_REALM_URL=\"$SSO_REALM_URL\" >> .env
echo SSO_TOKEN_URL=\"$SSO_TOKEN_URL\" >> .env
echo SSO_CLIENT_ID=\"$SSO_CLIENT_ID\" >> .env
echo SSO_PUBLIC_KEY=\"$SSO_PUBLIC_KEY\" >> .env
echo SSO_CLIENT_SECRET=\"$SSO_CLIENT_SECRET\" >> .env

#Image API
echo IMAGE_API_URL=\"$IMAGE_API_URL\" >> .env

# Frontend
echo FRONTEND_URL=\"$FRONTEND_URL/\" >> .env

#ELASTICSEARCH
echo ELASTIC_SEARCH_HOST=\"$ELASTIC_SEARCH_HOST\" >> .env
echo ELASTIC_SEARCH_PORT=9200 >> .env

# BEANSTALKD
echo BEANSTALKD_HOST=\"$BEANSTALKD_HOST\" >> .env
echo BEANSTALKD_PORT=\"$BEANSTALKD_PORT\" >> .env
