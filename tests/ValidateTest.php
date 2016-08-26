<?php
/**
 * The file for the validate-file-type tests
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\ValidateFileType;

use Jstewmc\TestCase\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 * Tests for the validate-file-type service tests
 */
class ValidateTest extends TestCase
{
	/* !Private properties */
	
	/**
     * @var  vfsStreamDirectory  the "root" virtual file system directory
     */
    private $root;
    
	
	/* !Framework methods */
    
    /**
     * Called before every test
     *
     * @return  void
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('test');
        
        return;
    }
    
	
	/* !__construct() */
	
	/**
	 * __construct() should set the service's properties
	 */
	public function testConstruct()
	{
		$types = ['text/plain', 'text/html'];
		
		$service = (new Validate($types));
		
		$this->assertEquals($types, $this->getProperty('types', $service));
		
		return;
	}
	
	
	/* !__invoke() */
	
	/**
	 * __invoke() should throw exception if the file does not exist
	 */
	public function testInvokeThrowsExceptionIfFileDoesNotExist()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		(new Validate([]))(vfsStream::url('path/to/bar.php'));
		
		return;
	}
	
	/**
	 * __invoke() should return true if the file's type is valid
	 */
	public function testInvokeReturnsTrueIfTypeIsValid()
	{
		$types = ['text/plain'];
		
        $filename = vfsStream::url('test/foo.txt');
        
        $contents = 'foo';
        
        file_put_contents($filename, $contents);
        
		$this->assertTrue((new Validate($types))($filename));
	}
	
	/**
	 * __invoke() should return false if the file's type is not valid
	 */
	public function testInvokeReturnsFalseIfTypeIsNotValid()
	{
		$types = ['text/plain'];
		
        $filename = vfsStream::url('test/foo.xml');
        
        $contents = '<?xml version="1.0" encoding="UTF-8"?><foo />';
        
        file_put_contents($filename, $contents);
		
		$this->assertFalse((new Validate($types))($filename));
		
		return;
	}
}
