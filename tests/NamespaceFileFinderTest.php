<?php

use Relax\Loader\FileFinder\NamespaceFileFinder;
use Relax\Loader\Filesystem\Filesystem;

class NamespaceFileFinderTest extends PHPUnit_Framework_TestCase
{
    /** @var  PHPUnit_Framework_MockObject_MockBuilder|Filesystem */
    protected $mockFilesystem;
    /** @var  NamespaceFileFinder */
    protected $nsFileFinder;

    public function setUp()
    {
        $this->mockFilesystem = $this->getMockBuilder('Relax\Loader\Contracts\Filesystem')->getMock();
        $this->nsFileFinder = new NamespaceFileFinder($this->mockFilesystem);
    }

    public function testAddSinglePath()
    {
        $this->mockFilesystem
            ->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('PATH/NAME.EXT'))
            ->willReturn(true);

        $this->nsFileFinder->addFileExtensions('EXT');
        $this->nsFileFinder->addPaths('PATH');

        $actual = $this->nsFileFinder->find('NAME');
        $expected = 'PATH/NAME.EXT';
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function an_appended_file_extension_will_be_searched_last()
    {
        $this->mockFilesystem->expects($this->at(0))->method('exists')->with($this->equalTo('PATH/NAME.EXT1'))->will($this->returnValue(false));
        $this->mockFilesystem->expects($this->at(1))->method('exists')->with($this->equalTo('PATH/NAME.EXT2'))->will($this->returnValue(true));

        $this->nsFileFinder->addPaths('PATH');
        $this->nsFileFinder->addFileExtensions('EXT1');
        $this->nsFileFinder->addFileExtensions('EXT2');

        $actual = $this->nsFileFinder->find('NAME');
        $expected = 'PATH/NAME.EXT2';
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function an_prepended_file_extension_will_be_searched_first()
    {
        $this->mockFilesystem->expects($this->at(0))->method('exists')->with($this->equalTo('PATH/NAME.EXT2'))->will($this->returnValue(false));
        $this->mockFilesystem->expects($this->at(1))->method('exists')->with($this->equalTo('PATH/NAME.EXT1'))->will($this->returnValue(true));

        $this->nsFileFinder->addPaths('PATH');
        $this->nsFileFinder->addFileExtensions('EXT1');
        $this->nsFileFinder->addFileExtensions('EXT2', true); // Prepend!

        $actual = $this->nsFileFinder->find('NAME');
        $expected = 'PATH/NAME.EXT1';
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_rejects_a_name_with_too_many_namespaces()
    {
        $this->nsFileFinder->find('ILLEGAL::NAME::SPACE');
    }

    /**
     * @testtestAddRepositoryPath
     * @expectedException \Relax\Loader\Filesystem\FileNotFoundException
     */
    public function it_cannot_find_file_starting_with_delimiter()
    {
        $this->nsFileFinder->find('::NAME');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_rejects_a_name_when_namespace_unknown()
    {
        $this->nsFileFinder->find('UNKNOWN::NAME');
    }
}
