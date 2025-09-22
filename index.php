<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use TelegramBot\Bot;

$config = parse_ini_file('.env');

$botToken = $config['TELEGRAM_BOT_TOKEN'] ?? null;
$timewebLogin = $config['TIMEWEB_LOGIN'] ?? null;
$timewebPassword = $config['TIMEWEB_PASSWORD'] ?? null;
$timewebAppkey = $config['TIMEWEB_APPKEY'] ?? null;
$listDomains = $config['LIST_DOMAINS'] ?? '';

if (!$botToken || !$timewebLogin || !$timewebPassword || !$timewebAppkey) {
    die('Environment variables must be set.');
}

$bot = new Bot($botToken, $timewebLogin, $timewebPassword, $timewebAppkey, $listDomains);

// Handle webhook
$input = file_get_contents('php://input');
$update = json_decode($input, true);

if ($update) {
    $bot->handleUpdate($update);
}

echo 'OK';
?>