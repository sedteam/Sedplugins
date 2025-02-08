<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.auth.first.php
Version=180
Updated=2025-feb-07
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=main
File=hybridauth.users.auth.first
Hooks=users.auth.first
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

use Hybridauth\Hybridauth;
use Hybridauth\HttpClient\CurlClient;

$provider = sed_import('provider', 'G', 'TXT'); 

if (!empty($provider)) {

	require_once(SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php');
	require_once(SED_ROOT . '/plugins/hybridauth/lib/autoload.php');

	session_start();

	$hybridauth = new Hybridauth($config_hybridauth);	
	
    try {
        $adapter = $hybridauth->authenticate($provider);
        $userProfile = $adapter->getUserProfile();

        $sql = sed_sql_query("SELECT user_id, user_skin, user_secret FROM $db_users WHERE user_oauth_uid='" . sed_sql_prep($userProfile->identifier) . "' AND user_oauth_provider='" . sed_sql_prep($provider) . "'");

        if (sed_sql_numrows($sql) == 1) {
            $row = sed_sql_fetchassoc($sql);
            $ruserid = $row['user_id'];
            $rdefskin = $row['user_skin']; 
            $rmdpass_secret = ($cfg['authsecret']) ? md5(sed_unique(16)) : $row['user_secret'];

            sed_sql_query("UPDATE $db_users SET user_secret = '" . $rmdpass_secret . "', user_lastip='" . $usr['ip'] . "' WHERE user_id='" . $row['user_id'] . "' LIMIT 1");

			if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {		
				$rcookiettl = ($rcookiettl == 0) ? 604800 : $rcookiettl;
				$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
				$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
				sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
			}

			if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
				$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
				$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
				$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
			}

            sed_redirect(sed_url("message", "msg=104&redirect=" . $redirect, "", true));
            exit;
        } else {
            $token = $adapter->getAccessToken();

			$defgroup = 4;
			
			$mdsalt = sed_unique(16);  
			$mdpass = sed_hash(sed_unique(8), 1, $mdsalt);  

			$rmdpass_secret = md5(sed_unique(16)); 
			
			$ruserfirstname = $userProfile->firstName;
			$ruserlastname = $userProfile->lastName;
			$rusername = $userProfile->displayName;
			
			/**/
			$ruseremail = $userProfile->email; // ????????????? TODO
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_email='" . sed_sql_prep($ruseremail) . "'");
			$ruseremail = (sed_sql_result($sql, 0, "COUNT(*)") > 0) ? "" : $ruseremail; // ???????			
			/**/
						
			$rtimezone = $cfg['defaulttimezone'];
			
			$ruserbirthdate = (isset($userProfile->birthDay) && isset($userProfile->birthMonth) && isset($userProfile->birthYear)) ? sed_mktime(1, 0, 0, $userProfile->birthMonth, $userProfile->birthDay, $userProfile->birthYear) : 0;			
			
			$rusergender = 'U';
			
			if (isset($userProfile->gender)) {
				$gender = $userProfile->gender;
				switch ($gender) {
					case 'male':
						$rusergender = 'M';
						break;
					case 'female':
						$rusergender = 'F';
						break;
					case 'other':
						$rusergender = 'U';
						break;
					case '0': 
						$rusergender = 'U';
						break;
					case '1': 
						$rusergender = 'F';
						break;
					case '2':
						$rusergender = 'M';
						break;
					default:
						$rusergender = 'U';
						break;
				}
			} 			
						
			$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name='" . sed_sql_prep($rusername) . "'");
			
			if (sed_sql_result($sql, 0, "COUNT(*)") > 0) {
				$rusername = $rusername.mt_rand(2, 9999);
			}

			$sql = sed_sql_query("INSERT into $db_users
				(user_name,
				user_firstname,
				user_lastname,
				user_password,
				user_salt,
				user_secret,
				user_passtype,
				user_maingrp,
				user_email,
				user_hideemail,
				user_pmnotify,
				user_skin,
				user_lang,
				user_regdate,
				user_logcount,
				user_gender,
				user_birthdate,				
				user_lastip,
				user_oauth_uid, 
				user_oauth_provider, 
				user_token
				)
				VALUES
				('" . sed_sql_prep($rusername) . "',
				'" . sed_sql_prep($ruserfirstname) . "',
				'" . sed_sql_prep($ruserlastname) . "',            
				'$mdpass',
				'$mdsalt',
				'$rmdpass_secret',
				1,
				" . (int)$defgroup . ",
				'" . sed_sql_prep($ruseremail) . "',
				1,
				1,
				'" . $cfg['defaultskin'] . "',
				'" . $cfg['defaultlang'] . "',
				" . (int)$sys['now_offset'] . ",
				0,
				'" . sed_sql_prep($rusergender) . "',
				" . (int)$ruserbirthdate . ",				
				'" . $usr['ip']."',
				'" . sed_sql_prep($userProfile->identifier) . "', 
				'" . sed_sql_prep($provider) . "', 
				'" . sed_sql_prep($token['access_token']) . "'				
				)");

			$ruserid = sed_sql_insertid();
			$sql = sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$ruserid . ", " . (int)$defgroup . ")");			
			
            $rdefskin = $cfg['defaultskin'];
			
			if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {		
				$rcookiettl = ($rcookiettl == 0) ? 604800 : $rcookiettl;
				$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
				$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
				sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
			}

			if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
				$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
				$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
				$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
			}			

            sed_redirect(sed_url("message", "msg=104&redirect=" . $redirect, "", true));
            exit;
        }
    } catch (Exception $e) {
        sed_diefatal('Hybridauth error : ' .  $e->getMessage());
    }
}



