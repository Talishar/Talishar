<?php

require_once __DIR__.'/vendor/autoload.php';
 
use Patreon\API;
use Patreon\OAuth;

// This example shows you how to create a webhook at Patreon API to notify you when you have any member changes in your campaign

// Create a client first, using your creator's access token
$api_client = new API('YOURCREATORSACCESSTOKEN');

// If you dont know the campaign id you are targeting already, fetch your campaigns and get the id for the campaign you need. If you already know your campaign id, just skip this part

$campaigns_response = $api_client->fetch_campaigns();

// Get the campaign id
$campaign_id = $campaigns_response['data'][0]['id'];
// If you have more than one campaign in the return, you have to iterate to find the one you want. If return format is array (as is default), just iterate the array and get the id you want

// Now, set the API client's cURL request method to POST because webhooks endpoint requires POST for creating webhooks. No need to do that for other requests since API client defaults to GET. But if you set this for a specific instance of the client to anything other than GET, you need to revert it back to default by re-setting it to GET after you make your POST call

$api_client->api_request_method = 'POST';

// Now set the POSTFIELDS that will contain the payload in cURL request and create the webhook. This particular webhook notifies your application whenever you get a new member, a member updates his/her membership, or a member is removed

$api_client->curl_postfields = array (
	'data' => array (
		'type' => 'webhook',
		'attributes' => array (
			'triggers' => array (
				'members:create',
				'members:update',
				'members:delete',
			),
			'uri' => 'https://yourdomain.com/webhook_receiver', // Replace https://yourdomain.com/webhook_receiver with the url at your site/app thats going to receive webhook requests. Note that your url must start with https://
		),
		'relationships' => array (
			'campaign' => array (
				'data' => array (
					'type' => 'campaign',
					'id' => $campaign_id, // Notice how your campaign id has to be inserted here
				),
			),
		),
	),
);

// Now, json_encode the array because webhooks endpoint requires JSON object

$api_client->curl_postfields = json_encode( $api_client->curl_postfields );

// Do the request by directly using get_data function that contacts the API and use the webhooks endpoint

$webhook_response = $api_client->get_data('webhooks');

// If all went well, you will receive a response as depicted in the API documentation here
// https://docs.patreon.com/#triggers-v2
// Except it is decoded as an array - or whatever format you set the API client to decode returns in


