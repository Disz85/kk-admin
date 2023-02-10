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
host('km-test-02')
    ->setLabels(['stage' => 'test'])
    ->set('branch', 'test');

host('km-web-02', 'km-web-03')
    ->setLabels(['stage' => 'production'])
    ->set('branch', 'production');

### Tasks #####################################################

task('upload', function () {
    upload(__DIR__ . "/", '{{release_path}}');
});

task('worker-restart', function () {
    run('{{bin/php}} {{release_path}}/artisan queue:restart');
});

task('l5swagger-generate', function () {
    run('{{bin/php}} {{release_path}}/artisan l5-swagger:generate');
})->select('stage=test');

task('deploy', [
    'deploy:prepare',
    'upload',
    'artisan:migrate',
    'artisan:storage:link',
    'artisan:config:cache',
    'deploy:shared',
    'l5swagger-generate',
    'deploy:symlink',
    'worker-restart',
    'deploy:unlock',
    'deploy:cleanup',
    'deploy:success'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

