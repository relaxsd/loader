<?php

use Relax\Loader\FileFinder\FileFinder;
use Relax\Loader\Filesystem\Filesystem;

class FileFinderTest extends PHPUnit_Framework_TestCase
{
    /** @var  PHPUnit_Framework_MockObject_MockBuilder|Filesystem */
    protected $mockFilesystem;
    /** @var  FileFinder */
    protected $fileFinder;

    public function setUp()
    {
        $this->mockFilesystem = $this
            ->getMockBuilder('Relax\Loader\Contracts\Filesystem')
            ->getMock();

        $this->fileFinder = new FileFinder($this->mockFilesystem);
    }

    public function testAddSinglePath()
    {
        $this->mockFilesystem
            ->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('PATH/NAME.EXT'))
            ->willReturn(true);

        $this->fileFinder->addFileExtensions('EXT');
        $this->fileFinder->addPaths('PATH');

        $expected = 'PATH/NAME.EXT';
        $actual   = $this->fileFinder->find('NAME');
        $this->assertSame($expected, $actual);
    }
}
