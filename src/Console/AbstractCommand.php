<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Console;

use Creativestyle\GoogleSheetsDownloader\DataObject;
use Creativestyle\GoogleSheetsDownloader\ServiceContainer;
use Symfony\Component\Console\Command\Command;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AbstractCommand extends Command
{
    /**
     * @var ServiceContainer|null
     */
    protected $serviceContainer = null;

    /**
     * @return ServiceContainer
     */
    protected function getServiceContainer()
    {
        if (null === $this->serviceContainer) {
            $this->serviceContainer = new ServiceContainer([
                'googleapi.client' => \Google_Client::class,
                'googleapi.service_sheets' => \Google_Service_Sheets::class,
                'config' => \Creativestyle\GoogleSheetsDownloader\Service\Config::class,
                'google_client_configurator' =>
                    \Creativestyle\GoogleSheetsDownloader\Service\GoogleClientConfigurator::class,
                'google_sheets' => \Creativestyle\GoogleSheetsDownloader\Service\GoogleSheets::class,
                'csv_writer' => \Creativestyle\GoogleSheetsDownloader\Service\Writer\Csv::class,
                'json_reader' => \Creativestyle\GoogleSheetsDownloader\Service\Reader\Json::class,
                'json_writer' => \Creativestyle\GoogleSheetsDownloader\Service\Writer\Json::class
            ]);
            $this->serviceContainer->compile();
        }
        return $this->serviceContainer;
    }

    /**
     * @return \Google_Client
     */
    protected function getGoogleClient()
    {
        return $this->getServiceContainer()->get('googleapi.client');
    }

    /**
     * @return \Creativestyle\GoogleSheetsDownloader\Contracts\Service\ConfigInterface
     */
    protected function getConfig()
    {
        return $this->getServiceContainer()->get('config');
    }

    /**
     * @return \Creativestyle\GoogleSheetsDownloader\Contracts\Service\GoogleClientConfiguratorInterface
     */
    protected function getGoogleClientConfigurator()
    {
        return $this->getServiceContainer()->get('google_client_configurator');
    }

    /**
     * @return \Creativestyle\GoogleSheetsDownloader\Contracts\Service\GoogleSheetsInterface
     */
    protected function getGoogleSheets()
    {
        return $this->getServiceContainer()->get('google_sheets');
    }

    /**
     * @return \Creativestyle\GoogleSheetsDownloader\Contracts\Service\WriterInterface
     */
    protected function getCsvWriter()
    {
        return $this->getServiceContainer()->get('csv_writer');
    }

    /**
     * @return \Creativestyle\GoogleSheetsDownloader\Contracts\Service\ReaderInterface
     */
    protected function getJsonReader()
    {
        return $this->getServiceContainer()->get('json_reader');
    }

    /**
     * @return \Creativestyle\GoogleSheetsDownloader\Contracts\Service\WriterInterface
     */
    protected function getJsonWriter()
    {
        return $this->getServiceContainer()->get('json_writer');
    }

    /**
     * @param array $params
     * @param string $path
     */
    protected function saveParams(array $params, $path)
    {
        $this->getJsonWriter()->write($params, $path);
    }

    /**
     * @param string $path
     * @return DataObject
     */
    protected function loadParamsFromConfigFile($path)
    {
        $params = [];
        if (file_exists($path)) {
            $params = $this->getJsonReader()->read($path);
        }
        return new DataObject($params);
    }
}
