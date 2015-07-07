<?php

ini_set('display_errors', 1);
session_start();

require_once('vendor/autoload.php');

$client = new Google_Client();
$client->setAuthConfigFile('etc/client_secrets.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');

$client->authenticate($_GET['code']);
$accessTokenJson = $client->getAccessToken();

$_SESSION['ga_access_token'] = $accessTokenJson;
header("location:/report.php");