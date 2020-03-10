<?php

include './vendor/autoload.php';

use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

$config = require 'config.php';


$oauth = new VKOAuth();
$client_id = $config['application']['id'];
$state = $config['application']['secret'];
$display = VKOAuthDisplay::PAGE;
$scope = array(VKOAuthGroupScope::MESSAGES);

$browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::TOKEN, $client_id, '', $display, [], $state);


echo $browser_url . PHP_EOL;