<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Contracts\Service;

use Creativestyle\GoogleSheetsDownloader\DataObject;

interface ConfigInterface
{
    /**
     * @return string
     */
    public function getApplicationName();

    /**
     * @return string
     */
    public function getScopes();

    /**
     * Return array comprised of client ID and client secret
     *
     * @return array
     */
    public function getAuthConfig();

    /**
     * Return access token array
     *
     * @return array|null
     */
    public function getAccessToken();

    /**
     * @param array $accessToken
     * @return void
     */
    public function setAccessToken(array $accessToken);

    /**
     * Set config data
     *
     * @param DataObject $configData
     * @return void
     */
    public function setConfigData(DataObject $configData);
}
