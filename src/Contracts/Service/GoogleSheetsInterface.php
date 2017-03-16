<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Contracts\Service;

interface GoogleSheetsInterface
{
    /**
     * @param string $spreadsheetId
     * @param string $sheetTitle
     * @param string $range
     * @return array
     */
    public function getValues($spreadsheetId, $sheetTitle, $range);
}
