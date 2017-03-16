<?php
/**
 * Google Sheets Downloader
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\GoogleSheetsDownloader\Service\Writer;

use Creativestyle\GoogleSheetsDownloader\Contracts\Service\WriterInterface;
use Creativestyle\GoogleSheetsDownloader\Exception;

class Json implements WriterInterface
{
    /**
     * @param string $path
     * @throws Exception
     */
    protected function createContainerDirectory($path)
    {
        $dirPath = dirname($path);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        if (!is_dir($dirPath)) {
            throw new Exception(sprintf('File node with name \'%s\' exists, but is not a directory', $dirPath));
        }
    }

    /**
     * @inheritdoc
     */
    public function write(array $data, $file)
    {
        $this->createContainerDirectory($file);
        file_put_contents($file, json_encode($data));
    }
}
