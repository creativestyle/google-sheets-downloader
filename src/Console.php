<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader;

use Creativestyle\GoogleSheetsDownloader\Console\ClearConfigParamCommand;
use Creativestyle\GoogleSheetsDownloader\Console\DownloadCommand;
use Creativestyle\GoogleSheetsDownloader\Console\SetConfigParamCommand;
use Creativestyle\GoogleSheetsDownloader\Console\ShowConfigCommand;
use Symfony\Component\Console\Application;

/**
 * Entry point for the console application
 *
 * @package Creativestyle\GoogleSheetsDownloader
 */
class Console extends Application
{
    /**
     * @param string $name The name of the application
     * @param string $version The version of the application
     */
    public function __construct(
        $name = 'Google Sheets Downloader',
        $version = 'UNKNOWN'
    ) {
        parent::__construct($name, $version);
        $this->add(new DownloadCommand('download'));
        $this->add(new ShowConfigCommand('config:show'));
        $this->add(new SetConfigParamCommand('config:param:set'));
        $this->add(new ClearConfigParamCommand('config:param:clear'));
    }
}
