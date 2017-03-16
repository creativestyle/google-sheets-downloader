<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Service;

use Creativestyle\GoogleSheetsDownloader\Contracts\Service\GoogleSheetsInterface;

class GoogleSheets implements GoogleSheetsInterface
{
    /**
     * @var \Google_Service_Sheets
     */
    private $googleSheetsService;

    public function __construct(\Google_Service_Sheets $googleService)
    {
        $this->googleSheetsService = $googleService;
    }

    /**
     * @param string $spreadsheetId
     * @param string $sheetTitle
     * @param string $range
     * @return array
     */
    public function getValues($spreadsheetId, $sheetTitle, $range)
    {
        $response = $this->googleSheetsService->spreadsheets_values->get(
            $spreadsheetId,
            sprintf('%s!%s', $sheetTitle, $range)
        );
        $values = $response->getValues();
        return $values ?: [];
    }
}
