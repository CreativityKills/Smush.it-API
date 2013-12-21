<?php namespace Neo\Smushit;

use Neo\Smushit\Exception\SmushitException;

class Smushit {

	/**
	 * Get the api URL.
	 *
	 * @var string
	 */
	protected $api = 'http://www.smushit.com/ysmush.it/ws.php?img=';

	/**
	 * Maximum bytes allowed by smushit.
	 *
	 * @var integer
	 */
	protected $maxbytes = 1024000;

	/**
	 * Smush the file URL.
	 *
	 * @param  string $url
	 * @return object
	 */
	public static function make($url)
	{
		$smushit = new static;

		// This just checks and validates if the parameters needed
		// to have a successful request are correct. Throws an
		// Exception if something is invalid.
		$smushit->_validate_process( $url );

		// Make the request
		$response = $smushit->_smush($url);

		return $response;
	}

	/**
	 * Send a HTTP request to the smushit api.
	 *
	 * @param  string $url
	 * @return object
	 */
	protected function _smush($url)
	{
		$ch = curl_init($this->api.$url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);

		curl_close($ch);

		$response = json_decode($response);

		if (isset($response->error))
		{
			$this->_haltProcess('Smushit API Error: '.$response->error);
		}

		return $response;
	}

	/**
	 * Validate the process.
	 *
	 * @param  string $url
	 * @return null
	 * @throws Neo\Smushit\Exception\SmushitException
	 */
	protected function _validate_process($url)
	{
		if ($url === null or empty($url))
		{
			$this->_haltProcess("File URL cannot be left empty.");
		}

		if (strpos($url, 'http://') !== 0)
		{
			$this->_haltProcess("Only HTTP protocol is supported.");
		}

		if ( ! ($file_size = @filesize($url)))
		{
			foreach ( get_headers($url) as $header )
			{
			    if (preg_match('/Content-Length: /', $header))
			    {
			        $file_size = substr($header, 15);

			        break;
			    }
			}
		}

		if ($file_size > $this->maxbytes)
		{
			$this->_haltProcess("This image is too large to be smushed.");
		}

		if ( ! function_exists('curl_init'))
		{
			$this->_haltProcess("PHP cURL is not installed on this server.");
		}

		if ( ! function_exists('json_decode'))
		{
			$this->_haltProcess("PHP JSON is not installed on this server.");
		}
	}

	/**
	 * Throws an exception.
	 *
	 * @param  string $message
	 * @return null
	 * @throws Neo\Smushit\Exception\SmushitException
	 */
	protected function _haltProcess($message)
	{
		throw new SmushitException($message);
	}

}