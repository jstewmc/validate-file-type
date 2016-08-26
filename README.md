# validate-file-type
Validate a file's mime-type.

```php
use Jstewmc\ValidateFileType\Validate;

// create a txt file
$txtFilename = '/path/to/foo.txt';
$txtContents = 'foo';

file_put_contents($txtFilename, $txtContents);

// create an xml file
$xmlFilename = '/path/to/foo.xml';
$xmlContents = '<?xml version="1.0" encoding="UTF-8"?><foo />';

file_put_contents($xmlFilename, $xmlContents);

// create a service to validate text files
$service = new Validate(['text/plain']);

// validate our two files
$service($txtFilename);  // returns true
$service($xmlFilename);  // returns false
```

This library uses PHP's [fileinfo extension](http://php.net/manual/en/book.fileinfo.php) to look for certain _magic_ byte sequences in a file to guess it's type. While this is not a bulletproof approach, the heuristics used do a very good job.

That's it!

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## License

[MIT](https://github.com/jstewmc/validate-file-type/blob/master/LICENSE)

## Version

### 0.1.0, August 25, 2016

* Initial release
