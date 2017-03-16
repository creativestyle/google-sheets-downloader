<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Contracts\Service;

interface WriterInterface
{
    /**
     * @param array $data
     * @param string $file
     * @return void
     */
    public function write(array $data, $file);
}
