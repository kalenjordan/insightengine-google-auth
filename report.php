<?php

ini_set('display_errors', 1);
session_start();

if (! isset($_SESSION['ga_access_token']) || ! $_SESSION['ga_access_token']) {
    die("Missing access token");
}

require_once('vendor/autoload.php');

$client = new Google_Client();
$client->setAuthConfigFile('etc/client_secrets.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');

$accessToken = $_SESSION['ga_access_token'];
$client->setAccessToken($accessToken);

$analytics = new Google_Service_Analytics($client);
$analytics_id   = 'ga:392420';
$lastWeek       = date('Y-m-d', strtotime('-30 day'));
$today          = date('Y-m-d');

// print_r($analytics->management_accounts->listManagementAccounts());

$optParams = array();
// Uncomment any of the optional parameters to include them in your query
$optParams['dimensions'] = "ga:date";
//$optParams['sort'] = "";
//$optParams['filters'] = "";
//$optParams['max-results'] = "";
$metrics = 'ga:avgPageLoadTime, ga:transactionsPerSession';
$results = $analytics->data_ga->get($analytics_id,
    $lastWeek,
    $today,$metrics,$optParams);

echo "<table border=1>
<tr>
<td>Date</td>
<td>Avg Page Load Time</td>
<td>Conversion Rate</td>
</tr>
";
foreach ($results['rows'] as $rowData) {
    $dateRaw = substr($rowData[0], 0, 4) . "-" . substr($rowData[0], 4, 2) . "-" . substr($rowData[0], 6, 2);
    $date = date("M d, Y", strtotime($dateRaw));

    $pageLoadTime = number_format($rowData[1], 1) . 's';
    $conversionRate = number_format($rowData[2], 2) . '%';
    echo "<tr>
    <td>$date</td>
    <td>$pageLoadTime</td>
    <td>$conversionRate</td>
    </tr>
    ";
}
echo "</table>";

// print_r($results['rows']);