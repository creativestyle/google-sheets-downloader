<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Contracts\Service;

interface ReaderInterface
{
    /**
     * @param string $path
     * @return array
     */
    public function read($path);
}
