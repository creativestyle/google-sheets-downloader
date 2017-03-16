<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Test\Console;

use Creativestyle\GoogleSheetsDownloader\Console\DownloadCommand;
use PHPUnit\Framework\TestCase;

class DownloadCommandTest extends TestCase
{
    /**
     * @var DownloadCommand
     */
    protected $commandInstance;

    protected function setUp()
    {
        $this->commandInstance = new DownloadCommand('test_command');
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(DownloadCommand::class, $this->commandInstance);
    }
}
