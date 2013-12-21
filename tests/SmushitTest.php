<?php

use Neo\Smushit\Smushit;
use Neo\Smushit\Exception\SmushitException;

class SmushitTest extends \PHPUnit_Framework_TestCase {

	public function testIfFailedValidationEmptyUrl()
	{
		$this->_testIfFailedValidation('');
	}

	public function testIfFailedValidationinvalidHTTPProtocol()
	{
		$this->_testIfFailedValidation('https://placehold.it/300x300');
	}

	public function testIfImagesSmushed()
	{
		$image = 'http://placehold.it/300x300';

		$result = Smushit::make($image);

		$this->assertTrue( isset($result->dest_size) && isset($result->percent) && isset($result->dest) );
	}


	protected function _testIfFailedValidation($url)
	{
		$failed = false;

		try
		{
			Smushit::make($url);
		}
		catch(SmushitException $e)
		{
			$failed = true;
		}

		$this->assertTrue($failed);
	}

}