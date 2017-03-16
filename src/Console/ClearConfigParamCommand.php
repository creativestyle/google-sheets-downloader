<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearConfigParamCommand extends AbstractCommand
{
    const INPUT_KEY_PARAM_NAME = 'param-name';
    const INPUT_KEY_CONFIG_FILE = 'config-file';

    protected function configure()
    {
        $this->setDescription('Clears selected configuration parameters');
        $this->setDefinition([
            new InputArgument(
                self::INPUT_KEY_PARAM_NAME,
                InputArgument::IS_ARRAY,
                'Config parameter name'
            ),
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
            $paramNames = $input->getArgument(self::INPUT_KEY_PARAM_NAME);
            foreach ($paramNames as $paramName) {
                $params->unsetData($paramName);
            }
            $this->saveParams($params->getData(), $configFilePath);

            $output->writeln(sprintf(
                '<info>Config parameters: %s have been cleared</info>',
                join(', ', $paramNames)
            ));

            return 0;
        } catch (\Exception $e) {
            $output->writeln(
                '<error>Error occurred when clearing configuration parameter!</error>' . PHP_EOL . $e->getMessage()
            );
            return $e->getCode();
        }
    }
}
