<?php declare(strict_types = 1);

namespace Keboola\Csv2XmlProcessor;

use Keboola\Csv;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class Processor
{

    /**
     * @return string[]
     */
    private function getSupportedExtensions(): array {
        return [
            'csv',
        ];
    }

    /**
     * Look up all xls(x) and return their path with desired output file.
     *
     * @param string $inFilesDirPath
     * @param string $outFilesDirPath
     * @return array
     * @throws Exception\InvalidFilePathException
     */
    private function getFilesToProcess(string $inFilesDirPath, string $outFilesDirPath): array {
        $inOuts = [];

        $finder = new Finder();
        $finder->files()->in($inFilesDirPath);

        foreach($this->getSupportedExtensions() as $extension) {
            $finder->files()->name(sprintf('*.%s', $extension));
        }

        // TODO check that tables
        foreach($finder as $file) {
            $outFilePath = sprintf('%s%s/%s.xml',
                $outFilesDirPath,
                mb_substr($file->getPath(), mb_strlen($inFilesDirPath)),
                $file->getBasename(sprintf('.%s', $file->getExtension()))
            );

            $inOuts[] = [
                'input' => $file->getPathname(),
                'output' => $outFilePath
            ];
        }

        return $inOuts;
    }

    /**
     * @param string $filePath
     * @return Processor
     */
    public function prepareOutputFile($filePath): self
    {
        $fileSystem = new FileSystem();

        $fileSystem->dumpFile($filePath, '');

        return $this;
    }

    /**
     * @param string $inFilePath
     * @param string $outFilePath
     * @return Processor
     */
    public function processFile(string $inFilePath, string $outFilePath): self
    {
        $csv = new Csv\CsvFile($inFilePath);

        //$xml = Xml::initByCsv($csv);

        //$fileSystem = new FileSystem();
        //$fileSystem->dumpFile($outFilePath, $xml->toString());

        Xml::fromCsvToFile($csv, $outFilePath);

        return $this;
    }

    /**
     * @param string $inFilesDirPath
     * @param string $outFilesDirPath
     * @return Processor
     */
    public function processDir(string $inFilesDirPath, string $outFilesDirPath): self
    {
        $inOuts = $this->getFilesToProcess($inFilesDirPath, $outFilesDirPath);

        foreach($inOuts as $inOut) {
            $this->processFile($inOut['input'], $inOut['output']);
        }

        return $this;
    }

}
