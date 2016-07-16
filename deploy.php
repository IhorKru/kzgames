<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */

require 'recipe/symfony.php';

// Set configurations
set('repository', 'git@github.com:IhorKru/jobbery.com.git');
set('shared_files', ['app/config/parameters.yml']);
set('shared_dirs', ['app/logs']);
set('writable_dirs', ['app/cache', 'app/logs']);

// Configure servers
server('production', 'jobbery.eu.pn', '21')
    ->user('2136439')
    ->password('80506043850Qq!')
    ->env('deploy_path', '/home/www/jobbery.eu.pn/web')
    ->stage('production');

/**
 * Restart php-fpm on success deploy.
 */
task('php-fpm:restart', function () {
    // Attention: The user must have rights for restart service
    // Attention: the command "sudo /bin/systemctl restart php-fpm.service" used only on CentOS system
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo /bin/systemctl restart php-fpm.service');
})->desc('Restart PHP-FPM service');

after('success', 'php-fpm:restart');


/**
 * Attention: This command is only for for example. Please follow your own migrate strategy.
 * Attention: Commented by default.  
 * Migrate database before symlink new release.
 */
 
// before('deploy:symlink', 'database:migrate');