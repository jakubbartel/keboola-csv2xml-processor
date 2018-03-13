<?php

namespace Keboola\Csv2XmlProcessor\Tests\Processor;

use Keboola\Csv2XmlProcessor;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{

    public function testProcessOneCsv() : void
    {
        $processor = new Csv2XmlProcessor\Processor();

        $fileSystem = vfsStream::setup();

        $processor->processFile(
            __DIR__ . '/fixtures/process_one_csv/input.csv',
            $fileSystem->url() . '/output.xml'
        );

        $this->assertTrue($fileSystem->hasChild('output.xml'));

        $this->assertFileEquals(
            __DIR__ . '/fixtures/process_one_csv/output.xml',
            $fileSystem->url() . '/output.xml'
        );
    }

}
