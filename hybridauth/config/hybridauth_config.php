<?php

$config_hybridauth = [   
    'providers' => [
        'Google' => [
            'enabled' => true,
			'callback' => $sys['abs_url'].'login?provider=Google',	
            'keys' => [
                'id' => 'your-google-client-id',
                'secret' => 'your-google-client-secret',
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
            'access_type' => 'online',
            'approval_prompt' => 'auto',
        ],
        'Yandex' => [
            'enabled' => true,
			'callback' => $sys['abs_url'].'login?provider=Yandex',
            'keys' => [
                'id' => 'your-yandex-client-id',
                'secret' => 'your-yandex-client-secret',
            ],
        ],
        'VKontakte' => [
            'enabled' => true,
			'callback' => $sys['abs_url'].'login?provider=Vkontakte',
            'keys' => [
                'id' => 'your-vk-client-id',
                'secret' => 'your-vk-client-secret',
            ],
            'scope' => 'email',
        ],		
        'Mailru' => [
            'enabled' => true,
            'keys' => [
                'id' => 'your-mailru-client-id',
                'secret' => 'your-mailru-client-secret',
            ],
        ],
    ],
	'debug_mode' => false,
	'debug_file' => SED_ROOT . '/plugins/hybridauth/hybridauth.log'
];
