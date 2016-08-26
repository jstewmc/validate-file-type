<?php
/**
 * The file for the validate-file-type service
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\ValidateFileType;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * The validate-file-type service
 *
 * @since  0.1.0
 */
class Validate
{
	/* !Private properties */
	
	/**
	 * @var    string[]  the allowed types (e.g., ["text/plain", "text/html"])
	 * @since  0.1.0
	 */
	private $types;
	
	
	/* !Magic methods */
	
	/**
	 * Called when the service is constructed
	 *
	 * @param   string[]  $types  the allowed types (case-insensitive)
	 * @since   0.1.0
	 */
	public function __construct(array $types)
	{
		$this->types = array_map('strtolower', $types);
	}
	
	/**
	 * Called when the service is treated like a function
	 *
	 * @param   string  $filename  the file's name
	 * @return  bool
	 * @throws  InvalidArgumentException  if $filename is not readable
	 * @since   0.1.0
	 */
	public function __invoke(string $filename): bool
	{
		// if the file is not readable, short-circuit
		if ( ! is_readable($filename)) {
			throw new InvalidArgumentException(
				__METHOD__ . "() expects parameter one, filename, to be the "
					. "absolute path to a readable file"
			);
		}
		
		// otherwise, get the file's mime-type
		$type = $this->getType($filename);
		
		return in_array(strtolower($type), $this->types);
	}
	
	
	/* !Private methods */
	
	/**
	 * Returns a file's mime-type
	 *
	 * I'll use the FileInfo PHP extension to guess the content-type and encoding of 
	 * a file by looking for certain magic byte sequences at specific positions 
	 * within the file.
	 *
	 * @param   string  $filename  the file's name
	 * @return  string
	 * @throws  BadMethodCallException  if fileinfo php extension is not installed
	 * @see     http://stackoverflow.com/a/23287361  Answer to "How to get file's
	 *     MIME type in PHP" (accessed 8/24/16)
	 * @see     http://php.net/manual/en/intro.fileinfo.php  PHP's FileInfo 
	 *     extension man page (accessed 8/24/16)
	 * @since   0.1.0
	 */
	private function getType(string $filename): string
	{
		// if the fileinfo extension is not loaded, short-circuit
		if ( ! extension_loaded('fileinfo')) {
			throw new RuntimeException(
				"This library requires the fileinfo php extension"
			);
		}
		
		// otherwise, open the file's info
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		
		// get the file's type
		$type = finfo_file($finfo, $filename);
		
		// close the file's info
		finfo_close($finfo);
		
		return $type;
	}
}
