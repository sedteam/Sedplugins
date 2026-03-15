<?php
namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Data;

/**
 * Vkontakte OAuth2 adapter for VK ID (OAuth 2.1)
 */
class Vkontakte extends OAuth2
{
    protected $apiBaseUrl = 'https://api.vk.com/method/';
    protected $authorizeUrl = 'https://id.vk.com/authorize';
    protected $accessTokenUrl = 'https://id.vk.com/oauth2/auth';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->callback = rtrim($this->config->get('callback'), '/');
        $this->AuthorizeUrlParameters['redirect_uri'] = $this->callback;

        $deviceId = $this->getStoredData('device_id');
        if (!$deviceId) {
            $deviceId = $this->generateRandomString(16);
            $this->storeData('device_id', $deviceId);
        }

        $codeVerifier = $this->getStoredData('code_verifier');
        if (!$codeVerifier) {
            $codeVerifier = $this->generateRandomString(64);
            $this->storeData('code_verifier', $codeVerifier);
        }

        $this->AuthorizeUrlParameters['device_id'] = $deviceId;
        $this->AuthorizeUrlParameters['code_challenge'] = $this->generateCodeChallenge($codeVerifier);
        $this->AuthorizeUrlParameters['code_challenge_method'] = 'S256';
        $this->AuthorizeUrlParameters['v'] = '5.131';
    }

    /**
     * {@inheritdoc}
     */
    protected function exchangeCodeForAccessToken($code)
    {
        $receivedDeviceId = isset($_GET['device_id']) ? $_GET['device_id'] : $this->getStoredData('device_id');
        $codeVerifier = $this->getStoredData('code_verifier');

        $this->tokenExchangeParameters = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $this->callback,
            'device_id'     => $receivedDeviceId,
            'code_verifier' => $codeVerifier,
        ];

        $response = $this->httpClient->request(
            $this->accessTokenUrl,
            'POST',
            $this->tokenExchangeParameters
        );

        $this->validateApiResponse('Unable to exchange code for API access token');

        return $response;
    }

	/**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('https://id.vk.com/oauth2/user_info', 'POST', [
            'client_id'    => $this->clientId,
            'access_token' => $this->getStoredData('access_token')
        ]);

        if (!isset($response->user)) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userInfo = $response->user;
        $userProfile = new \Hybridauth\User\Profile();
        
        $userProfile->identifier  = isset($userInfo->user_id) ? $userInfo->user_id : null;
        $userProfile->firstName   = isset($userInfo->first_name) ? $userInfo->first_name : null;
        $userProfile->lastName    = isset($userInfo->last_name) ? $userInfo->last_name : null;
        $userProfile->photoURL    = isset($userInfo->avatar) ? $userInfo->avatar : null;
        $userProfile->phone       = isset($userInfo->phone) ? $userInfo->phone : null;
        
        $email = isset($userInfo->email) ? $userInfo->email : (isset($response->email) ? $response->email : null);
        $userProfile->email = $email;

        if (!empty($email)) {
            $parts = explode('@', $email);
            $userProfile->displayName = $parts[0];
        } else {
            $userProfile->displayName = trim($userProfile->firstName . ' ' . $userProfile->lastName);
        }

        if (empty($userProfile->displayName)) {
            $userProfile->displayName = 'vk_user_' . $userProfile->identifier;
        }

        $userProfile->profileURL = 'https://vk.com/id' . $userProfile->identifier;

        if (!empty($userInfo->birthday)) {
            $bday = explode('.', $userInfo->birthday);
            $userProfile->birthDay   = isset($bday[0]) ? (int)$bday[0] : null;
            $userProfile->birthMonth = isset($bday[1]) ? (int)$bday[1] : null;
            $userProfile->birthYear  = isset($bday[2]) ? (int)$bday[2] : null;
        }

        $gender = isset($userInfo->gender) ? (int)$userInfo->gender : 0;
        switch ($gender) {
            case 1:
                $userProfile->gender = 'female';
                break;
            case 2:
                $userProfile->gender = 'male';
                break;
        }

        $userProfile->data = [
            'education' => isset($userInfo->education) ? $userInfo->education : null,
        ];

        return $userProfile;
    }

    protected function generateRandomString($length = 64)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $max)];
        }
        return $randomString;
    }

    protected function generateCodeChallenge($verifier)
    {
        $hash = hash('sha256', $verifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }
}