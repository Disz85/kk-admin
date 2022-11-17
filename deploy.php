<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'gitlab@glab.p24.hu:kremmania/km-admin.git');
set('remote_user', 'admin.kremmania.deploy');
set('http_user', 'nginx');
set('deploy_path', '/www/local/km-admin');
set('forwardAgent', true);
set('multiplexing', true);


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
host('test')
    ->setHostname('km-test-02')
    ->set('branch', 'test');

host('production')
    ->setHostName('[TODO-CI]')
    ->set('branch', 'production');

### Tasks #####################################################

task('upload', function () {
    upload(__DIR__ . "/", '{{release_path}}');
});

task('artisan:editor', function () {
    run('{{bin/php}} {{release_path}}/artisan vendor:publish --provider="VanOns\Laraberg\LarabergServiceProvider"');
});

task('fpm-reload', function () {
    run('sudo systemctl reload php-fpm');
});

task('worker-restart', function () {
    run('{{bin/php}} {{release_path}}/artisan queue:restart');
});

task('deploy', [
    'deploy:prepare',
    'upload',
    'deploy:vendors',
    'artisan:migrate',
    'artisan:storage:link',
    'artisan:editor',
    'artisan:config:cache',
    'deploy:shared',
    'deploy:symlink',
    'fpm-reload',
    'worker-restart',
    'deploy:cleanup',
    'deploy:success'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

