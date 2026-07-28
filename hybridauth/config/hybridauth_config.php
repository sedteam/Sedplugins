<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

Hybridauth configuration file.
Edit provider keys to enable social login.

Callback URL for all providers (set in provider app settings):
  SEF:     https://yoursite.com/plug/hybridauth
  non-SEF: https://yoursite.com/index.php?module=plug&e=hybridauth
==================== */

$ha_callback = rtrim($sys['abs_url'], '/') . '/' . sed_url('plug', 'e=hybridauth', '', false, false);

$config_hybridauth = array(
    'callback' => $ha_callback,
    'providers' => array(
        'Vkontakte' => array(
            'enabled' => true,
            'keys' => array(
                'id' => '',
                'secret' => '',
            ),
            'scope' => 'email',
        ),
        'Yandex' => array(
            'enabled' => true,
            'keys' => array(
                'id' => '',
                'secret' => '',
            ),
        ),
        'Google' => array(
            'enabled' => false,
            'keys' => array(
                'id' => '',
                'secret' => '',
            ),
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
            'access_type' => 'online',
            'approval_prompt' => 'auto',
        ),
        'SberId' => array(
            'enabled' => false,
            'keys' => array(
                'id' => '',
                'secret' => '',
            ),
            'scope' => 'openid name email mobile',
        ),
        'Mailru' => array(
            'enabled' => true,
            'keys' => array(
                'id' => '',
                'secret' => '',
            ),
        ),
    ),
    'debug_mode' => false,
    'debug_file' => SED_ROOT . '/plugins/hybridauth/hybridauth.log'
);
