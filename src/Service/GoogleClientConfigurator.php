<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Service;

use Creativestyle\GoogleSheetsDownloader\Contracts\Service\ConfigInterface;
use Creativestyle\GoogleSheetsDownloader\Contracts\Service\GoogleClientConfiguratorInterface;

class GoogleClientConfigurator implements GoogleClientConfiguratorInterface
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param array|null $accessToken
     * @return bool
     */
    protected function validateAccessToken(array $accessToken = null)
    {
        if (null === $accessToken) {
            return false;
        }
        if (!isset($accessToken['access_token'])) {
            return false;
        }
        return true;
    }

    /**
     * @param \Google_Client $googleClient
     * @return void
     */
    public function configure(\Google_Client $googleClient)
    {
        $googleClient->setApplicationName($this->config->getApplicationName());
        $googleClient->setScopes($this->config->getScopes());
        $googleClient->setAuthConfig($this->config->getAuthConfig());
        $googleClient->setAccessType('offline');
        $accessToken = $this->config->getAccessToken();
        if ($this->validateAccessToken($accessToken)) {
            $googleClient->setAccessToken($this->config->getAccessToken());
            if ($googleClient->isAccessTokenExpired()) {
                $googleClient->fetchAccessTokenWithRefreshToken($googleClient->getRefreshToken());
                $this->config->setAccessToken($googleClient->getAccessToken());
            }
        }
    }
}
