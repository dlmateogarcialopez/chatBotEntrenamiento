<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require './lib.php';
require_once __DIR__ . '/vendor/autoload.php';

$api = new chatBotApi();

$api->getProfileFacebook(2500176683399107);

