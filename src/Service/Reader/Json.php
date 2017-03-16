<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Service\Reader;

use Creativestyle\GoogleSheetsDownloader\Contracts\Service\ReaderInterface;

class Json implements ReaderInterface
{
    /**
     * @inheritdoc
     */
    public function read($path)
    {
        $content = file_get_contents($path);
        return json_decode($content, true);
    }
}
