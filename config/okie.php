<?php

return [
	'upload' => [
		'basename' => 'uploads',
		'fullpath' => public_path( 'uploads/' )
	],
	'banner' => [
		'quality' => [
			'jpg' =>90,
			'image' => 80
		],
		'full_width' => false,
		'interval' => 3000,
		'sizes' => [
			'square' => [
				'size' => 50,
				'suffix' => 'sqr'
			],
			'thumbnail' => [
				'size' => 500,
				'suffix' => 'thn'
			],
			'small' => [
				'width' => 150,
				'height' => null,
				'suffix' => 'sml'
			],
			'medium' => [
				'width' => 300,
				'height' => null,
				'suffix' => 'mdm'
			],
			'large' => [
				'width' => 600,
				'height' => null,
				'suffix' => 'lrg'
			],
			'original' => [
				'suffix' => 'org'
			]
		]
	],
	'privacy_policy' => [
		'url' => 'privacy-policy',
		'contents' => '<p>This Privacy Policy discloses the privacy practices for the Okie Online Store website (collectively, the “Website” located at www.okie.website). Okie Online Store, the provider of the Website (referred to as “us” or “we”), is committed to protecting your privacy online. Please read the following to learn what information we collect from you (the “User” or the “End User”) and how we use that information. If you have any questions about our privacy policy, please email us at support@okie.website.</p>'
	],
	'terms_and_conditions' => [
		'url' => 'terms-and-conditions',
		'contents' => '<p>Insert Terms and Conditions here.</p>'
	]

];
 
