<?php
/*
|--------------------------------------------------------------------------
| Git Hook
|--------------------------------------------------------------------------
|
| Handles git webhooks for deployment
|
*/

$input_json = file_get_contents("php://input");

if(empty($input_json))
	throw new \Exception('No post input received.');

$input = json_decode($input_json, true);
if(json_last_error() !== JSON_ERROR_NONE)
	throw new \Exception('An error occurred while attempting to parse the JSON input.');

// For now, just launch deployment script on server
$output = shell_exec("bash /scripts/deploy.sh");
file_put_contents('/tmp/deploy_out.log', $output);