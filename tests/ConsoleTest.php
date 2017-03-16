<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Test;

use Creativestyle\GoogleSheetsDownloader\Console;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    /**
     * @var array
     */
    protected $availableCommands = [
        'download' => \Creativestyle\GoogleSheetsDownloader\Console\DownloadCommand::class,
        'config:show' => \Creativestyle\GoogleSheetsDownloader\Console\ShowConfigCommand::class,
        'config:param:set' => \Creativestyle\GoogleSheetsDownloader\Console\SetConfigParamCommand::class,
        'config:param:clear' => \Creativestyle\GoogleSheetsDownloader\Console\ClearConfigParamCommand::class
    ];

    /**
     * @var Console
     */
    protected $consoleAppInstance;

    protected function setUp()
    {
        $this->consoleAppInstance = new Console();
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(Console::class, $this->consoleAppInstance);
    }

    public function testItHandlesAllAvailableCommands()
    {
        foreach ($this->availableCommands as $commandName => $commandClassName) {
            $this->assertInstanceOf($commandClassName, $this->consoleAppInstance->get($commandName));
        }
    }
}
