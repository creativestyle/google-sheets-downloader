<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Console;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowConfigCommand extends AbstractCommand
{
    const INPUT_KEY_CONFIG_FILE = 'config-file';

    protected function configure()
    {
        $this->setDescription('Show Google Sheets Downloader configuration');
        $this->setDefinition([
            new InputOption(
                self::INPUT_KEY_CONFIG_FILE,
                '-c',
                InputOption::VALUE_REQUIRED,
                'Path to config file (JSON)',
                './etc/google-sheets-downloader/config.json'
            )
        ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $configFilePath = $input->getOption(self::INPUT_KEY_CONFIG_FILE);
            $params = $this->loadParamsFromConfigFile($configFilePath);
            $table = new Table($output);
            $table->setHeaders(['Param name', 'Param value']);
            foreach ($params->getData() as $paramKey => $paramValue) {
                $table->addRow([$paramKey, $paramValue]);
            }
            $table->render();
            return 0;
        } catch (\Exception $e) {
            $output->writeln(
                '<error>Error occurred when retrieving configuration!</error>' . PHP_EOL . $e->getMessage()
            );
            return $e->getCode();
        }
    }
}
