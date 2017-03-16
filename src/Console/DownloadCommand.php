<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Console;

use Creativestyle\GoogleSheetsDownloader\DataObject;
use Creativestyle\GoogleSheetsDownloader\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DownloadCommand extends AbstractCommand
{
    const INPUT_KEY_OUTPUT = 'output-path';
    const INPUT_KEY_CONFIG_FILE = 'config-file';
    const INPUT_KEY_GOOGLE_CLIENT_ID = 'google-client-id';
    const INPUT_KEY_GOOGLE_CLIENT_SECRET = 'google-client-secret';
    const INPUT_KEY_GOOGLE_ACCESS_TOKEN = 'google-access-token';
    const INPUT_KEY_GOOGLE_REFRESH_TOKEN = 'google-refresh-token';
    const INPUT_KEY_SPREADSHEET_ID = 'spreadsheet-id';
    const INPUT_KEY_SHEET_TITLE = 'sheet-title';
    const INPUT_KEY_SHEET_CELL_RANGE = 'cell-range';
    const INPUT_KEY_SAVE_PARAMS = 'save';

    protected function configure()
    {
        $this->setDescription('Save Google Docs sheet to the CSV file');
        $this->setDefinition([
            new InputArgument(
                self::INPUT_KEY_OUTPUT,
                InputArgument::OPTIONAL,
                'Path to the output CSV file'
            ),
            new InputOption(
                self::INPUT_KEY_CONFIG_FILE,
                '-c',
                InputOption::VALUE_REQUIRED,
                'Path to config file (JSON)',
                './etc/google-sheets-downloader/config.json'
            ),
            new InputOption(
                self::INPUT_KEY_GOOGLE_CLIENT_ID,
                null,
                InputOption::VALUE_REQUIRED,
                'Google API client ID'
            ),
            new InputOption(
                self::INPUT_KEY_GOOGLE_CLIENT_SECRET,
                null,
                InputOption::VALUE_REQUIRED,
                'Google API client secret'
            ),
            new InputOption(
                self::INPUT_KEY_GOOGLE_ACCESS_TOKEN,
                null,
                InputOption::VALUE_REQUIRED,
                'Google API access token'
            ),
            new InputOption(
                self::INPUT_KEY_GOOGLE_REFRESH_TOKEN,
                null,
                InputOption::VALUE_REQUIRED,
                'Google API refresh token'
            ),
            new InputOption(
                self::INPUT_KEY_SPREADSHEET_ID,
                null,
                InputOption::VALUE_REQUIRED,
                'Spreadsheet ID'
            ),
            new InputOption(
                self::INPUT_KEY_SHEET_TITLE,
                null,
                InputOption::VALUE_REQUIRED,
                'Title of sheet to download'
            ),
            new InputOption(
                self::INPUT_KEY_SHEET_CELL_RANGE,
                null,
                InputOption::VALUE_REQUIRED,
                'Range of sheet cells to save',
                'A1:B'
            ),
            new InputOption(
                self::INPUT_KEY_SAVE_PARAMS,
                '-s',
                InputOption::VALUE_NONE,
                'Save params to the config file'
            )
        ]);
    }

    /**
     * @param InputInterface $input
     * @param DataObject $defaultParams
     * @return DataObject
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function getParams(InputInterface $input, DataObject $defaultParams)
    {
        return new DataObject([
            'output_path' => $input->getArgument(self::INPUT_KEY_OUTPUT) ?: $defaultParams->getOutputPath(),
            'google_client_id' => $input->getOption(self::INPUT_KEY_GOOGLE_CLIENT_ID)
                ?: $defaultParams->getGoogleClientId(),
            'google_client_secret' => $input->getOption(self::INPUT_KEY_GOOGLE_CLIENT_SECRET)
                ?: $defaultParams->getGoogleClientSecret(),
            'google_access_token' => $input->getOption(self::INPUT_KEY_GOOGLE_ACCESS_TOKEN)
                ?: $defaultParams->getGoogleAccessToken(),
            'google_access_token_type' => $defaultParams->getGoogleAccessTokenType() ?: 'Bearer',
            'google_access_token_expires_in' => $defaultParams->getGoogleAccessTokenExpiresIn() ?: 3600,
            'google_refresh_token' => $input->getOption(self::INPUT_KEY_GOOGLE_REFRESH_TOKEN)
                ?: $defaultParams->getGoogleRefreshToken(),
            'google_access_token_created' => $defaultParams->getGoogleAccessTokenCreated() ?: 0,
            'spreadsheet_id' => $input->getOption(self::INPUT_KEY_SPREADSHEET_ID) ?: $defaultParams->getSpreadsheetId(),
            'sheet_title' => $input->getOption(self::INPUT_KEY_SHEET_TITLE) ?: $defaultParams->getSheetTitle(),
            'cell_range' => $input->getOption(self::INPUT_KEY_SHEET_CELL_RANGE) ?: $defaultParams->getCellRange()
        ]);
    }

    /**
     * @param string $outputPath
     * @return bool
     * @throws Exception
     */
    protected function validateOutputPath($outputPath)
    {
        if (!$outputPath) {
            throw new Exception('Output path must be provided either by config file or by command line parameter');
        }
        return true;
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
            $params = $this->getParams($input, $this->loadParamsFromConfigFile($configFilePath));

            $this->validateOutputPath($params->getOutputPath());

            $config = $this->getConfig();
            $config->setConfigData($params);

            $googleClient = $this->getGoogleClient();
            $this->getGoogleClientConfigurator()->configure($googleClient);

            if (!$googleClient->getAccessToken()) {
                $authUrl = $googleClient->createAuthUrl();
                $output->writeln(sprintf('<comment>%s</comment>', $authUrl));
                $question = new Question(
                    '<question>Open above link in your browser,'
                    . 'authorize the application and paste generated auth code:</question> '
                );
                $authCode = $this->getHelper('question')->ask($input, $output, $question);
                $accessToken = $googleClient->fetchAccessTokenWithAuthCode($authCode);
                $config->setAccessToken($accessToken);
            }

            $googleSheets = $this->getGoogleSheets();
            $csvData = $googleSheets->getValues(
                $params->getSpreadsheetId(),
                $params->getSheetTitle(),
                $params->getCellRange()
            );
            $this->getCsvWriter()->write($csvData, $params->getOutputPath());

            if ($input->getOption(self::INPUT_KEY_SAVE_PARAMS)) {
                $this->saveParams($config->getData(), $configFilePath);
            }

            $output->writeln(sprintf(
                '<info>\'%s\' sheet from \'%s\' spreadsheet saved to %s</info>',
                $params->getSheetTitle(),
                $params->getSpreadsheetId(),
                $params->getOutputPath()
            ));

            return 0;
        } catch (\Exception $e) {
            $output->writeln(
                '<error>Error occurred when downloading Google sheet!</error>' . PHP_EOL . $e->getMessage()
            );
            return $e->getCode();
        }
    }
}
