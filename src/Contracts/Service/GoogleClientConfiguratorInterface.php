<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Contracts\Service;

interface GoogleClientConfiguratorInterface
{
    /**
     * @param \Google_Client $googleClient
     * @return void
     */
    public function configure(\Google_Client $googleClient);
}
