<?php
namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Data;
use Hybridauth\User;

/**
 * Sber ID OAuth2 provider adapter (OIDC).
 *
 * Authorize endpoint: https://id.sber.ru/CSAFront/oidc/authorize.do
 * Token endpoint:     https://id.sber.ru/CSAFront/oidc/token.do
 * UserInfo endpoint:  https://id.sber.ru/CSAFront/oidc/userinfo.do
 *
 * App registration: https://developers.sber.ru/
 */
class SberId extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $scope = 'openid name email mobile';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://id.sber.ru/CSAFront/oidc/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://id.sber.ru/CSAFront/oidc/authorize.do';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://id.sber.ru/CSAFront/oidc/token.do';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developers.sber.ru/';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        // Sber ID requires nonce for OIDC
        $nonce = $this->getStoredData('sberid_nonce');
        if (!$nonce) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
            $this->storeData('sberid_nonce', $nonce);
        }

        $this->AuthorizeUrlParameters['response_type'] = 'code';
        $this->AuthorizeUrlParameters['nonce'] = $nonce;
        $this->AuthorizeUrlParameters['scope'] = $this->scope;

        $this->tokenExchangeMethod = 'POST';
        $this->tokenExchangeHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $response = $this->apiRequest('https://id.sber.ru/CSAFront/oidc/userinfo.do');

        if (!isset($response->sub) && !isset($response->id)) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $data = new Data\Collection($response);

        $userProfile = new User\Profile();
        $userProfile->identifier  = $data->get('sub') ? $data->get('sub') : $data->get('id');
        $userProfile->firstName   = $data->get('given_name') ? $data->get('given_name') : $data->get('first_name');
        $userProfile->lastName    = $data->get('family_name') ? $data->get('family_name') : $data->get('last_name');
        $userProfile->displayName = $data->get('name');
        $userProfile->email       = $data->get('email');
        $userProfile->emailVerified = $data->get('email_verified') ? $data->get('email') : null;
        $userProfile->phone       = $data->get('phone_number') ? $data->get('phone_number') : $data->get('mobile');
        $userProfile->gender      = $data->get('gender');

        if (empty($userProfile->displayName)) {
            $userProfile->displayName = trim($userProfile->firstName . ' ' . $userProfile->lastName);
        }

        if ($data->get('birthdate')) {
            $bday = explode('-', $data->get('birthdate'));
            if (count($bday) === 3) {
                $userProfile->birthYear  = (int) $bday[0];
                $userProfile->birthMonth = (int) $bday[1];
                $userProfile->birthDay   = (int) $bday[2];
            }
        }

        return $userProfile;
    }
}
