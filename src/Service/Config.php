<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Service;

use Creativestyle\GoogleSheetsDownloader\Contracts\Service\ConfigInterface;
use Creativestyle\GoogleSheetsDownloader\DataObject;

class Config extends DataObject implements ConfigInterface
{
    const APPLICATION_NAME = 'Google Sheets Downloader by creativestyle';
    const SCOPES = [\Google_Service_Sheets::SPREADSHEETS_READONLY];

    /**
     * @inheritdoc
     */
    public function getApplicationName()
    {
        return self::APPLICATION_NAME;
    }

    /**
     * @inheritdoc
     */
    public function getScopes()
    {
        return join(' ', self::SCOPES);
    }

    /**
     * @inheritdoc
     */
    public function getAuthConfig()
    {
        return ['installed' => [
            'client_id' => $this->getData('google_client_id'),
            'client_secret' => $this->getData('google_client_secret'),
            'redirect_uris' => ['urn:ietf:wg:oauth:2.0:oob', 'http://localhost']
        ]];
    }

    /**
     * @inheritdoc
     */
    public function getAccessToken()
    {
        if (!$this->getData('google_access_token')) {
            return null;
        }
        return [
            'access_token' => $this->getData('google_access_token'),
            'token_type' => $this->getData('google_access_token_type') ?: 'Bearer',
            'expires_in' => $this->getData('google_access_token_expires_in') ?: 3600,
            'refresh_token' => $this->getData('google_refresh_token'),
            'created' => $this->getData('google_access_token_created') ?: 0
        ];
    }

    /**
     * @inheritdoc
     */
    public function setAccessToken(array $accessToken)
    {
        if (isset($accessToken['access_token'])) {
            $this->setData('google_access_token', $accessToken['access_token']);
        }
        if (isset($accessToken['token_type'])) {
            $this->setData('google_access_token_type', $accessToken['token_type']);
        }
        if (isset($accessToken['expires_in'])) {
            $this->setData('google_access_token_expires_in', $accessToken['expires_in']);
        }
        if (isset($accessToken['refresh_token'])) {
            $this->setData('google_refresh_token', $accessToken['refresh_token']);
        }
        if (isset($accessToken['created'])) {
            $this->setData('google_access_token_created', $accessToken['created']);
        }
    }

    /**
     * @inheritdoc
     */
    public function setConfigData(DataObject $configData)
    {
        $this->addData($configData->getData());
    }
}
