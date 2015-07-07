<?php

ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['ga_access_token']) && $_SESSION['ga_access_token']) {
    header("location: report.php");
}

require_once('vendor/autoload.php');

$client = new Google_Client();
$client->setAuthConfigFile('etc/client_secrets.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);

$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));