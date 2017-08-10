<?php

/**
 * Require the Kotori.php using Composer's autoloader
 *
 * If you are not using Composer, you need to load Kotori.php with your own
 * PSR-4 autoloader.
 */

require __DIR__ . '/../../vendor/autoload.php';

$config = [
    'app_name' => 'app',
    // 'app_name' => [
    //     '1.kotori.php' => 'app',
    //     '2.kotori.php' => 'module2'
    // ],
    'db' => [
        'test' => [
            'host' => getenv('MYSQL_HOST') ? getenv('MYSQL_HOST') : '127.0.0.1',
            'user' => getenv('MYSQL_USER') ? getenv('MYSQL_USER') : 'root',
            'pwd' => getenv('MYSQL_PWD') ? (getenv('CI') ? '' : getenv('MYSQL_PWD')) : '123456',
            'name' => getenv('MYSQL_DB') ? getenv('MYSQL_DB') : 'test',
            'type' => 'mysql',
        ],
    ],
    // 'cache' => [
    //     'adapter' => 'memcached',
    // ],
    // 'session' => [
    //     'adapter' => 'memcached',
    // ],
    'url_mode' => 'path_info',
    'url_route' => [
        '/' => 'Hello/index',
        'news/([0-9])' => 'Hello/showNews/$1',
        'add' => [
            'get' => 'Hello/addNews',
            'post' => 'Hello/insertNews',
            'delete' => 'Hello/deleteNews',
        ],
        'captcha' => 'Hello/captcha',
        'memcache' => 'Hello/memcache',
        'cliTest/(.*)' => [
            'cli' => 'Hello/cli/$1',
        ],
        'test/get' => 'Test/receiveGet',
        'test/post' => 'Test/receivePost',
    ],
];

$app = new \Kotori\App($config);

$app->run();
