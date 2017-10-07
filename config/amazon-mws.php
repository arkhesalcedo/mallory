<?php

return [
	'store' => [
		'US' => [
			'merchantId' => env('US_MERCHANT_ID'),
			'marketplaceId' => env('US_MARKET_PLACE_ID'),
			'keyId' => env('US_KEY_ID'),
			'secretKey' => env('US_SECRET_KEY'),
			'amazonServiceUrl' => env('US_AMAZON_SERVICE_URL')
		],
		'UK' => [
			'merchantId' => env('UK_MERCHANT_ID'),
			'marketplaceId' => env('UK_MARKET_PLACE_ID'),
			'keyId' => env('UK_KEY_ID'),
			'secretKey' => env('UK_SECRET_KEY'),
			'amazonServiceUrl' => env('UK_AMAZON_SERVICE_URL')
		],
		'CA' => [
			'merchantId' => env('CA_MERCHANT_ID'),
			'marketplaceId' => env('CA_MARKET_PLACE_ID'),
			'keyId' => env('CA_KEY_ID'),
			'secretKey' => env('CA_SECRET_KEY'),
			'amazonServiceUrl' => env('CA_AMAZON_SERVICE_URL')
		]
	],

	// Default service URL
	'AMAZON_SERVICE_URL' => 'https://mws.amazonservices.com/',

	'muteLog' => false
];