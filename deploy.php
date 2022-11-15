<?php

namespace Deployer;

require 'recipe/laravel.php';

### Project #####################################################
set('application', 'km-admin');
set('allow_anonymous_stats', false);

add('shared_dirs', [
    'storage'
]);

set('shared_files', [
    'public/keycloak.json'
]);

add('writable_dirs', [
    'storage/logs'
]);

### Hosts #####################################################
host('km-test-01')
    ->stage('test')
    ->set('http_user', 'nginx')
    ->set('branch', 'test')
    ->set('deploy_path', '/www/local/{{application}}')
    ->user('admin.kremmania.deploy')
    ->forwardAgent(true)
    ->multiplexing(true);

host('[TODO-CI]')
    ->stage('production')
    ->set('http_user', 'nginx')
    ->set('branch', 'production')
    ->set('deploy_path', '/www/local/{{application}}')
    ->user('admin.kremmania.deploy')
    ->forwardAgent(true)
    ->multiplexing(true);

### Tasks #####################################################

task('upload', function () {
    upload(__DIR__ . "/", '{{release_path}}');
});

task('artisan:editor', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --provider="VanOns\Laraberg\LarabergServiceProvider"');
});

task('fpm-reload', function () {
    run('sudo systemctl reload php-fpm');
})->onHosts([ 'km-test-01']);

task('worker-restart', function () {
    run('{{bin/php}} {{release_path}}/artisan queue:restart');
})->onHosts(['km-test-01']);

task('release', [
    'deploy:prepare',
    'deploy:release',
    'upload',
    'artisan:migrate',
    'artisan:storage:link',
    'artisan:editor',
    'artisan:config:cache',
    'deploy:shared',
    'deploy:symlink',
    'fpm-reload',
    'worker-restart',
]);

task('deploy', [
    'release',
    'cleanup',
    'success'
]);
