<?php

include './vendor/autoload.php';

use VK\Client\VKApiClient;

$config = require 'config.php';

if (!isset($argv[1])) {

    die('Не передан id пользователя' . PHP_EOL);
}

$userId = $argv[1];

$path = __DIR__ . '/result/' . $userId . '/';

if (!is_dir($path)) {

    mkdir($path, 0777);
}

$vk = new VKApiClient();

// друзья
\Helper\VKHelper::getExecuteFullItems('API.friends.get', 5000, [
    'user_id' => $userId,
], $config['application']['accessToken'], function (array $items, int $page) use($path) {

    file_put_contents($path . '/friends_' . $page, implode("\n", $items));
});

// подписки
\Helper\VKHelper::getExecuteFullItems('API.users.getFollowers', 1000, [
    'user_id' => $userId,
], $config['application']['accessToken'], function (array $items, int $page) use($path) {

    file_put_contents($path . '/followers_' . $page, implode("\n", $items));
});
