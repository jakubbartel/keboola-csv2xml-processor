<?php declare(strict_types = 1);

namespace Keboola\Csv2XmlProcessor;

use Keboola\Csv;
use SimpleXMLElement;
use Symfony\Component\Filesystem\Filesystem;
use XMLWriter;

class Xml
{

    /**
     * @var SimpleXMLElement
     * @deprecated
     */
    private $file;

    /**
     * Private Xml constructor.
     *
     * @param SimpleXMLElement $file
     * @deprecated
     */
    private function __construct(SimpleXMLElement $file)
    {
        $this->file = $file;
    }

    /**
     * @param Csv\CsvFile $csv
     * @return Xml
     * @deprecated
     */
    public static function initByCsv(Csv\CsvFile $csv): self
    {
        $xml = new SimpleXMLElement('<root/>');

        $header = $csv->getHeader();

        foreach($csv as $row) {
            $item = $xml->addChild('item');
            foreach($row as $i => $col) {
                $item->addChild($header[$i], $col);
            }
        }

        return new Xml($xml);
    }

    /**
     * @return string
     * @throws Exception\XmlParsingErrorException
     * @deprecated
     */
    public function toString() {
        $string = $this->file->asXML();

        if($string === false) {
            throw new Exception\XmlParsingErrorException();
        }

        return $string;
    }

    /**
     * @param Csv\CsvFile $csv
     * @param string $filePath
     * @todo root and item node definitions
     */
    public static function fromCsvToFile(Csv\CsvFile $csv, string $filePath): void
    {
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($filePath, '');

        $header = $csv->getHeader();

        $xmlWriter = new XMLWriter();

        $xmlWriter->openMemory();
        $xmlWriter->startDocument('1.0', 'UTF-8');

        $xmlWriter->startElement('root');

        $row_i = 0;

        foreach($csv as $row) {
            if($row_i == 0) {
                $row_i += 1;
                continue;
            }

            $xmlWriter->startElement('item');

            foreach($row as $col_i => $col) {
                $xmlWriter->writeElement($header[$col_i], $col);
            }

            $xmlWriter->endElement();

            // Flush XML to file every 100 rows
            if($row_i % 100 == 0) {
                $fileSystem->appendToFile($filePath, $xmlWriter->flush(true));
            }

            $row_i += 1;
        }

        $xmlWriter->endElement();

        // make to not miss anything
        $fileSystem->appendToFile($filePath, $xmlWriter->flush(true));
    }

}
